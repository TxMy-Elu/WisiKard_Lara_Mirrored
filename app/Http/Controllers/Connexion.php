<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Log;
use App\Models\Reactivation;
use PragmaRX\Google2FA\Google2FA;

/* A FAIRE (fiche 3, partie 2, question 1) : inclure ci-dessous le use PHP pour la libriairie gérant l'A2F */

// A FAIRE (fiche 3, partie 3, question 4) : inclure ci-dessous le use PHP pour la libriairie gérant le

class Connexion extends Controller
{
    public function afficherFormulaireConnexion()
    {
        return view('formulaireConnexion', []);
    }

    public function reactivationCompte()
    {
        $validation = false; // Booléen vrai/faux si les conditions de vérification sont remplies pour réactiver le compte
        $messageAAfficher = null; // Contient le message d'erreur ou de succès à afficher

        // Vérification du code dans l'URL ainsi que de l'expiration du lien + réactivation du compte
        if (isset($_GET["code"])) {
            $code = $_GET["code"];
            $reactivation = Reactivation::where("codeReactivation", $code)->first();
            if ($reactivation !== null) {
                if (Reactivation::estValide($code)) { // Pass the $code argument
                    $utilisateur = Compte::find($reactivation->idCompte);
                    $utilisateur->reactiverCompte();
                    $reactivation->delete();
                    $messageAAfficher = "Votre compte a été réactivé avec succès";
                    $validation = true;
                } else {
                    $messageAAfficher = "Le lien de réactivation a expiré";
                }
            } else {
                $messageAAfficher = "Le lien de réactivation est invalide";
            }
        } else {
            $messageAAfficher = "Le lien de réactivation est invalide";
        }
        echo $messageAAfficher;
        if ($validation === false) {
            return view("pageErreur", ["messageErreur" => $messageAAfficher]);
        } else {
            return view('confirmation', ["messageConfirmation" => $messageAAfficher]);
        }
    }

    public function boutonConnexion()
    {
        $validationFormulaire = false; // Booléen qui indique si les données du formulaire sont valides
        $messagesErreur = array(); // Tableau contenant les messages d'erreur à afficher
        $tentativesRestantes = 5; // Nombre de tentatives restantes

        if (Compte::where("email", $_POST["email"])->count() === 0) {
            $messagesErreur[] = "Adresse email inconnue";
            $validationFormulaire = false;
        } else {
            $utilisateur = Compte::where("email", $_POST["email"])->first();
            $tentativesRestantes = 5 - $utilisateur->tentativesCo; // Retrieve failed attempts from the database

            if ($utilisateur->estDesactive === 1) {
                $messagesErreur[] = "Votre compte a été désactivé";
                $validationFormulaire = false;
            } else {
                if (password_verify($_POST["motdepasse"], $utilisateur->password) === false) {
                    $messagesErreur[] = "Mot de passe incorrect";
                    $utilisateur->tentativesCo += 1;
                    $tentativesRestantes = 10 - $utilisateur->tentativesCo;
                    if ($utilisateur->tentativesCo >= 10) {
                        $utilisateur->desactiverCompte();
                        $messagesErreur[] = "Votre compte a été désactivé après 5 tentatives échouées";

                        $codeReactivation = Reactivation::creerCodeReactivation($utilisateur);

                        /*
                        $message = "Bonjour " . $utilisateur->prenomUtilisateur . " " . $utilisateur->nomUtilisateur . ",<br><br>";
                        $message .= "Votre compte a été désactivé suite à 5 tentatives de connexion échouées.<br>";
                        $message .= "Pour réactiver votre compte, veuillez cliquer sur <a href='http://172.17.0.12:9000/reactivation?code=" . $codeReactivation . "'>ce lien</a>.<br><br>";
                        $message .= "Cordialement,<br>L'équipe de développement";

                        Email::envoyerEmail($utilisateur->emailUtilisateur, "Réactivation de votre compte", $message);
                        */

                        Log::ecrireLog($utilisateur->email, "Désactivation");
                    }
                    $utilisateur->save();
                    $validationFormulaire = false;
                } else {
                    $validationFormulaire = true;
                    $utilisateur->tentativesCo = 0; // Reset failed attempts on successful login
                    $utilisateur->save();
                    session()->put('connexion', $utilisateur->idCompte);
                    Log::ecrireLog($utilisateur->email, "Connexion");
                }
            }
        }

        if ($validationFormulaire === false) {
            return view('formulaireConnexion', ["messagesErreur" => $messagesErreur, "tentativesRestantes" => $tentativesRestantes]);
        } else {
            return view('profil', []);
        }
    }

    public
    function deconnexion()
    {
        if (session()->has('connexion')) {
            session()->forget('connexion');
        }
        if (isset($_COOKIE["auth"])) {
            setcookie("auth", "", time() - 3600);
        }

        return redirect()->to('connexion')->send();
    }

    public
    function validationFormulaire()
    {
        if (isset($_POST["boutonVerificationCodeA2F"])) {
            return $this->boutonVerificationCodeA2F();
        } else {
            if (isset($_POST["boutonConnexion"])) {
                return $this->boutonConnexion();
            } else {
                return redirect()->to('connexion')->send();
            }
        }
    }
}