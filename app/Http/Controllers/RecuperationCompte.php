<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Recuperation;
use App\Models\Compte;

class RecuperationCompte extends Controller
{
    /**
     * Affiche le formulaire de changement de mot de passe si le code de récupération est valide.
     *
     * @return \Illuminate\View\View Retourne la vue formulaireChangementMotDePasse si le code est valide, sinon retourne la vue pageErreur avec un message d'erreur.
     */
    public function afficherFormulaireChangementMotDePasse()
    {
        $ok = false; // Boolean indiquant si le code passé en GET dans l'URL est vérifié ou non
        $messageErreur = ""; // Chaîne contenant le message d'erreur à afficher

        if (isset($_GET["code"]) && Recuperation::estValide($_GET["code"])) {
            $ok = true;
        } else {
            $messageErreur = "Le lien de réinitialisation est invalide ou a expiré.";
        }

        if ($ok === true) {
            return view('Formulaire.formulaireChangementMotDePasse', ["codeRecuperation" => $_GET["code"]]);
        } else {
            return view('pageErreur', ["messageErreur" => $messageErreur]);
        }
    }

    /**
     * Affiche le formulaire de récupération de compte.
     *
     * @return \Illuminate\View\View Retourne la vue formulaireRecuperation.
     */
    public function afficherFormulaireRecuperation()
    {
        return view('Formulaire.formulaireRecuperation', []);
    }

    /**
     * Gère la soumission du formulaire de récupération de compte.
     *
     * @return \Illuminate\View\View Retourne la vue formulaireRecuperation avec des messages d'erreur ou la vue confirmation avec un message de succès.
     */
    public function boutonRecuperer()
    {
        if (isset($_POST["boutonRecuperer"])) {
            $messagesErreur = array();

            $email = $_POST["email"];
            $utilisateur = Compte::where("email", $email)->first();

            if (!$utilisateur) {
                $messagesErreur[] = "Aucun compte trouvé pour cette adresse email.";
                return view('Formulaire.formulaireRecuperation', ["messagesErreur" => $messagesErreur]);
            } else {
                $codeRecuperation = Recuperation::creerCodeRecuperation($utilisateur);
                $lien = url('/reinitialisation?code=' . $codeRecuperation);

                // Envoyer l'email
                $message = "Bonjour,<br><br>Vous avez demandé à réinitialiser votre mot de passe. Pour ce faire, veuillez cliquer sur le lien suivant : <a href='" . $lien . "'>" . $lien . "</a>.<br><br>Cordialement,<br>L'équipe Auth-App";
                if (Email::envoyerEmail($email, "Réinitialisation de mot de passe", $message)) {
                    Logs::ecrireLog($email, "Un email de réinitialisation de mot de passe a été envoyé à l'adresse email " . $email . ".");
                    return view('Formulaire.confirmation', ["messageConfirmation" => "Un lien de réinitialisation a été envoyé à votre adresse email."]);
                } else {
                    $messagesErreur[] = "Échec de l'envoi de l'email de réinitialisation.";
                    return view('Formulaire.formulaireRecuperation', ["messagesErreur" => $messagesErreur]);
                }
            }
        }
    }

    /**
     * Gère la soumission du formulaire de changement de mot de passe.
     *
     * @return \Illuminate\View\View Retourne la vue formulaireChangementMotDePasse avec des messages d'erreur ou la vue confirmation avec un message de succès.
     */
    public function boutonChangerMotDePasse()
    {
        if (isset($_POST["boutonChangerMotDePasse"])) {
            $validationFormulaire = true; // Boolean indiquant si les données du formulaire sont valides
            $messagesErreur = array(); // Tableau contenant les messages d'erreur à afficher

            if (Recuperation::estValide($_POST["boutonChangerMotDePasse"]) === false) {
                $messagesErreur[] = "Le lien de réinitialisation a expiré.";
                $validationFormulaire = false;
            }

            // Valider le nouveau mot de passe
            $nouveauMotDePasse = $_POST["motDePasse1"];
            $confirmationMotDePasse = $_POST["motDePasse2"];
            if (strlen($nouveauMotDePasse) < 13) {
                $messagesErreur[] = "Le mot de passe doit contenir au moins 13 caractères.";
                $validationFormulaire = false;
            }
            if ($nouveauMotDePasse !== $confirmationMotDePasse) {
                $messagesErreur[] = "Les mots de passe ne correspondent pas.";
                $validationFormulaire = false;
            }

            if ($validationFormulaire === false) {
                return view('Formulaire.formulaireChangementMotDePasse', ["messagesErreur" => $messagesErreur, "codeRecuperation" => $_POST["boutonChangerMotDePasse"]]);
            } else {
                $utilisateurConcerne = Compte::find(Recuperation::where("codeRecuperation", $_POST["boutonChangerMotDePasse"])->first()->idCompte);

                // Changer le mot de passe de l'utilisateur
                $utilisateurConcerne->password = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
                $utilisateurConcerne->save();

                // Enregistrer le changement de mot de passe dans les logs
                Logs::ecrireLog($utilisateurConcerne->email, "Le mot de passe du compte associé à l'adresse email " . $utilisateurConcerne->email . " a été modifié.");
                // Supprimer le code de récupération
                Recuperation::where("codeRecuperation", $_POST["boutonChangerMotDePasse"])->delete();

                return view('confirmation', ["messageConfirmation" => "Mot de passe modifié avec succès !"]);
            }
        }
    }
}
