<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAttendanceTypeEnum extends Migration
{
    public function up()
    {
        // Modify atd_type to include excel_import
        $this->db->query("ALTER TABLE Tb_Attendance MODIFY COLUMN atd_type ENUM('check_in', 'check_out', 'excel_import') DEFAULT 'check_in' NOT NULL");
    }

    public function down()
    {
        // Rollback to original enum
        $this->db->query("ALTER TABLE Tb_Attendance MODIFY COLUMN atd_type ENUM('check_in', 'check_out') DEFAULT 'check_in' NOT NULL");
    }
}
