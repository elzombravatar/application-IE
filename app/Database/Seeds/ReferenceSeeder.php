<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReferenceSeeder extends Seeder
{
    public function run()
    {
        // === INTÉGRITÉS ===
        $integrites = [
            ['nom' => 'Intègre'],
            ['nom' => 'Non-intègre'],
        ];

        $this->db->table('integrites')->insertBatch($integrites);

        // === CODES UN ===
        $codes_un = [
            ['code' => 'UN 2590', 'description' => null],
            ['code' => 'UN2212', 'description' => null],
            ['code' => 'Non soumis à l\'ADR (DS 168)', 'description' => null],
            ['code' => 'Exemption partielle du 1.1.3.6', 'description' => null],
        ];

        $this->db->table('codes_un')->insertBatch($codes_un);

        // === TYPES DE CONDITIONNEMENT ===
        $types_conditionnement = [
            ['nom' => 'BIG-BAG'],
            ['nom' => 'Palette filmée'],
            ['nom' => 'Dépôt-bag'],
            ['nom' => 'Conteneur-bag'],
            ['nom' => 'SAC'],
            ['nom' => 'CAISSE'],
            ['nom' => 'VRAC'],
            ['nom' => 'FÛT'],
        ];

        $this->db->table('types_conditionnement')->insertBatch($types_conditionnement);

        // === CODES DÉCHETS (échantillon) ===
        $codes_dechets = [
            [
                'code' => '06 07 01*',
                'description' => 'déchets contenant de l\'amiante provenant de l\'électrolyse'
            ],
            [
                'code' => '06 13 04*',
                'description' => 'déchets provenant de la transformation de l\'amiante'
            ],
            [
                'code' => '08 01 17*',
                'description' => 'déchets provenant du décapage de peintures ou vernis contenant des solvants organiques ou autres substances dangereuses'
            ],
            [
                'code' => '08 04 09*',
                'description' => 'déchets de colles et mastics contenant des solvants organiques ou d\'autres substances dangereuses'
            ],
            [
                'code' => '10 13 09*',
                'description' => 'déchets provenant de la fabrication d\'amiante-ciment contenant de l\'amiante'
            ],
            [
                'code' => '12 01 16*',
                'description' => 'déchets de grenaillage contenant des substances dangereuses'
            ],
            [
                'code' => '15 01 11*',
                'description' => 'emballages métalliques contenant une matrice poreuse solide dangereuse (par exemple amiante), y compris des conteneurs à pression vide'
            ],
            [
                'code' => '15 02 02*',
                'description' => 'absorbants, matériaux filtrants (y compris les filtres à huile non spécifiés ailleurs), chiffons d\'essuyage et vêtements de protection contaminés par des substances dangereuses'
            ],
            [
                'code' => '16 01 11*',
                'description' => 'patins de freins contenant de l\'amiante'
            ],
            [
                'code' => '16 02 12*',
                'description' => 'équipements mis au rebut contenant de l\'amiante libre'
            ],
            [
                'code' => '16 02 13*',
                'description' => 'équipements mis au rebut contenant des composants dangereux (3) autres que ceux visés aux rubriques 16 02 09 à 16 02 12'
            ],
            [
                'code' => '17 01 06*',
                'description' => 'mélanges ou fractions séparées de béton, briques, tuiles et céramiques contenant des substances dangereuses'
            ],
            [
                'code' => '17 02 04*',
                'description' => 'bois, verre et matières plastiques contenant des substances dangereuses ou contaminés par de telles substances'
            ],
            [
                'code' => '17 06 01*',
                'description' => 'matériaux d\'isolation contenant de l\'amiante'
            ],
            [
                'code' => '17 06 03*',
                'description' => 'autres matériaux d\'isolation à base de ou contenant des substances dangereuses'
            ],
            [
                'code' => '17 06 05*',
                'description' => 'matériaux de construction contenant de l\'amiante'
            ],
        ];

        $this->db->table('codes_dechets')->insertBatch($codes_dechets);

        echo "Données de référence insérées avec succès !\n";
    }
}