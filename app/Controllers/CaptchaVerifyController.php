<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class CaptchaVerifyController extends BaseController
{

    protected $session;
    protected $captcha;

    public function __construct(){
        $this->session = Services::session();
        $this->captcha = $this->load->library('captcha');
    }

    public function index()
    {
        $vals = array(
           'word' => 'Random word',
           'img_path' => './captcha/',
           'img_url' => 'http://example.com/captcha/',
           'font_path' => './path/to/fonts/texb.ttf',
           'img_width' => '150',
           'img_height' => 30,
           'expiration' => 7200
           );
            
            $cap = create_captcha($vals);
            echo $cap['image'];
        return view('captcha/index');
    }
}
