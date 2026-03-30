<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScholarshipBookingTables extends Migration
{
    public function up()
    {
        // ตาราง Slots - ช่วงเวลาที่เปิดให้จอง
        $this->forge->addField([
            'slot_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'slot_scholarship_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'slot_date' => [
                'type' => 'DATE',
            ],
            'slot_start_time' => [
                'type' => 'TIME',
            ],
            'slot_end_time' => [
                'type' => 'TIME',
            ],
            'slot_max' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 1,
            ],
            'slot_is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'slot_created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('slot_id', true);
        $this->forge->createTable('Tb_Scholarship_Slots', true);

        // ตาราง Bookings - ข้อมูลการจอง
        $this->forge->addField([
            'bk_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'bk_slot_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'bk_fullname' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'bk_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'bk_id_card' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'bk_note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'bk_queue_number' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            'bk_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'checked_in', 'cancelled'],
                'default'    => 'pending',
            ],
            'bk_created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('bk_id', true);
        $this->forge->createTable('Tb_Scholarship_Bookings', true);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Scholarship_Bookings');
        $this->forge->dropTable('Tb_Scholarship_Slots');
    }
}
