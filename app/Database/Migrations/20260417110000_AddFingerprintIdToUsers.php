<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFingerprintIdToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'u_fingerprint_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'u_id'
            ]
        ];
        $this->forge->addColumn('Tb_Users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Users', 'u_fingerprint_id');
    }
}
