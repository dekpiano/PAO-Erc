<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'u_username' => 'admin',
            'u_password' => password_hash('1234', PASSWORD_DEFAULT),
            'u_fullname' => 'Administrator',
            'u_role'     => 'admin',
            'u_created_at' => date('Y-m-d H:i:s'),
            'u_updated_at' => date('Y-m-d H:i:s'),
        ];

        // Simple Queries
        $this->db->table('Tb_Users')->insert($data);

        // Add a regular user
        $dataUser = [
            'u_username' => 'user01',
            'u_password' => password_hash('1234', PASSWORD_DEFAULT),
            'u_fullname' => 'John Doe',
            'u_role'     => 'user',
            'u_created_at' => date('Y-m-d H:i:s'),
            'u_updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('Tb_Users')->insert($dataUser);
    }
}
