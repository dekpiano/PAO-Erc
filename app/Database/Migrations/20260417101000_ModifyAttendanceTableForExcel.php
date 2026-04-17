<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyAttendanceTableForExcel extends Migration
{
    public function up()
    {
        $fields = [
            'atd_status' => [
                'type'       => 'ENUM',
                'constraint' => ['มา', 'ขาด', 'ลา', 'สาย', 'ไปราชการ'],
                'null'       => true,
                'after'      => 'atd_type'
            ],
            'atd_date' => [
                'type'  => 'DATE',
                'null'  => true,
                'after' => 'atd_timestamp'
            ]
        ];
        $this->forge->addColumn('Tb_Attendance', $fields);
        
        // Add index to atd_date for performance
        $this->db->query('ALTER TABLE Tb_Attendance ADD INDEX (atd_date)');
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Attendance', ['atd_status', 'atd_date']);
    }
}
