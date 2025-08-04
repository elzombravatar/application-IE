<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEntreprisesTravauxTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'auto_increment' => true],
            'fid_id'    => ['type' => 'INT'],
            'nom'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'siret'     => ['type' => 'VARCHAR', 'constraint' => 20],
            'activite'  => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('entreprises_travaux');
    }

    public function down()
    {
        $this->forge->dropTable('entreprises_travaux');
    }
}
