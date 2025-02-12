<?php

namespace App\Controllers;

use App\Models\BulkQrJob;
use App\Models\QrDocument;
use CodeIgniter\Log\Exceptions\LogException;
use CodeIgniter\Log\Logger;
use ZipArchive;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Smalot\PdfParser\Parser;

class BulkQrController extends BaseController
{
    protected $bulkQrModel;

    public function __construct()
    {
        $this->bulkQrModel = new BulkQrJob();
    }

    public function index()
    {
        $data['jobs'] = $this->bulkQrModel->orderBy('created_at', 'DESC')->findAll();
        return view('bulk_qr/index', $data);
    }

    public function create()
    {
        return view('bulk_qr/create');
    }

    public function process()
    {
        // Add this check at the beginning of the process method
        if (!extension_loaded('zip')) {
            return redirect()->back()->with('error', 'ZIP extension is not installed. Please contact your administrator.');
        }

        if (!extension_loaded('gd')) {
            return redirect()->back()->with('error', 'GD extension is not installed. Please contact your administrator.');
        }

        // Validate files
        $files = $this->request->getFiles('pdf_files');
        if (empty($files)) {
            return redirect()->back()->with('error', 'No files selected');
        }

        // Create job record
        $jobName = 'Job_' . date('YmdHis');
        $jobId = $this->bulkQrModel->insert([
            'job_name' => $jobName,
            'total_files' => count($files['pdf_files']),
            'status' => 'processing'
        ]);

        // Create necessary directories
        $processDir = FCPATH . 'uploads/bulk/' . $jobName;
        mkdir($processDir . '/original', 0777, true);
        mkdir($processDir . '/qrcodes', 0777, true);
        mkdir($processDir . '/final', 0777, true);

        // Process each uploaded PDF file
        $processedFiles = 0;
        foreach ($files['pdf_files'] as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                try {
                    $originalName = $file->getRandomName();
                    $finalPdfName = 'with_qr_' . $originalName;
                    $qrCodeName = 'qr_' . uniqid() . '.png';

                    // Baca Nim Dari PDF
                    $nim = $this->getNimFromPdf($file);

                    // Move uploaded file
                    $file->move($processDir . '/original', $originalName);

                    // Generate QR Code
                    $pdfUrl = 'uploads/bulk/' . $jobName . '/final/' . $finalPdfName;

                    log_message('info', 'Result NIM: ' . $nim);

                    $qrDocumentModel = new QrDocument();
                    $qrDocumentModel->insert([
                        'job_id' => $jobId,
                        'nim' => $nim,
                        'title' => $finalPdfName,
                        'description' => '',
                        'pdf_file' => $pdfUrl,
                        'qr_code' => $qrCodeName,
                    ]);

                    $writer = new PngWriter();
                    $qrCode = new QrCode(base_url('view-file/' . $finalPdfName));
                    $qrCode->setSize(300)->setMargin(10);
                    $result = $writer->write($qrCode);
                    $result->saveToFile($processDir . '/qrcodes/' . $qrCodeName);

                    // Create PDF with QR Code
                    $this->createPdfWithQrCode(
                        $processDir . '/original/' . $originalName,
                        $processDir . '/qrcodes/' . $qrCodeName,
                        $processDir . '/final/' . $finalPdfName
                    );

                    $processedFiles++;
                    $this->bulkQrModel->update($jobId, ['processed_files' => $processedFiles]);
                } catch (\Exception $e) {
                    log_message('error', 'Error processing file ' . $file->getName() . ': ' . $e->getMessage());
                }
            }
        }

        // Create ZIP file
        $zipName = $jobName . '.zip';
        $zipPath = FCPATH . 'uploads/bulk/' . $zipName;
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $finalFiles = glob($processDir . '/final/*');
            foreach ($finalFiles as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        // Update job status
        $this->bulkQrModel->update($jobId, [
            'status' => 'completed',
            'zip_file' => $zipName
        ]);

        return redirect()->to('bulk-qr')->with('message', 'Bulk processing completed');
    }

    public function download($id)
    {
        $job = $this->bulkQrModel->find($id);
        if (!$job || !$job['zip_file']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $zipPath = FCPATH . 'uploads/bulk/' . $job['zip_file'];
        return $this->response->download($zipPath, null);
    }
    public function view($id)
    {
        $qrdocumentmodel = new QrDocument();
        $qr_documents = $qrdocumentmodel->where('job_id', $id)->find();
        if (!$qr_documents) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['qr_documents'] = $qr_documents;
        return view('bulk_qr/view', $data);
    }

    public function getNimFromPdf($path)
    {
        log_message('error', "PDF PATH : " . $path);

        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($path);
            $text = $pdf->getText();

            preg_match('/NIM \s*([\w]+)/', $text, $matches);

            return !empty($matches[1]) ? $matches[1] : false;
        } catch (\Exception $e) {
            log_message('error', 'Error extracting NIM: ' . $e->getMessage());
            return false;
        }
    }

    private function createPdfWithQrCode($originalPdfPath, $qrCodePath, $outputPath)
    {
        try {
            $pdf = new \setasign\Fpdi\TcpdfFpdi();
            $templateId = $pdf->setSourceFile($originalPdfPath);
            $templateId = $pdf->importPage(1);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            $qrSize = 15;
            $qrX = ($size['width'] * 0.6) - ($qrSize / 2);
            $qrY = ($size['height'] * 0.825) - ($qrSize / 2);
            $pdf->Image($qrCodePath, $qrX, $qrY, $qrSize);

            $pdf->Output($outputPath, 'F');
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error creating PDF: ' . $e->getMessage());
            return false;
        }
    }
}