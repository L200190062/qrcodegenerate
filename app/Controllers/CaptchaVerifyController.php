<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class CaptchaVerifyController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = Services::session();
    }

    public function index()
    {

        log_message('info', 'CAPTCHA index accessed');

        $vals = [
            'img_path' => FCPATH . 'captcha/',
            'img_url' => base_url('captcha/'),
            'font_path' => FCPATH . 'fonts/arial.ttf',
            'img_width' => 300,
            'img_height' => 100,
            'expiration' => 7200
        ];

        log_message('info', 'CAPTCHA values: ' . json_encode($vals));

        $cap = create_captcha($vals);

        if ($cap === false) {
            log_message('error', 'CAPTCHA creation failed');
            return view('captcha/index', ['captcha_image' => 'CAPTCHA creation failed. Please try again.']);
        }

        $this->session->set('captchaWord', $cap['word']);

        log_message('info', 'CAPTCHA word set to: ' . $cap['word']);

        return view('captcha/index', ['captcha_image' => $cap['image']]);
    }

    public function verify()
    {
        $userInput = $this->request->getPost('captcha');
        $sessionCaptcha = $this->session->get('captchaWord');

        if (strtolower($userInput) === strtolower($sessionCaptcha)) {
            // CAPTCHA verified successfully
            // Redirect to the next request or a specific URL
            $this->session->set('captcha_verified', true);
            return redirect()->to($this->session->get('next_request') ?? base_url());
        } else {
            // CAPTCHA verification failed
            // Redirect back with an error message
            return redirect()->back()->with('error', 'CAPTCHA verification failed. Please try again.');
        }
    }
}
