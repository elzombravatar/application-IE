<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDechetsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'fid_id'      => ['type' => 'INT'],
            'type'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'code_dechet' => ['type' => 'VARCHAR', 'constraint' => 50],
            'quantite'    => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'unite'       => ['type' => 'VARCHAR', 'constraint' => 20],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('dechets');
    }

    public function down()
    {
        $this->forge->dropTable('dechets');
    }
}
