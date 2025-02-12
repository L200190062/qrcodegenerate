<?php

namespace App\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use setasign\Fpdi\PdfParser\StreamReader;

class GenerateCertificateController extends BaseController
{
    public function index()
    {
        $dataMahasiswa = [
            'nim' => 'L200190062',
            'nama' => 'JIHAN MUSTIKASARI',
            'program' => 'Sarjana',
            'prodi' => 'Teknik Informatika'
        ];

        $templatePath = FCPATH . 'templates/template.pdf';
        $file = new \CodeIgniter\Files\File($templatePath);

        $qrCodePath = $this->generateQrCode($dataMahasiswa['nim']);

        var_dump($qrCodePath);

        $certificatePath = $this->generateCertificate($file->getRealPath(), $qrCodePath, $dataMahasiswa);

        return $certificatePath;
    }

    private function generateQrCode($nim)
    {
        $qrCodeName = 'qr_' . uniqid() . '.png';

        $writer = new PngWriter();
        $qrCode = new QrCode(base_url('view-file/' . $nim));
        $qrCode->setSize(300)->setMargin(10);
        $result = $writer->write($qrCode);

        $qrOutputName = WRITEPATH . 'qrcodes/' . $qrCodeName;
        $result->saveToFile($qrOutputName);

        return $qrOutputName;
    }

    private function generateCertificate($pdfPath, $qrCodePath, $dataMahasiswa)
    {
        $outputPath = WRITEPATH . 'certificates/' . $dataMahasiswa['nim'] . '.pdf';

        try {
            $pdf = new \setasign\Fpdi\TcpdfFpdi();
            $pdfPath = StreamReader::createByFile($pdfPath);

            $templateId = $pdf->setSourceFile($pdfPath);
            $templateId = $pdf->importPage(1);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            $qrSize = 15;
            $qrX = ($size['width'] * 0.6) - ($qrSize / 2);
            $qrY = ($size['height'] * 0.825) - ($qrSize / 2);
            $pdf->Image($qrCodePath, $qrX, $qrY, $qrSize);

            $pdf->SetFont('Times', 'B', 16.5);

            $pdf->SetXY(137, 86);
            $pdf->Write(0, $dataMahasiswa['nim']);

            $pdf->SetFont('Times', 'B', 18);
            $pdf->SetXY(112, 75);
            $pdf->Write(0, $dataMahasiswa['nama']);

            $pdf->SetFont('Times', 'B', 18);

            $pdf->SetXY(110, 113);
            $pdf->Write(0, $dataMahasiswa['program']);

            $pdf->SetXY(184, 113);
            $pdf->Write(0, $dataMahasiswa['prodi']);

            $pdf->Output($outputPath, 'F');
            return $outputPath;
        } catch (\Exception $e) {
            log_message('error', 'Error creating PDF: ' . $e->getMessage());
            return false;
        }
    }
}