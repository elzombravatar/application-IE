<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCodesUnTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'   => ['type' => 'INT', 'auto_increment' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('codes_un');
    }

    public function down()
    {
        $this->forge->dropTable('codes_un');
    }
}
