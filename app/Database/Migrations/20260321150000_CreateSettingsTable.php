<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            's_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            's_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            's_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            's_description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('s_id', true);
        $this->forge->createTable('Tb_Settings');

        // Insert Default Settings via Migration (or Seeder)
        // I'll use Seeder later or just insert here for simplicity.
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Settings');
    }
}
