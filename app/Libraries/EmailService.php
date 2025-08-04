<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;
use Config\Services;

class EmailService
{
    protected Email $email;

    public function __construct()
    {
        $this->email = Services::email();
    }

    /**
     * Envoie un email HTML
     *
     * @param string|array $to Adresse(s) de destination
     * @param string $subject Sujet du mail
     * @param string $message Contenu HTML du mail
     * @param array $attachments Liste des fichiers à joindre (facultatif)
     * @return bool|string true si OK, sinon message d'erreur
     */
    public function send($to, string $subject, string $message, array $attachments = [])
    {
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);

        // Ajout des pièces jointes si nécessaire
        foreach ($attachments as $filePath) {
            $this->email->attach($filePath);
        }

        if ($this->email->send()) {
            return true;
        } else {
            return $this->email->printDebugger(['headers', 'subject', 'body']);
        }
    }
}
