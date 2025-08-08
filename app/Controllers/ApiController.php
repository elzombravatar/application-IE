<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class ApiController extends Controller
{
    use ResponseTrait;

    /**
     * API - Récupérer toutes les intégrités
     */
    public function integrites()
    {
        $db = \Config\Database::connect();
        $integrites = $db->table('integrites')->get()->getResultArray();
        
        return $this->respond($integrites);
    }

    /**
     * API - Récupérer tous les codes UN
     */
    public function codesUn()
    {
        $db = \Config\Database::connect();
        $codes = $db->table('codes_un')->get()->getResultArray();
        
        return $this->respond($codes);
    }

    /**
     * API - Récupérer tous les types de conditionnement
     */
    public function typesConditionnement()
    {
        $db = \Config\Database::connect();
        $types = $db->table('types_conditionnement')->get()->getResultArray();
        
        return $this->respond($types);
    }

    /**
     * API - Récupérer tous les codes déchets
     */
    public function codesDechets()
    {
        $db = \Config\Database::connect();
        $codes = $db->table('codes_dechets')
                   ->orderBy('code', 'ASC')
                   ->get()
                   ->getResultArray();
        
        return $this->respond($codes);
    }

    /**
     * API - Recherche SIRENE (simulation pour l'instant)
     */
    public function sirene($siret)
    {
        // Validation basique du SIRET
        if (strlen($siret) !== 14 || !is_numeric($siret)) {
            return $this->failValidationError('SIRET invalide (doit faire 14 chiffres)');
        }

        // Pour l'instant, on simule une réponse
        // TODO: Intégrer la vraie API SIRENE
        $mockResponse = [
            'siret' => $siret,
            'raison_sociale' => 'Entreprise Test SIRET ' . $siret,
            'adresse' => '123 rue de la Test',
            'code_postal' => '75000',
            'ville' => 'Paris',
            'statut' => 'active',
        ];

        return $this->respond([
            'success' => true,
            'data' => $mockResponse,
        ]);
    }

    /**
     * API - Test de nos models
     */
    public function testModels()
    {
        $fidModel = new \App\Models\FidModel();
        $dechetModel = new \App\Models\FidDechetModel();

        $tests = [
            'fid_model_loaded' => class_exists('App\Models\FidModel'),
            'dechet_model_loaded' => class_exists('App\Models\FidDechetModel'),
            'generate_reference' => $fidModel->generateReference(),
            'generate_token' => $fidModel->generateClientToken(),
        ];

        return $this->respond([
            'success' => true,
            'tests' => $tests,
            'message' => 'Models fonctionnels !',
        ]);
    }
}