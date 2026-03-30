<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScholarshipTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'sch_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'sch_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'sch_slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'sch_content' => [
                'type' => 'TEXT',
            ],
            'sch_amount' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'sch_deadline' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'sch_status' => [
                'type'       => 'ENUM',
                'constraint' => ['published', 'draft', 'closed'],
                'default'    => 'published',
            ],
            'sch_cover' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'sch_created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'sch_created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'sch_updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('sch_id', true);
        $this->forge->createTable('Tb_Scholarships', true);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Scholarships');
    }
}
