<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QrDocument;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use TCPDF;

class QrController extends BaseController
{
    protected $qrModel;

    public function __construct()
    {
        $this->qrModel = new QrDocument();
    }

    public function index()
    {
        $data['documents'] = $this->qrModel->findAll();
        return view('qr/index', $data);
    }

    public function create()
    {
        return view('qr/create');
    }

    public function store()
    {
        // Validasi input
        $rules = [
            'title' => 'required|min_length[3]',
            'description' => 'permit_empty',
            'pdf_file' => 'uploaded[pdf_file]|mime_in[pdf_file,application/pdf]|max_size[pdf_file,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload PDF Original
        $pdf = $this->request->getFile('pdf_file');
        $originalName = $pdf->getRandomName();
        $pdf->move(FCPATH . 'uploads/pdf/original', $originalName);

        // Generate nama untuk file final
        $finalPdfName = 'with_qr_' . $originalName;
        $qrCodeName = 'qr_' . uniqid() . '.png';

        // Generate QR Code
        $pdfUrl = base_url('uploads/pdf/final/' . $finalPdfName);
        
        $writer = new PngWriter();
        $qrCode = new QrCode('/captcha?nexUrl=' . $pdfUrl);
        $qrCode->setSize(300)
               ->setMargin(10);

        $result = $writer->write($qrCode);
        
        // Simpan QR Code
        $result->saveToFile(FCPATH . 'uploads/qrcodes/' . $qrCodeName);

        // Buat PDF baru dengan QR Code
        $this->createPdfWithQrCode(
            FCPATH . 'uploads/pdf/original/' . $originalName,
            FCPATH . 'uploads/qrcodes/' . $qrCodeName,
            FCPATH . 'uploads/pdf/final/' . $finalPdfName
        );

        // Simpan ke database
        $this->qrModel->insert([
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'pdf_file' => $finalPdfName,
            'qr_code' => $qrCodeName,
        ]);

        return redirect()->to('/qr')->with('message', 'Document berhasil ditambahkan');
    }

    private function createPdfWithQrCode($originalPdfPath, $qrCodePath, $outputPath)
    {
        try {
            // Buat instance FPDI
            $pdf = new \setasign\Fpdi\TcpdfFpdi();

            // Baca Halaman dari PDF asli
            $templateId = $pdf->setSourceFile($originalPdfPath);

            // Ambil halaman pertama saja
            $templateId = $pdf->importPage(1);
            $size = $pdf->getTemplateSize($templateId);

            // Tambahkan halaman baru dengan ukuran sesuai template
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            // Tambahkan QR Code di tengah halaman
            $qrSize = 15;
            $qrX = ($size['width'] * 0.6) - ($qrSize / 2);
            $qrY = ($size['height'] * 0.825) - ($qrSize / 2);

            // Memasukan Qr Code ke halaman
            $pdf->Image($qrCodePath, $qrX, $qrY, $qrSize);

            // Simpan file output
            $pdf->Output($outputPath, 'F');
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error creating PDF: ' . $e->getMessage());
            return false;
        }
    }

    public function show($id)
    {
        $data['document'] = $this->qrModel->find($id);
        
        if (!$data['document']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('qr/show', $data);
    }
}
