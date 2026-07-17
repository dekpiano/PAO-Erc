<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAnnounceTime extends Migration
{
    public function up()
    {
        $fields = [
            'comp_announce_time' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'วันที่ประกาศรายชื่อผู้มีสิทธิ์',
                'after' => 'comp_close_time'
            ],
        ];
        $this->forge->addColumn('Tb_ScienceWeek_Competitions', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_ScienceWeek_Competitions', 'comp_announce_time');
    }
}
