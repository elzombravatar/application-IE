<?php

namespace App\Controllers;

use App\Libraries\EmailService;

class TestEmail extends BaseController
{
    public function index()
    {
        $mailer = new EmailService();

        $result = $mailer->send(
            'zthugs.leboss@gmail.com', // adresse définie pour tous les tests
            'Test depuis EmailService',
            '<p>✅ Ceci est un test d’envoi via la classe <strong>EmailService</strong>.</p>'
        );

        if ($result === true) {
            return '✅ Email envoyé avec succès à zthugs.leboss@gmail.com';
        } else {
            return '❌ Erreur :<br><pre>' . $result . '</pre>';
        }
    }
}
