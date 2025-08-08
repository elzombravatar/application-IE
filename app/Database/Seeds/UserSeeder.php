<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'username'      => 'admin',
                'email'         => 'admin@ie-trans.fr',
                'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
                'first_name'    => 'Admin',
                'last_name'     => 'IE-TRANS',
                'role'          => 'admin',
                'is_active'     => true,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'username'      => 'g.vasiliev',
                'email'         => 'g.vasiliev@ie-trans.fr',
                'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
                'first_name'    => 'Guennadiy',
                'last_name'     => 'VASILIEV',
                'role'          => 'admin',
                'is_active'     => true,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'username'      => 'ibrahim',
                'email'         => 'zthugs.leboss@gmail.com',
                'password_hash' => password_hash('ibrahim123', PASSWORD_BCRYPT),
                'first_name'    => 'Ibrahim',
                'last_name'     => 'Dev',
                'role'          => 'admin',
                'is_active'     => true,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'username'      => 'employe1',
                'email'         => 'employe@ie-trans.fr',
                'password_hash' => password_hash('employe123', PASSWORD_BCRYPT),
                'first_name'    => 'Employé',
                'last_name'     => 'Test',
                'role'          => 'employe',
                'is_active'     => true,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($users);

        echo "Utilisateurs créés :\n";
        echo "- admin / admin123 (admin)\n";
        echo "- g.vasiliev / password123 (admin)\n";
        echo "- ibrahim / ibrahim123 (admin)\n";
        echo "- employe1 / employe123 (employe)\n";
    }
}