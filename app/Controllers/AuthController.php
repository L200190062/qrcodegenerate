<?php

namespace App\Controllers;

use App\Models\UMhsDetail;
use App\Models\User;
use CodeIgniter\Controller;

class AuthController extends Controller
{

    protected $auth;

    public function __construct()
    {
        $this->auth = service('auth');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function checkLogin()
    {
        $nim = $this->request->getPost('nim');
        $mhsModel = new UMhsDetail();
        $userModel = new User();

        $mhs = $mhsModel->findByNIM($nim);
        $user = $userModel->findByNIM($nim);

        if (!$mhs) {
            return redirect()->back()->with('error', 'NIM tidak ditemukan di database mahasiswa.');
        }


        // !strpos(strtolower($mhs['FPredikatLulus']), 'cumlaude')

        $pujian = $mhs['FPredikatLulus'];
        $pujianLowerCase = strtolower($pujian);

        if (!strpos($pujianLowerCase, 'cumlaude')) {
            return redirect()->to('/auth/login')->with('error', 'Anda Tidak Cumlaude');
        }

        if (!$user) {
            $userModel->insert([
                'nim' => $mhs['FNIM'],
                'email' => $mhs['EMAIL'],
                'password' => null
            ]);

            session()->set('nim', $nim);
            return redirect()->to('/auth/set-password');
        }
        if ($user['password']) {
            return redirect()->to('/auth/login-password');
        }
        session()->set('nim', $nim);
        return redirect()->to('/auth/set-password');
    }

    public function setPassword()
    {
        if (!session()->has('nim')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('auth/set_password');
    }

    public function savePassword()
    {
        if (!session()->has('nim')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $password = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
        $nim = session()->get('nim');

        $userModel = new User();
        $userModel->where('nim', $nim)->set(['password' => $password])->update();

        session()->remove('nim');
        return redirect()->to('/auth/login')->with('success', 'Password berhasil disimpan. Silakan login.');
    }

    public function loginWithPassword()
    {
        return view('auth/login_password');
    }

    public function processLogin()
    {
        $nim = $this->request->getPost('nim');
        $password = $this->request->getPost('password');

        $nim = $this->request->getPost('nim');
        $password = $this->request->getPost('password');

        if ($this->auth->login($nim, $password)) {
            return redirect()->to('/mahasiswa');
        } else {
            return redirect()->to('/auth/login')->with('error', 'NIM atau Password salah.');
        }
    }

    public function logout()
    {
        $this->auth->logout();
        return redirect()->to('/auth/login');
    }
}
