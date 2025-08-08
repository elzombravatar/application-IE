<?php

namespace App\Controllers;

use App\Models\FidModel;
use App\Models\FidDechetModel;
use CodeIgniter\Controller;

class FidController extends Controller
{
    protected $fidModel;
    protected $dechetModel;
    
    public function __construct()
    {
        $this->fidModel = new FidModel();
        $this->dechetModel = new FidDechetModel();
        
        // TODO: Vérifier l'authentification
        // Pour l'instant on simule un utilisateur connecté
        session()->set('user_id', 1); // Admin
    }

    /**
     * Page d'accueil - Liste des FID
     */
    public function index()
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Filtres de recherche
        $filters = [
            'status' => $this->request->getGet('status'),
            'search' => $this->request->getGet('search'),
        ];

        // Récupérer les FID avec pagination
        $fids = $this->fidModel->getFidsWithPagination($limit, $offset, $filters);
        
        // Compter le total pour la pagination
        $total = $this->fidModel->countAll();
        
        $data = [
            'title' => 'Gestion des FID',
            'fids' => $fids,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
                'total_items' => $total,
            ],
            'filters' => $filters,
        ];

        return view('fid/index', $data);
    }

    /**
     * Afficher le formulaire de création d'une FID
     */
    public function create()
    {
        // Charger les données de référence pour les dropdowns
        $data = [
            'title' => 'Créer une nouvelle FID',
            'integrites' => $this->getIntegrites(),
            'codes_un' => $this->getCodesUn(),
            'types_conditionnement' => $this->getTypesConditionnement(),
            'codes_dechets' => $this->getCodesDechets(),
        ];

        return view('fid/create', $data);
    }

    /**
     * Enregistrer une nouvelle FID
     */
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/fid');
        }

        $input = $this->request->getJSON(true);
        
        // Générer une référence unique
        $input['reference'] = $this->fidModel->generateReference();
        $input['created_by'] = session('user_id');

        // Validation
        if (!$this->fidModel->validate($input)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->fidModel->errors()
            ]);
        }

        // Transaction pour créer la FID et ses déchets
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Créer la FID
            $fidId = $this->fidModel->insert($input);
            
            // Ajouter les déchets si présents
            if (!empty($input['dechets'])) {
                foreach ($input['dechets'] as $dechet) {
                    $this->dechetModel->addDechetToFid($fidId, $dechet);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Erreur lors de la sauvegarde');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'FID créée avec succès',
                'fid_id' => $fidId,
                'reference' => $input['reference']
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Voir une FID en lecture seule
     */
    public function show($id)
    {
        $fid = $this->fidModel->getFidWithDechets($id);
        
        if (!$fid) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('FID introuvable');
        }

        // Calculer les totaux
        $totalTonnage = $this->dechetModel->getTotalTonnageForFid($id);
        $totalConditionnements = $this->dechetModel->getTotalConditionnements($id);

        $data = [
            'title' => 'FID ' . $fid['reference'],
            'fid' => $fid,
            'totaux' => [
                'tonnage' => $totalTonnage,
                'conditionnements' => $totalConditionnements,
            ],
        ];

        return view('fid/show', $data);
    }

    /**
     * Afficher le formulaire d'édition d'une FID
     */
    public function edit($id)
    {
        $fid = $this->fidModel->getFidWithDechets($id);
        
        if (!$fid) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('FID introuvable');
        }

        // Vérifier si la FID peut être modifiée
        if ($fid['status'] === 'exporte') {
            return redirect()->to("/fid/show/{$id}")
                           ->with('error', 'Cette FID ne peut plus être modifiée car elle a été exportée.');
        }

        $data = [
            'title' => 'Modifier FID ' . $fid['reference'],
            'fid' => $fid,
            'integrites' => $this->getIntegrites(),
            'codes_un' => $this->getCodesUn(),
            'types_conditionnement' => $this->getTypesConditionnement(),
            'codes_dechets' => $this->getCodesDechets(),
        ];

        return view('fid/edit', $data);
    }

    /**
     * Mettre à jour une FID
     */
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/fid');
        }

        $fid = $this->fidModel->find($id);
        if (!$fid) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'FID introuvable'
            ]);
        }

        $input = $this->request->getJSON(true);

        // Transaction pour mettre à jour la FID et ses déchets
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Mettre à jour la FID
            $this->fidModel->update($id, $input);

            // Mettre à jour les déchets si présents
            if (isset($input['dechets'])) {
                $this->dechetModel->updateDechetsForFid($id, $input['dechets']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Erreur lors de la mise à jour');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'FID mise à jour avec succès'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Supprimer une FID
     */
    public function delete($id)
    {
        $fid = $this->fidModel->find($id);
        if (!$fid) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'FID introuvable'
            ]);
        }

        // Vérifier si la FID peut être supprimée
        if ($fid['status'] === 'exporte') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cette FID ne peut pas être supprimée car elle a été exportée'
            ]);
        }

        if ($this->fidModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'FID supprimée avec succès'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la suppression'
        ]);
    }

    // === MÉTHODES PRIVÉES POUR CHARGER LES DONNÉES DE RÉFÉRENCE ===

    private function getIntegrites()
    {
        $db = \Config\Database::connect();
        return $db->table('integrites')->get()->getResultArray();
    }

    private function getCodesUn()
    {
        $db = \Config\Database::connect();
        return $db->table('codes_un')->get()->getResultArray();
    }

    private function getTypesConditionnement()
    {
        $db = \Config\Database::connect();
        return $db->table('types_conditionnement')->get()->getResultArray();
    }

    private function getCodesDechets()
    {
        $db = \Config\Database::connect();
        return $db->table('codes_dechets')
                 ->orderBy('code', 'ASC')
                 ->get()
                 ->getResultArray();
    }
}