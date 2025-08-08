<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Si l'utilisateur est déjà connecté, rediriger vers le dashboard
        if (session()->get('user_id')) {
            return redirect()->to('/fid');
        }

        $data = [
            'title' => 'Bienvenue - Groupe IE',
        ];

        return view('welcome', $data);
    }
}