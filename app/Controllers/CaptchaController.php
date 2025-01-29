<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class CaptchaController extends Controller
{
    public function index($uuid)
    {
        return view('captcha_view', ['uuid' => $uuid]);
    }

    public function verify()
    {
        $recaptchaResponse = $this->request->getPost('g-recaptcha-response');

        // Verifikasi Google reCAPTCHA
        $recaptchaSecret = 'YOUR_SECRET_KEY';
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $response = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
        $responseKeys = json_decode($response, true);

        if (intval($responseKeys["success"]) !== 1) {
            return redirect()->back()->with('error', 'Please verify you are not a robot.');
        } else {
            return redirect()->to('success-page'); // Redirect setelah validasi berhasil
        }
    }
}
