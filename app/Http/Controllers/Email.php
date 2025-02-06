<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Email extends Controller
{
    /**
     * Envoie un email.
     *
     * @param string $destinataire L'adresse email du destinataire.
     * @param string $sujet Le sujet de l'email.
     * @param string $corpsMessage Le corps du message de l'email.
     * @return bool Retourne true si l'email a été envoyé avec succès, sinon false.
     */
    public static function envoyerEmail($destinataire, $sujet, $corpsMessage)
    {
        /*
         * Configuration SMTP (à vérifier)
         * ini_set('SMTP', 'mail.sendix.fr');
         * ini_set('smtp_port', '25');
         * ini_set('sendmail_from', 'noreply@sendix.fr');
         */

        $headers = "From: noreply@sendix.fr\r\n";
        $headers .= "Reply-To: noreply@sendix.fr\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $sujet = "=?UTF-8?B?" . base64_encode($sujet) . "?=";

        if (mail($destinataire, $sujet, $corpsMessage, $headers)) {
            return true;
        } else {
            return false;
        }
    }
}
