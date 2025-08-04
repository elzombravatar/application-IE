<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIntegritesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'   => ['type' => 'INT', 'auto_increment' => true],
            'nom'  => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('integrites');
    }

    public function down()
    {
        $this->forge->dropTable('integrites');
    }
}
