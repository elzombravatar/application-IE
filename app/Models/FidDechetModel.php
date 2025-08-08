<?php

namespace App\Models;

use CodeIgniter\Model;

class FidDechetModel extends Model
{
    protected $table         = 'fid_dechets';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'fid_id',
        'nom_usuel_dechet',
        'integrite',
        'code_un',
        'code_dechet',
        'famille',
        'type_conditionnement',
        'nombre_conditionnements',
        'tonnage',
        'ordre',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';

    // Validation des données
    protected $validationRules = [
        'fid_id'                   => 'required|integer',
        'nom_usuel_dechet'         => 'required|max_length[200]',
        'integrite'                => 'required|max_length[50]',
        'code_un'                  => 'required|max_length[50]',
        'type_conditionnement'     => 'required|max_length[100]',
        'nombre_conditionnements'  => 'required|integer|greater_than_equal_to[0]',
        'tonnage'                  => 'required|decimal|greater_than_equal_to[0]',
        'ordre'                    => 'integer|greater_than[0]',
    ];

    protected $validationMessages = [
        'nom_usuel_dechet' => [
            'required' => 'Le nom du déchet est obligatoire',
        ],
        'integrite' => [
            'required' => 'L\'intégrité est obligatoire',
        ],
        'code_un' => [
            'required' => 'Le code UN est obligatoire',
        ],
        'type_conditionnement' => [
            'required' => 'Le type de conditionnement est obligatoire',
        ],
        'nombre_conditionnements' => [
            'required'                => 'Le nombre de conditionnements est obligatoire',
            'greater_than_equal_to'   => 'Le nombre de conditionnements doit être positif',
        ],
        'tonnage' => [
            'required'                => 'Le tonnage est obligatoire',
            'greater_than_equal_to'   => 'Le tonnage doit être positif',
        ],
    ];

    /**
     * Récupère tous les déchets d'une FID triés par ordre
     */
    public function getDechetsForFid($fidId)
    {
        return $this->where('fid_id', $fidId)
                   ->orderBy('ordre', 'ASC')
                   ->findAll();
    }

    /**
     * Ajoute un déchet à une FID
     */
    public function addDechetToFid($fidId, $dechetData)
    {
        // Calculer l'ordre automatiquement
        $maxOrdre = $this->where('fid_id', $fidId)
                        ->selectMax('ordre')
                        ->first();
        
        $dechetData['fid_id'] = $fidId;
        $dechetData['ordre'] = ($maxOrdre['ordre'] ?? 0) + 1;

        return $this->insert($dechetData);
    }

    /**
     * Met à jour plusieurs déchets d'une FID en une fois
     */
    public function updateDechetsForFid($fidId, $dechetsData)
    {
        // Supprimer tous les anciens déchets de cette FID
        $this->where('fid_id', $fidId)->delete();

        // Insérer les nouveaux déchets
        if (!empty($dechetsData)) {
            foreach ($dechetsData as $index => $dechetData) {
                $dechetData['fid_id'] = $fidId;
                $dechetData['ordre'] = $index + 1;
                
                if (!$this->insert($dechetData)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Supprime un déchet et réorganise les ordres
     */
    public function deleteDechetAndReorder($dechetId)
    {
        $dechet = $this->find($dechetId);
        if (!$dechet) {
            return false;
        }

        $fidId = $dechet['fid_id'];
        $ordreSuppr = $dechet['ordre'];

        // Supprimer le déchet
        $this->delete($dechetId);

        // Réorganiser les ordres des déchets restants
        $this->where('fid_id', $fidId)
             ->where('ordre >', $ordreSuppr)
             ->set('ordre', 'ordre - 1', false)
             ->update();

        return true;
    }

    /**
     * Calcule le tonnage total d'une FID
     */
    public function getTotalTonnageForFid($fidId)
    {
        $result = $this->where('fid_id', $fidId)
                      ->selectSum('tonnage')
                      ->first();
        
        return (float) ($result['tonnage'] ?? 0);
    }

    /**
     * Calcule le nombre total de conditionnements d'une FID
     */
    public function getTotalConditionnements($fidId)
    {
        $result = $this->where('fid_id', $fidId)
                      ->selectSum('nombre_conditionnements')
                      ->first();
        
        return (int) ($result['nombre_conditionnements'] ?? 0);
    }

    /**
     * Duplique les déchets d'une FID vers une autre
     */
    public function duplicateDechetsToFid($sourceFidId, $targetFidId)
    {
        $dechets = $this->getDechetsForFid($sourceFidId);
        
        foreach ($dechets as $dechet) {
            unset($dechet['id']); // Supprimer l'ID pour créer un nouveau
            $dechet['fid_id'] = $targetFidId;
            
            if (!$this->insert($dechet)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Valide les données d'un déchet avant sauvegarde
     */
    public function validateDechetData($data)
    {
        $errors = [];

        // Validation personnalisée du tonnage
        if (isset($data['tonnage']) && $data['tonnage'] <= 0) {
            $errors['tonnage'] = 'Le tonnage doit être supérieur à 0';
        }

        // Validation du nombre de conditionnements
        if (isset($data['nombre_conditionnements']) && $data['nombre_conditionnements'] <= 0) {
            $errors['nombre_conditionnements'] = 'Le nombre de conditionnements doit être supérieur à 0';
        }

        return empty($errors) ? true : $errors;
    }

    /**
     * Formate les données d'un déchet pour l'affichage
     */
    public function formatDechetForDisplay($dechet)
    {
        return [
            'id' => $dechet['id'],
            'nom_usuel_dechet' => $dechet['nom_usuel_dechet'],
            'integrite' => $dechet['integrite'],
            'code_un' => $dechet['code_un'],
            'code_dechet' => $dechet['code_dechet'] ?? '',
            'famille' => $dechet['famille'] ?? '',
            'type_conditionnement' => $dechet['type_conditionnement'],
            'nombre_conditionnements' => (int) $dechet['nombre_conditionnements'],
            'tonnage' => number_format((float) $dechet['tonnage'], 3, ',', ' '),
            'tonnage_raw' => (float) $dechet['tonnage'],
            'ordre' => (int) $dechet['ordre'],
        ];
    }
}