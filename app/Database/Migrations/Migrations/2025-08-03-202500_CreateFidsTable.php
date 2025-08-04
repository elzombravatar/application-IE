
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFidsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'auto_increment' => true],
            'reference'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'chantier_nom'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'chantier_adresse'=> ['type' => 'TEXT', 'null' => true],
            'chantier_code_postal' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'chantier_ville'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'dechets_json'    => ['type' => 'JSON', 'null' => true],
            'created_by'      => ['type' => 'INT', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true, 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('fids');
    }

    public function down()
    {
        $this->forge->dropTable('fids');
    }
}
