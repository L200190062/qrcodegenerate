<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QrDocument;
use App\Models\UMhsDetail;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Session;

class ViewFileController extends BaseController
{

    public $qr_document_model;

    public function __construct()
    {
        $this->qr_document_model = new QrDocument();
    }

    public function index($key)
    {
        // $session = Session();
        //$session->set('captcha_verified', false);

        $qrDocument = new QrDocument();
        $uMhsDetail = new UMhsDetail();

        $qrDocument = $qrDocument->where('title', $key)->first();

        $data = $uMhsDetail->where('nim', $qrDocument['nim'])->first();

        var_dump($data);
        die;

        $dataMahasiswa = [
            'nama' => 'Jhon Doe',
            'nim' => 2213443,
            'ttl' => 'Bandung, 01 Januari 2000',
            'tanggal_lulus' => '01 Januari 2022'
        ];

        $file = $this->qr_document_model->where('title', $key)->find();
        $targetUrl = '';

        // redirct ke file
        $targetUrl = base_url($file[0]['pdf_file']);

        // redirect ke halaman lain
        // $targetUrl = base_url('/halaman/index');

        return view('view_file/index', [
            'targetUrl' => $targetUrl,
            'dataMahasiswa' => $dataMahasiswa
        ]);
    }

    public function download($key)
    {
        $session = Session();
        $session->set('captcha_verified', false);

        $file = $this->qr_document_model->where('title', $key)->find();
        $targetUrl = '';

        // redirct ke file
        $targetUrl = base_url($file[0]['pdf_file']);

        // redirect ke halaman lain
        // $targetUrl = base_url('/halaman/index');

        return $this->response->redirect($targetUrl);
    }
}
