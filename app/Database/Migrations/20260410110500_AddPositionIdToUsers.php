<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPositionIdToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'u_pos_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'u_fullname',
                'comment'    => 'รหัสตำแหน่ง อ้างอิงจาก Tb_Positions'
            ]
        ];

        if (!$this->db->fieldExists('u_pos_id', 'Tb_Users')) {
            $this->forge->addColumn('Tb_Users', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('u_pos_id', 'Tb_Users')) {
            $this->forge->dropColumn('Tb_Users', 'u_pos_id');
        }
    }
}
