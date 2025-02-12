<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QrDocument;
use App\Models\UMhsDetail;
use CodeIgniter\I18n\Time;

class ViewFileController extends BaseController
{

    public $qr_document_model;

    public function __construct()
    {
        $this->qr_document_model = new QrDocument();

    }


    public function index($key)
    {
        $session = Session();
        $session->set('captcha_verified', false);

        $qrDocument = new QrDocument();
        $uMhsDetail = new UMhsDetail();

        $qrDocument = $qrDocument->where('title', $key)->first();

        $data = $uMhsDetail->where('fnim', $qrDocument['nim'])->first();

        // var_dump($data['FNAMA']);
        // die;

        $tgllahir = new Time($data['FTGLLAHIR'], 'Asia/Jakarta', 'id_ID');

        $dataMahasiswa = [
            'nama' => $data['FNAMA'],
            'nim' => $data['FNIM'],
            'ttl' => $data['FTMPLAHIR'] . ', ' . $tgllahir,
            'tanggal_lulus' => date('d F Y', strtotime($data['TG_LULUS']))
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