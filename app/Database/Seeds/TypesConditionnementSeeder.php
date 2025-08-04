<?php namespace App\Database\Seeds;

    use CodeIgniter\Database\Seeder;

    class TypesConditionnementSeeder extends Seeder
    {
        public function run()
        {
            $data = [
                ['label' => 'BIG-BAG'],
                ['label' => 'Palette filmée'],
                ['label' => 'Dépôt-bag'],
                ['label' => 'Conteneur-bag']
            ];
            $this->db->table('types_conditionnement')->insertBatch($data);
        }
    }