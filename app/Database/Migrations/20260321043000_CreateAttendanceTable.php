<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAttendanceTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'atd_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'atd_user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'atd_type' => [
                'type'       => 'ENUM',
                'constraint' => ['check_in', 'check_out'],
                'default'    => 'check_in',
            ],
            'atd_timestamp' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'atd_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'atd_location' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'atd_note' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'atd_created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'atd_updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('atd_id', true);
        $this->forge->createTable('Tb_Attendance');
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Attendance');
    }
}
