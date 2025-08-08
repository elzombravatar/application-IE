<?php

namespace App\Models;

use CodeIgniter\Model;

class FidModel extends Model
{
    protected $table         = 'fids';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;

    // Champs autorisés pour l'insertion/mise à jour
    protected $allowedFields = [
        'reference',
        'status',
        'chantier_nom',
        'chantier_adresse', 
        'chantier_numero_pdre',
        'chantier_contact',
        'entreprise_raison_sociale',
        'entreprise_siret',
        'moa_raison_sociale',
        'moa_siret',
        'moa_adresse',
        'moa_telephone',
        'moa_email',
        'moa_nom_signataire_bsda',
        'moa_date_evacuation_prevue',
        'client_email',
        'client_token',
        'client_token_expires_at',
        'client_form_sent_at',
        'client_form_completed_at',
        'exported_at',
        'export_path',
        'created_by',
    ];

    // Gestion automatique des timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation des données
    protected $validationRules = [
        'reference'          => 'required|max_length[100]|is_unique[fids.reference,id,{id}]',
        'chantier_nom'       => 'required|max_length[200]',
        'chantier_adresse'   => 'required',
        'chantier_numero_pdre' => 'required|max_length[100]',
        'chantier_contact'   => 'required|max_length[100]',
        'client_email'       => 'required|valid_email',
        'created_by'         => 'required|integer',
    ];

    protected $validationMessages = [
        'reference' => [
            'required'   => 'La référence est obligatoire',
            'is_unique'  => 'Cette référence existe déjà',
        ],
        'chantier_nom' => [
            'required'   => 'Le nom du chantier est obligatoire',
        ],
        'client_email' => [
            'required'     => 'L\'email du client est obligatoire',
            'valid_email'  => 'L\'email du client doit être valide',
        ],
    ];

    // Statuts possibles
    const STATUS_BROUILLON        = 'brouillon';
    const STATUS_ENVOYE_CLIENT    = 'envoye_client';
    const STATUS_EN_ATTENTE_CLIENT = 'en_attente_client';
    const STATUS_CLIENT_VALIDE    = 'client_valide';
    const STATUS_FINALISE         = 'finalise';
    const STATUS_EXPORTE          = 'exporte';

    /**
     * Récupère une FID avec ses déchets
     */
    public function getFidWithDechets($id)
    {
        $fid = $this->find($id);
        if (!$fid) {
            return null;
        }

        // Charger les déchets associés
        $dechetModel = new FidDechetModel();
        $fid['dechets'] = $dechetModel->where('fid_id', $id)
                                     ->orderBy('ordre', 'ASC')
                                     ->findAll();

        return $fid;
    }

    /**
     * Récupère toutes les FID avec pagination
     */
    public function getFidsWithPagination($limit = 10, $offset = 0, $filters = [])
    {
        $builder = $this->builder();
        
        // Jointure avec users pour le nom du créateur
        $builder->select('fids.*, users.username as created_by_name')
               ->join('users', 'users.id = fids.created_by');

        // Filtres
        if (!empty($filters['status'])) {
            $builder->where('fids.status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $builder->groupStart()
                   ->like('fids.reference', $filters['search'])
                   ->orLike('fids.chantier_nom', $filters['search'])
                   ->orLike('fids.client_email', $filters['search'])
                   ->groupEnd();
        }

        return $builder->orderBy('fids.created_at', 'DESC')
                      ->limit($limit, $offset)
                      ->get()
                      ->getResultArray();
    }

    /**
     * Génère une référence unique pour une nouvelle FID
     */
    public function generateReference()
    {
        $year = date('y'); // 25 pour 2025
        $month = date('m'); // 08 pour août
        
        // Compter les FID du mois
        $count = $this->where('YEAR(created_at)', date('Y'))
                     ->where('MONTH(created_at)', date('n'))
                     ->countAllResults();
        
        $number = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        return "FID CH {$year} {$number}";
    }

    /**
     * Génère un token sécurisé pour le client
     */
    public function generateClientToken()
    {
        return bin2hex(random_bytes(32)); // Token de 64 caractères
    }

    /**
     * Change le statut d'une FID
     */
    public function changeStatus($id, $newStatus, $userId = null)
    {
        $allowedStatuses = [
            self::STATUS_BROUILLON,
            self::STATUS_ENVOYE_CLIENT,
            self::STATUS_EN_ATTENTE_CLIENT,
            self::STATUS_CLIENT_VALIDE,
            self::STATUS_FINALISE,
            self::STATUS_EXPORTE,
        ];

        if (!in_array($newStatus, $allowedStatuses)) {
            return false;
        }

        return $this->update($id, ['status' => $newStatus]);
    }

    /**
     * Marque une FID comme envoyée au client
     */
    public function markAsSentToClient($id, $token)
    {
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        return $this->update($id, [
            'status' => self::STATUS_EN_ATTENTE_CLIENT,
            'client_token' => $token,
            'client_token_expires_at' => $expiresAt,
            'client_form_sent_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Vérifie si un token client est valide
     */
    public function getFidByValidToken($token)
    {
        return $this->where('client_token', $token)
                   ->where('client_token_expires_at >', date('Y-m-d H:i:s'))
                   ->first();
    }

    /**
     * Marque une FID comme complétée par le client
     */
    public function markAsCompletedByClient($id, $moaData)
    {
        $updateData = array_merge($moaData, [
            'status' => self::STATUS_CLIENT_VALIDE,
            'client_form_completed_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->update($id, $updateData);
    }
}