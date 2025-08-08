<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFidTables extends Migration
{
    public function up()
    {
        // Table principale des fiches FID
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['brouillon', 'envoye_client', 'en_attente_client', 'client_valide', 'finalise', 'exporte'],
                'default'    => 'brouillon',
            ],
            
            // === CHANTIER (pré-rempli par IE PRO) ===
            'chantier_nom' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'chantier_adresse' => [
                'type' => 'TEXT',
            ],
            'chantier_numero_pdre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'chantier_contact' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            
            // === ENTREPRISE DE TRAVAUX (toujours IE PRO) ===
            'entreprise_raison_sociale' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'default'    => 'IE PRO',
            ],
            'entreprise_siret' => [
                'type'       => 'VARCHAR',
                'constraint' => 14,
                'null'       => true,
            ],
            
            // === MOA (rempli par le client) ===
            'moa_raison_sociale' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'moa_siret' => [
                'type'       => 'VARCHAR',
                'constraint' => 14,
                'null'       => true,
            ],
            'moa_adresse' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'moa_telephone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'moa_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'moa_nom_signataire_bsda' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'moa_date_evacuation_prevue' => [
                'type'       => 'VARCHAR',
                'constraint' => 10, // Format DD/MM/YYYY
                'null'       => true,
            ],
            
            // === GESTION CLIENT ===
            'client_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'client_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'unique'     => true,
            ],
            'client_token_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'client_form_sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'client_form_completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            
            // === EXPORT ===
            'exported_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'export_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            
            // === MÉTADONNÉES ===
            'created_by' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('reference', 'uk_fid_reference_2025');
        $this->forge->addUniqueKey('client_token', 'uk_client_token_2025');
        $this->forge->addKey('status');
        $this->forge->addKey('created_by');
        $this->forge->addKey('client_email');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('fids');

        // Table des déchets identifiés dans chaque FID
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'fid_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'nom_usuel_dechet' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'integrite' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'code_un' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'code_dechet' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'famille' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'type_conditionnement' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nombre_conditionnements' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'tonnage' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'default'    => 0.000,
            ],
            'ordre' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('fid_id');
        $this->forge->addKey(['fid_id', 'ordre']);
        $this->forge->addForeignKey('fid_id', 'fids', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('fid_dechets');
    }

    public function down()
    {
        $this->forge->dropTable('fid_dechets');
        $this->forge->dropTable('fids');
    }
}