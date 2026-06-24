<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRulesToScienceWeekCompetitionsTable extends Migration
{
    public function up()
    {
        $fields = [
            'comp_rule_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'comp_description',
                'comment'    => 'ไฟล์แนบกติกาการแข่งขัน (.pdf, .doc, .docx)',
            ],
            'comp_rule_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'comp_rule_file',
                'comment'    => 'ลิงก์ภายนอกกติกาการแข่งขัน',
            ],
        ];
        $this->forge->addColumn('Tb_ScienceWeek_Competitions', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_ScienceWeek_Competitions', ['comp_rule_file', 'comp_rule_link']);
    }
}
