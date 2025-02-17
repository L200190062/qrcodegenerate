<?php

namespace App\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use setasign\Fpdi\TcpdfFpdi;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Exceptions\PageNotFoundException;

class GenerateCertificateController extends BaseController
{

    protected $auth;

    public function __construct()
    {
        $this->auth = service('auth');
    }

    public function index()
    {

        $dataMahasiswa = [
            'nim'     => $this->auth->getUser()['nim'],
            'nama'    => 'JIHAN MUSTIKASARI',
            'program' => 'Sarjana',
            'prodi'   => 'Teknik Informatika'
        ];

        $templatePath = FCPATH . 'templates/template.pdf';
        if (!is_file($templatePath)) {
            throw new PageNotFoundException('Template PDF tidak ditemukan.');
        }

        $qrCodePath = $this->generateQrCode($dataMahasiswa['nim']);
        if (!$qrCodePath) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Gagal membuat QR Code.');
        }

        $certificateUrl = $this->generateCertificate($templatePath, $qrCodePath, $dataMahasiswa);
        if (!$certificateUrl) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Gagal membuat sertifikat.');
        }

        return $this->response->setJSON(['certificate_path' => $certificateUrl]);
    }

    private function generateQrCode($nim)
    {
        $qrDir = FCPATH . 'qrcodes/';
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        $qrCodeName = 'qr_' . md5($nim . time()) . '.png';
        $qrOutputPath = $qrDir . $qrCodeName;
        $qrCodeUrl = base_url('qrcodes/' . $qrCodeName);

        try {
            $writer = new PngWriter();
            $qrCode = new QrCode(base_url('view-file/' . $nim));
            $qrCode->setSize(300)->setMargin(10);
            $result = $writer->write($qrCode);
            $result->saveToFile($qrOutputPath);

            return $qrOutputPath;
        } catch (\Exception $e) {
            log_message('error', 'Error creating QR Code: ' . $e->getMessage());
            return false;
        }
    }

    private function generateCertificate($pdfPath, $qrCodePath, $dataMahasiswa)
    {
        $certDir = FCPATH . 'certificates/';
        if (!is_dir($certDir)) {
            mkdir($certDir, 0755, true);
        }

        $outputFilename = $dataMahasiswa['nim'] . '.pdf';
        $outputPath = $certDir . $outputFilename;
        $certificateUrl = base_url('certificates/' . $outputFilename);

        try {
            $pdf = new TcpdfFpdi();
            $templateId = $pdf->setSourceFile($pdfPath);
            $templateId = $pdf->importPage(1);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            $qrSize = 20;
            $qrX = ($size['width'] * 0.6) - ($qrSize / 2);
            $qrY = ($size['height'] * 0.825) - ($qrSize / 2);
            $pdf->Image($qrCodePath, $qrX, $qrY, $qrSize);

            $pdf->SetFont('Times', 'B', 18);
            $this->writeText($pdf, 137, 86, $dataMahasiswa['nim']);
            $this->writeText($pdf, 112, 75, $dataMahasiswa['nama']);
            $this->writeText($pdf, 110, 113, $dataMahasiswa['program']);
            $this->writeText($pdf, 184, 113, $dataMahasiswa['prodi']);

            $pdf->Output($outputPath, 'F');
            return $certificateUrl;
        } catch (\Exception $e) {
            log_message('error', 'Error creating PDF: ' . $e->getMessage());
            return false;
        }
    }

    private function writeText($pdf, $x, $y, $text)
    {
        $pdf->SetXY($x, $y);
        $pdf->Write(0, $text);
    }
}
