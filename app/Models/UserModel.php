<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'username',
        'email', 
        'password_hash',
        'first_name',
        'last_name',
        'role',
        'is_active',
        'last_login_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation des données
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[70]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password_hash' => 'required',
        'role'     => 'in_list[admin,employe]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'   => 'Le nom d\'utilisateur est obligatoire',
            'min_length' => 'Le nom d\'utilisateur doit faire au moins 3 caractères',
            'is_unique'  => 'Ce nom d\'utilisateur existe déjà',
        ],
        'email' => [
            'required'     => 'L\'email est obligatoire',
            'valid_email'  => 'L\'email doit être valide',
            'is_unique'    => 'Cet email existe déjà',
        ],
        'role' => [
            'in_list' => 'Le rôle doit être admin ou employe',
        ],
    ];

    // Constantes pour les rôles
    const ROLE_ADMIN = 'admin';
    const ROLE_EMPLOYE = 'employe';

    /**
     * Créer un nouvel utilisateur avec mot de passe hashé
     */
    public function createUser($userData)
    {
        // Hasher le mot de passe si fourni en clair
        if (isset($userData['password'])) {
            $userData['password_hash'] = password_hash($userData['password'], PASSWORD_BCRYPT);
            unset($userData['password']);
        }

        // Valeurs par défaut
        $userData['role'] = $userData['role'] ?? self::ROLE_EMPLOYE;
        $userData['is_active'] = $userData['is_active'] ?? true;

        return $this->insert($userData);
    }

    /**
     * Mettre à jour le mot de passe d'un utilisateur
     */
    public function updatePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        return $this->update($userId, ['password_hash' => $hashedPassword]);
    }

    /**
     * Récupérer un utilisateur par email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Récupérer un utilisateur par nom d'utilisateur
     */
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Vérifier si un utilisateur est administrateur
     */
    public function isAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === self::ROLE_ADMIN;
    }

    /**
     * Récupérer tous les utilisateurs actifs
     */
    public function getActiveUsers()
    {
        return $this->where('is_active', true)
                   ->orderBy('first_name', 'ASC')
                   ->findAll();
    }

    /**
     * Récupérer tous les employés (non-admin)
     */
    public function getEmployees()
    {
        return $this->where('role', self::ROLE_EMPLOYE)
                   ->where('is_active', true)
                   ->orderBy('first_name', 'ASC')
                   ->findAll();
    }

    /**
     * Récupérer tous les administrateurs
     */
    public function getAdmins()
    {
        return $this->where('role', self::ROLE_ADMIN)
                   ->where('is_active', true)
                   ->orderBy('first_name', 'ASC')
                   ->findAll();
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleUserStatus($userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }

        $newStatus = !$user['is_active'];
        return $this->update($userId, ['is_active' => $newStatus]);
    }

    /**
     * Mettre à jour la dernière connexion
     */
    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Changer le rôle d'un utilisateur
     */
    public function changeUserRole($userId, $newRole)
    {
        if (!in_array($newRole, [self::ROLE_ADMIN, self::ROLE_EMPLOYE])) {
            return false;
        }

        return $this->update($userId, ['role' => $newRole]);
    }

    /**
     * Récupérer les statistiques des utilisateurs
     */
    public function getUserStats()
    {
        $stats = [
            'total' => $this->countAll(),
            'active' => $this->where('is_active', true)->countAllResults(false),
            'inactive' => $this->where('is_active', false)->countAllResults(false),
            'admins' => $this->where('role', self::ROLE_ADMIN)->countAllResults(false),
            'employes' => $this->where('role', self::ROLE_EMPLOYE)->countAllResults(false),
        ];

        return $stats;
    }

    /**
     * Rechercher des utilisateurs
     */
    public function searchUsers($query, $activeOnly = true)
    {
        $builder = $this->builder();

        if ($activeOnly) {
            $builder->where('is_active', true);
        }

        return $builder->groupStart()
                      ->like('username', $query)
                      ->orLike('email', $query)
                      ->orLike('first_name', $query)
                      ->orLike('last_name', $query)
                      ->groupEnd()
                      ->orderBy('first_name', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Formater les données utilisateur pour l'affichage
     */
    public function formatUserForDisplay($user)
    {
        return [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => trim($user['first_name'] . ' ' . $user['last_name']),
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'role' => $user['role'],
            'role_label' => $user['role'] === self::ROLE_ADMIN ? 'Administrateur' : 'Employé',
            'is_active' => $user['is_active'],
            'status_label' => $user['is_active'] ? 'Actif' : 'Inactif',
            'last_login_at' => $user['last_login_at'],
            'last_login_formatted' => $user['last_login_at'] ? 
                date('d/m/Y à H:i', strtotime($user['last_login_at'])) : 
                'Jamais connecté',
            'created_at' => $user['created_at'],
            'created_formatted' => date('d/m/Y', strtotime($user['created_at'])),
        ];
    }

    /**
     * Vérifier si un utilisateur peut effectuer une action
     */
    public function canUserPerformAction($userId, $action)
    {
        $user = $this->find($userId);
        if (!$user || !$user['is_active']) {
            return false;
        }

        switch ($action) {
            case 'create_fid':
            case 'edit_fid':
            case 'delete_fid':
                return true; // Tous les utilisateurs actifs

            case 'manage_users':
            case 'export_fid':
            case 'send_client_form':
                return $user['role'] === self::ROLE_ADMIN;

            default:
                return false;
        }
    }

    /**
     * Validation personnalisée avant insertion/mise à jour
     */
    protected function beforeInsert(array $data)
    {
        return $this->hashPassword($data);
    }

    protected function beforeUpdate(array $data)
    {
        return $this->hashPassword($data);
    }

    private function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
            unset($data['data']['password']);
        }

        return $data;
    }
}