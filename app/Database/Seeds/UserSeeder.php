<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'      => 'admin',
            'email'         => 'admin@example.com',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'role'          => 'admin',
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        // Using Query Builder to insert data
        $this->db->table('users')->insert($data);
    }
}
