<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Page de connexion
     */
    public function login()
    {
        // Si déjà connecté, rediriger vers le dashboard
        if (session()->get('user_id')) {
            return redirect()->to('/fid');
        }

        $data = [
            'title' => 'Connexion - IE-TRANS',
            'error' => session()->getFlashdata('error'),
            'success' => session()->getFlashdata('success'),
        ];

        return view('auth/login', $data);
    }

    /**
     * Traitement de la connexion
     */
    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Veuillez vérifier vos informations.');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember') ? true : false;

        // Rechercher l'utilisateur par email
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Identifiants incorrects.');
        }

        // Vérifier le mot de passe
        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Identifiants incorrects.');
        }

        // Vérifier si le compte est actif
        if (!$user['is_active']) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Votre compte est désactivé. Contactez l\'administrateur.');
        }

        // Mettre à jour la dernière connexion
        $this->userModel->update($user['id'], [
            'last_login_at' => date('Y-m-d H:i:s')
        ]);

        // Créer la session
        $sessionData = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'is_logged_in' => true,
        ];

        session()->set($sessionData);

        // Gestion du "Se souvenir de moi"
        if ($remember) {
            $this->setRememberMeCookie($user['id']);
        }

        // Rediriger vers la page demandée ou le dashboard
        $redirectTo = session()->get('redirect_to') ?? '/fid';
        session()->remove('redirect_to');

        return redirect()->to($redirectTo)
                        ->with('success', 'Connexion réussie ! Bienvenue ' . $user['first_name']);
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        // Supprimer le cookie "Se souvenir de moi"
        if (get_cookie('remember_token')) {
            delete_cookie('remember_token');
        }

        // Détruire la session
        session()->destroy();

        return redirect()->to('/auth/login')
                        ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Page d'inscription (optionnelle)
     */
    public function register()
    {
        // Vérifier si l'inscription est autorisée
        if (!$this->isRegistrationEnabled()) {
            return redirect()->to('/auth/login')
                           ->with('error', 'L\'inscription n\'est pas autorisée.');
        }

        $data = [
            'title' => 'Inscription - IE-TRANS',
            'error' => session()->getFlashdata('error'),
        ];

        return view('auth/register', $data);
    }

    /**
     * Traitement de l'inscription
     */
    public function attemptRegister()
    {
        if (!$this->isRegistrationEnabled()) {
            return redirect()->to('/auth/login');
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[70]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Veuillez corriger les erreurs.');
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role' => 'employe', // Par défaut
            'is_active' => false, // Activation manuelle par admin
        ];

        if ($this->userModel->insert($userData)) {
            return redirect()->to('/auth/login')
                           ->with('success', 'Compte créé ! En attente d\'activation par un administrateur.');
        }

        return redirect()->back()
                        ->with('error', 'Erreur lors de la création du compte.');
    }

    /**
     * Vérification automatique "Se souvenir de moi"
     */
    public function checkRememberMe()
    {
        $token = get_cookie('remember_token');
        
        if ($token && !session()->get('user_id')) {
            // Rechercher l'utilisateur par token (à implémenter si nécessaire)
            // Pour l'instant, on ignore cette fonctionnalité
        }
    }

    /**
     * Middleware pour vérifier l'authentification
     */
    public static function requireAuth()
    {
        if (!session()->get('user_id')) {
            // Sauvegarder la page demandée
            session()->set('redirect_to', current_url());
            
            return redirect()->to('/auth/login')
                           ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
    }

    /**
     * Middleware pour les admins uniquement
     */
    public static function requireAdmin()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/fid')
                           ->with('error', 'Accès réservé aux administrateurs.');
        }
    }

    // === MÉTHODES PRIVÉES ===

    private function setRememberMeCookie($userId)
    {
        $token = bin2hex(random_bytes(32));
        
        // Sauvegarder le token en base (optionnel)
        // $this->userModel->update($userId, ['remember_token' => $token]);
        
        // Créer le cookie (30 jours)
        set_cookie('remember_token', $token, 30 * 24 * 60 * 60);
    }

    private function isRegistrationEnabled()
    {
        // Pour l'instant, désactiver l'inscription publique
        // Seuls les admins peuvent créer des comptes
        return false;
    }
}