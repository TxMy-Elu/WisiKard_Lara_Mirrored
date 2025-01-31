<?php
// app/Http/Controllers/Connexion.php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use App\Models\Reactivation;
use Firebase\JWT\JWT;

class Connexion extends Controller
{
    public function afficherFormulaireConnexion()
    {
        return view('Formulaire.formulaireConnexion', []);
    }

    public function validationFormulaire()
    {
        if (isset($_POST["boutonConnexion"])) {
            return $this->boutonConnexion();
        } else {
            return redirect()->to('connexion')->send();
        }
    }

    public function boutonConnexion()
    {
        $validationFormulaire = false;
        $messagesErreur = array();
        $tentativesRestantes = 5;

        if (Compte::where("email", $_POST["email"])->count() === 0) {
            $messagesErreur[] = "Adresse email inconnue";
            $validationFormulaire = false;
        } else {
            $utilisateur = Compte::where("email", $_POST["email"])->first();
            $tentativesRestantes = 10 - $utilisateur->tentativesCo;

            if ($utilisateur->estDesactiver === 1) {
                $messagesErreur[] = "Votre compte a été désactivé";
                $validationFormulaire = false;
            } else {
                if (password_verify($_POST["motdepasse"], $utilisateur->password) === false) {
                    $messagesErreur[] = "Mot de passe incorrect";
                    $utilisateur->tentativesCo += 1;
                    $tentativesRestantes = 10 - $utilisateur->tentativesCo;
                    if ($utilisateur->tentativesCo >= 10) {
                        $utilisateur->desactiverCompte();
                        $messagesErreur[] = "Votre compte a été désactivé après 10 tentatives échouées";

                        $codeReactivation = Reactivation::creerCodeReactivation($utilisateur);

                        $message = "Bonjour,<br><br>";
                        $message .= "Votre compte a été désactivé suite à 10 tentatives de connexion échouées.<br>";
                        $message .= "Pour réactiver votre compte, veuillez cliquer sur <a href='http://172.17.0.12:9000/reactivation?code=" . $codeReactivation . "'>ce lien</a>.<br><br>";
                        $message .= "Cordialement,<br>L'équipe de développement";
                        if (Email::envoyerEmail($utilisateur->email, "Réactivation de votre compte", $message)) {
                            Logs::ecrireLog($utilisateur->email, "Désactivation");
                        } else {
                            $messagesErreur[] = "Échec de l'envoi de l'email de réactivation.";
                        }
                    }
                    $utilisateur->save();
                    $validationFormulaire = false;
                } else {
                    $validationFormulaire = true;
                    $utilisateur->tentativesCo = 0;
                    $utilisateur->save();
                    session()->put('connexion', $utilisateur->idCompte);
                    Logs::ecrireLog($utilisateur->email, "Connexion");
                }
            }
        }

        if ($validationFormulaire === true) {
            $cle = "T3mUjGjhC6WuxyNGR2rkUt2uQgrlFUHx";
            $payload = [
                "iss" => "http://172.0.0.1:9000",
                "sub" => $utilisateur->idCompte,
                "iat" => time(),
                "exp" => time() + 3600
            ];
            $jwt = JWT::encode($payload, $cle, 'HS256');
            setcookie("auth", $jwt, time() + 3600, "/", "", false, true);
            Logs::ecrireLog($utilisateur->email, "Connexion réussie");

            if ($utilisateur->role === 'admin') {
                return redirect()->route('Admin.dashboardAdmin');
            } elseif ($utilisateur->role === 'starter' || $utilisateur->role === 'advanced') {
                return redirect()->route('Client.dashboardClient');
            } else {
                $messagesErreur[] = "Votre rôle est non autorisé.";
                return view('Formulaire.formulaireConnexion', ["messagesErreur" => $messagesErreur]);
            }
        } else {
            if (isset($utilisateur)) {
                Logs::ecrireLog($utilisateur->email, "Connexion échouée");
            }
            return view('formulaireConnexion', ["messagesErreur" => $messagesErreur, "tentativesRestantes" => $tentativesRestantes]);
        }
    }




    public function deconnexion()
    {
        if (session()->has('connexion')) {
            session()->forget('connexion');
        }
        if (isset($_COOKIE["auth"])) {
            setcookie("auth", "", time() - 3600);
        }

        return redirect()->to('formulaireConnexion')->send();
    }
}