<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmpTypeToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('Tb_Users', [
            'u_emp_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'u_level'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Users', 'u_emp_type');
    }
}
