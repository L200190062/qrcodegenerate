<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class CaptchaFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = Services::session();

        if (!$session->get('captcha_verified')) {
            // Simpan request saat ini agar bisa diarahkan kembali
            $session->set('next_request', current_url());
            // Redirect ke halaman CAPTCHA
            return redirect()->to(site_url('captcha-verify'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $session = Services::session();
        $session->set('captcha_verified', false);


        var_dump($session->get('captcha_verified'));
        die;
    }
}
