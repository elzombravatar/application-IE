
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMoaProducteursTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'fid_id'     => ['type' => 'INT', 'unique' => true],
            'nom'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'adresse'    => ['type' => 'TEXT'],
            'code_postal'=> ['type' => 'VARCHAR', 'constraint' => 20],
            'ville'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'telephone'  => ['type' => 'VARCHAR', 'constraint' => 20],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('moa_producteurs');
    }

    public function down()
    {
        $this->forge->dropTable('moa_producteurs');
    }
}
