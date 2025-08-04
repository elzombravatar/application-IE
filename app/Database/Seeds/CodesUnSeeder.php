<?php namespace App\Database\Seeds;

    use CodeIgniter\Database\Seeder;

    class CodesUnSeeder extends Seeder
    {
        public function run()
        {
            $data = [
                ['label' => 'UN 2590'],
                ['label' => 'UN2212'],
                ['label' => 'Non soumis à l'ADR (DS 168)'],
                ['label' => 'Exemption partielle du 1.1.3.6']
            ];
            $this->db->table('codes_un')->insertBatch($data);
        }
    }