<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'u_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'u_username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'u_password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'u_fullname' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'u_role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'user',
            ],
            'u_created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'u_updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('u_id', true);
        $this->forge->createTable('Tb_Users');
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Users');
    }
}
