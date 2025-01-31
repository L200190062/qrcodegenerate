<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QrDocument;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Session;

class ViewFileController extends BaseController
{

    public $qr_document_model;

    public function __construct()
    {
        $this->qr_document_model = new QrDocument();
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
        $targetUrl = base_url('/halaman/index');

        return $this->response->redirect($targetUrl);
    }
}
