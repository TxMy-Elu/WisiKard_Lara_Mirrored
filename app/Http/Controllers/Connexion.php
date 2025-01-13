<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use App\Models\Reactivation;
use Firebase\JWT\JWT;

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
            $tentativesRestantes = 10 - $utilisateur->tentativesCo; // Retrieve failed attempts from the database

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
                    $utilisateur->tentativesCo = 0; // Reset failed attempts on successful login
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
                "exp" => time() + 3600 // 1 hour
            ];
            $jwt = JWT::encode($payload, $cle, 'HS256'); // Pass the algorithm as the third argument
            setcookie("auth", $jwt, time() + 3600, "/", "", false, true);
            Logs::ecrireLog($utilisateur->email, "Connexion réussie");

            // si admin redirection vers la page admin sinon vers la page dashboardClient
            if ($utilisateur->role === 'admin') {
                return redirect()->route('dashboardAdmin')->send();
            } else {
                return redirect()->route('dashboardClient')->send();
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

        return redirect()->to('connexion')->send();
    }

    public function validationFormulaire()
    {
        if (isset($_POST["boutonConnexion"])) {
            return $this->boutonConnexion();
        } else {
            return redirect()->to('connexion')->send();
        }
    }
}
