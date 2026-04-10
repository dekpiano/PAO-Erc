<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSubstituteToLeaveRequests extends Migration
{
    public function up()
    {
        $fields = [
            'leave_substitute_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'leave_contact',
                'comment'    => 'รหัสผู้ใช้งานที่รับมอบหมายงานแทน (ใช้สำหรับลาพักผ่อน)'
            ]
        ];

        if (!$this->db->fieldExists('leave_substitute_id', 'Tb_Leave')) {
            $this->forge->addColumn('Tb_Leave', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('leave_substitute_id', 'Tb_Leave')) {
            $this->forge->dropColumn('Tb_Leave', 'leave_substitute_id');
        }
    }
}
