<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNewsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'news_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'news_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'news_slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'news_content' => [
                'type' => 'TEXT',
            ],
            'news_category' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'news_cover' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'news_view_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'news_status' => [
                'type'       => 'ENUM',
                'constraint' => ['published', 'draft', 'hidden'],
                'default'    => 'published',
            ],
            'news_created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'news_created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'news_updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('news_id', true);
        // Note: Tb_Users is the table name found in migrations
        $this->forge->addForeignKey('news_created_by', 'Tb_Users', 'u_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('Tb_News');
    }

    public function down()
    {
        $this->forge->dropTable('Tb_News');
    }
}
