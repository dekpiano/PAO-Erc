<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLevelFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'u_level' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'u_position'
            ]
        ];
        $this->forge->addColumn('Tb_Users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Users', 'u_level');
    }
}
