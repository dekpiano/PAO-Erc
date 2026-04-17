<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExpandAttendanceStatuses extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE Tb_Attendance MODIFY COLUMN atd_status ENUM('มา', 'ขาด', 'ลา', 'สาย', 'ไปราชการ', 'ลาป่วย', 'ลากิจ', 'ลาพักผ่อน') DEFAULT NULL");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE Tb_Attendance MODIFY COLUMN atd_status ENUM('มา', 'ขาด', 'ลา', 'สาย', 'ไปราชการ') DEFAULT NULL");
    }
}
