<?php

namespace App\Services;

use App\Models\User;
use CodeIgniter\Session\Session;

class AuthService
{
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new User();
    }

    public function login(string $nim, string $password): bool
    {
        $user = $this->userModel->where('nim', $nim)->first();

        if ($user && password_verify($password, $user['password'])) {
            $this->session->set([
                'isLoggedIn' => true,
                'user_id'    => $user['id'],
                'nim'        => $user['nim'],
            ]);
            return true;
        }
        return false;
    }

    public function isLoggedIn(): bool
    {
        return $this->session->has('isLoggedIn') && $this->session->get('isLoggedIn');
    }

    public function logout()
    {
        $this->session->destroy();
    }

    public function getUser()
    {
        if ($this->isLoggedIn()) {
            return $this->userModel->find($this->session->get('user_id'));
        }
        return null;
    }
}
