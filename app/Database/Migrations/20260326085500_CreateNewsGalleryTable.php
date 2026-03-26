<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNewsGalleryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'gal_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'gal_news_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'gal_image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'gal_created_at datetime default current_timestamp',
        ]);
        $this->forge->addKey('gal_id', true);
        // $this->forge->addForeignKey('gal_news_id', 'Tb_News', 'news_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('Tb_News_Gallery', true);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_News_Gallery');
    }
}
