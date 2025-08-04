<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCodesDechetsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                     => ['type' => 'INT', 'auto_increment' => true],
            'code'                   => ['type' => 'VARCHAR', 'constraint' => 100],
            'description'            => ['type' => 'TEXT'],
            'famille'                => ['type' => 'VARCHAR', 'constraint' => 10],
            'integrite_id'           => ['type' => 'INT'],
            'code_un_id'             => ['type' => 'INT'],
            'type_conditionnement_id'=> ['type' => 'INT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('integrite_id', 'integrites', 'id');
        $this->forge->addForeignKey('code_un_id', 'codes_un', 'id');
        $this->forge->addForeignKey('type_conditionnement_id', 'types_conditionnement', 'id');
        $this->forge->createTable('codes_dechets');
    }

    public function down()
    {
        $this->forge->dropTable('codes_dechets');
    }
}
