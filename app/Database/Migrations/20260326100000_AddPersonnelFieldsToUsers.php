<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPersonnelFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'u_prefix' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'u_password'
            ],
            'u_position' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'u_fullname'
            ],
            'u_division' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'u_position'
            ],
            'u_photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'u_division'
            ],
            'u_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'u_photo'
            ],
            'u_sort' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 99,
                'after'      => 'u_phone'
            ],
            'u_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active',
                'after'      => 'u_sort'
            ]
        ];
        if (!$this->db->fieldExists('u_prefix', 'Tb_Users')) {
            $this->forge->addColumn('Tb_Users', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Users', ['u_prefix', 'u_position', 'u_division', 'u_photo', 'u_phone', 'u_sort', 'u_status']);
    }
}
