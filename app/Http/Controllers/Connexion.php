<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use App\Models\Reactivation;
use Firebase\JWT\JWT;


class Connexion extends Controller
{
    /**
     * Affiche le formulaire de connexion pour les utilisateurs.
     *
     * @return \Illuminate\View\View Retourne la vue contenant le formulaire de connexion.
     *
     * Cette méthode rend la vue dédiée au formulaire de connexion, permettant aux utilisateurs de saisir
     * leurs informations pour se connecter.
     */
    public function afficherFormulaireConnexion()
    {
        // Retourne la vue du formulaire de connexion
        return view('Formulaire.formulaireConnexion', []);
    }

    /**
     * Valide le formulaire de connexion soumis par l'utilisateur.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * Retourne une redirection ou une vue en fonction des actions effectuées.
     *
     * Cette méthode vérifie si le bouton "boutonConnexion" a été soumis. Si c'est le cas, elle
     * exécute la méthode associée pour traiter la connexion. Sinon, elle redirige l'utilisateur
     * vers le formulaire de connexion.
     */
    public function validationFormulaire()
    {
        // Vérification de la soumission du bouton de connexion
        if (isset($_POST["boutonConnexion"])) {
            // Traitement avec la méthode associée
            return $this->boutonConnexion();
        } else {
            // Redirection vers le formulaire de connexion
            return redirect()->to('formulaireConnexion')->send();
        }
    }

    /**
     * Gère la logique de connexion des utilisateurs.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * Retourne une redirection vers le tableau de bord ou une vue avec les messages d'erreur.
     *
     * Cette méthode traite les informations du formulaire de connexion. Elle valide l'email et le mot de passe fournis.
     * En cas d'échecs répétés (10 tentatives), le compte est désactivé, et un email de réactivation est envoyé.
     * En cas de succès, un jeton JWT est généré pour l'authentification et l'utilisateur est redirigé vers son tableau de bord approprié.
     */
    public function boutonConnexion()
    {
        $validationFormulaire = false;
        $messagesErreur = [];
        $tentativesRestantes = 5;

        // Vérification de l'existence de l'email dans la base
        if (Compte::where("email", $_POST["email"])->count() === 0) {
            $messagesErreur[] = "Adresse email inconnue";
            $validationFormulaire = false;
        } else {
            // Récupération de l'utilisateur avec cet email
            $utilisateur = Compte::where("email", $_POST["email"])->first();
            $tentativesRestantes = 10 - $utilisateur->tentativesCo;

            // Vérification si le compte est désactivé
            if ($utilisateur->estDesactiver === 1) {
                $messagesErreur[] = "Votre compte a été désactivé";
                $validationFormulaire = false;
            } else {
                // Vérification du mot de passe
                if (password_verify($_POST["motdepasse"], $utilisateur->password) === false) {
                    $messagesErreur[] = "Mot de passe incorrect";
                    $utilisateur->tentativesCo += 1;
                    $tentativesRestantes = 10 - $utilisateur->tentativesCo;

                    // Désactivation du compte après 10 échecs
                    if ($utilisateur->tentativesCo >= 10) {
                        $utilisateur->desactiverCompte();
                        $messagesErreur[] = "Votre compte a été désactivé après 10 tentatives échouées";

                        // Création du code de réactivation et envoi de l'email
                        $codeReactivation = Reactivation::creerCodeReactivation($utilisateur);
                        $message = "Bonjour,<br><br>";
                        $message .= "Votre compte a été désactivé suite à 10 tentatives de connexion échouées.<br>";
                        $message .= "Pour réactiver votre compte, veuillez cliquer sur <a href='https://app.wisikard.fr/reactivation?code=" . $codeReactivation . "'>ce lien</a>.<br><br>";
                        $message .= "Cordialement,<br>L'équipe WisiKard";

                        // Envoi de l'email de réactivation
                        if (Email::envoyerEmail($utilisateur->email, "Réactivation de votre compte", $message)) {
                            Logs::ecrireLog($utilisateur->email, "Désactivation");
                        } else {
                            $messagesErreur[] = "Échec de l'envoi de l'email de réactivation.";
                        }
                    }
                    $utilisateur->save();
                    $validationFormulaire = false;
                } else {
                    // Connexion réussie
                    $validationFormulaire = true;
                    $utilisateur->tentativesCo = 0;
                    $utilisateur->save();
                    session()->put('connexion', $utilisateur->idCompte);
                    Logs::ecrireLog($utilisateur->email, "Connexion");
                }
            }
        }

        // Traitement en cas de validation du formulaire
        if ($validationFormulaire === true) {
            $cle = "T3mUjGjhC6WuxyNGR2rkUt2uQgrlFUHx";
            $payload = [
                "iss" => "https://app.wisikard.fr",
                "sub" => $utilisateur->idCompte,
                "iat" => time(),
                "exp" => time() + 3600
            ];
            $jwt = JWT::encode($payload, $cle, 'HS256');

            // Définition du cookie avec le JWT
            setcookie("auth", $jwt, time() + 3600, "/", "", false, true);
            Logs::ecrireLog($utilisateur->email, "Connexion réussie");

            // Redirection selon le rôle de l'utilisateur
            if ($utilisateur->role === 'admin') {
                return redirect()->route('dashboardAdmin');
            } elseif ($utilisateur->role === 'starter' || $utilisateur->role === 'advanced') {
                return redirect()->route('dashboardClient');
            } else {
                $messagesErreur[] = "Votre rôle est non autorisé.";
                return view('Formulaire.formulaireConnexion', ["messagesErreur" => $messagesErreur]);
            }
        } else {
            // Enregistrement des connexions échouées
            if (isset($utilisateur)) {
                Logs::ecrireLog($utilisateur->email, "Connexion échouée");
            }
            return view('Formulaire.formulaireConnexion', ["messagesErreur" => $messagesErreur, "tentativesRestantes" => $tentativesRestantes]);
        }
    }

    /**
     * Déconnecte l'utilisateur de la session en cours.
     *
     * @return \Illuminate\Http\RedirectResponse Redirige l'utilisateur vers la page de connexion après la déconnexion.
     *
     * Cette méthode supprime les informations de connexion de la session et, si un cookie d'authentification est présent,
     * le réinitialise. Elle effectue ensuite une redirection vers la page de connexion.
     */
    public function deconnexion()
    {
        // Suppression de la session de connexion
        if (session()->has('connexion')) {
            session()->forget('connexion');
        }

        // Réinitialisation du cookie d'authentification s'il existe
        if (isset($_COOKIE["auth"])) {
            setcookie("auth", "", time() - 3600);
        }

        // Redirection vers la page de connexion
        return redirect()->to('connexion')->send();
    }
}
