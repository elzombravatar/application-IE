<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FidSeeder extends Seeder
{
    public function run()
    {
        // 1. Création d'une fiche FID
        $this->db->table('fids')->insert([
            'reference' => 'FID-EX-001',
            'chantier_nom' => 'Réhabilitation immeuble Victor Hugo',
            'chantier_adresse' => '12 rue Victor Hugo',
            'chantier_code_postal' => '75015',
            'chantier_ville' => 'Paris',
            'dechets_json' => json_encode([
                ['type' => 'Gravats', 'code_dechet' => '17 01 01', 'quantite' => 3.5, 'unite' => 'tonnes'],
                ['type' => 'Bois', 'code_dechet' => '17 02 01', 'quantite' => 1.2, 'unite' => 'tonnes'],
            ]),
            'created_by' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $fidID = $this->db->insertID();

        // 2. Entreprise de travaux liée
        $this->db->table('entreprises_travaux')->insert([
            'fid_id' => $fidID,
            'nom' => 'Travaux BTP Île-de-France',
            'siret' => '84290311200016',
            'activite' => 'Démolition'
        ]);

        // 3. MOA Producteur lié
        $this->db->table('moa_producteurs')->insert([
            'fid_id' => $fidID,
            'nom' => 'Société Immobilière Paris Habitat',
            'adresse' => '45 rue Balard',
            'code_postal' => '75015',
            'ville' => 'Paris',
            'telephone' => '0178203030',
            'email' => 'contact@parishabitat.fr'
        ]);
    }
}
