<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email extends Controller
{
    /**
     * Envoie un email avec PHPMailer.
     *
     * @param string $destinataire L'adresse email du destinataire.
     * @param string $sujet Le sujet de l'email.
     * @param string $corpsMessage Le corps du message de l'email (HTML autorisé).
     * @return bool Retourne true si l'email a été envoyé avec succès, sinon false.
     */
    public static function envoyerEmail($destinataire, $sujet, $corpsMessage)
    {
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);

        try {
            // Paramètres SMTP
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'ssl0.ovh.net'); // Récupération depuis .env
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME', 'support@wisikard.fr');
            $mail->Password   = env('MAIL_PASSWORD', 'SupportWisikard55');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'ssl'); // ssl ou tls
            $mail->Port       = env('MAIL_PORT', 465);


            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            $mail->setFrom(env('MAIL_FROM_ADDRESS', 'support@wisikard.fr'), env('MAIL_FROM_NAME', 'WISIKARD'));
            $mail->addAddress($destinataire);

            $mail->isHTML(true);
            $mail->Subject = "=?UTF-8?B?" . base64_encode($sujet) . "?="; // Sujet encodé en UTF-8
            $mail->Body    = $corpsMessage;

            $mail->send();
            return true;
        } catch (Exception $e) {
            Log::error('Erreur envoi email : ' . $mail->ErrorInfo);
            return false;
        }
    }
}
