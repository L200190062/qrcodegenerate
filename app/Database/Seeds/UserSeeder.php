<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nim'        => '1234567890',
                'email'      => 'user1@example.com',
                'password'   => password_hash('password123', PASSWORD_DEFAULT), // Hash password
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'nim'        => '0987654321',
                'email'      => 'user2@example.com',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
