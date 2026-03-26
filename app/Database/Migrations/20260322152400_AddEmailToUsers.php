<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'u_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'u_username',
                'unique'     => true,
            ],
            'u_google_sub' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'u_email',
                'unique'     => true,
            ],
        ];
        $this->forge->addColumn('Tb_Users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Users', 'u_email');
    }
}
