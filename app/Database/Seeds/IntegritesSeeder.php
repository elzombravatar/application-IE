<?php namespace App\Database\Seeds;

    use CodeIgniter\Database\Seeder;

    class IntegritesSeeder extends Seeder
    {
        public function run()
        {
            $data = [
                ['label' => 'Intégré'],
                ['label' => 'Non-intégré']
            ];
            $this->db->table('integrites')->insertBatch($data);
        }
    }