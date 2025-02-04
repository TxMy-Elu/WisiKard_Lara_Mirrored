<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Recuperation;
use App\Models\Compte;

class RecuperationCompte extends Controller
{
    public function afficherFormulaireChangementMotDePasse()
        {
            $ok = false; // Boolean indicating if the code passed in GET in the URL is verified or not
            $messageErreur = ""; // String containing the error message to display

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

        public function afficherFormulaireRecuperation()
        {
            return view('Formulaire.formulaireRecuperation', []);
        }

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

                    // Send the email
                    $message = "Bonjour,<br><br>Vous avez demandé à réinitialiser votre mot de passe. Pour ce faire, veuillez cliquer sur le lien suivant : <a href='" . $lien . "'>" . $lien . "</a>.<br><br>Cordialement,<br>L'équipe Auth-App";
                    if (Email::envoyerEmail($email, "Réinitialisation de mot de passe", $message)) {
                        Logs::ecrireLog($email, "Un email de réinitialisation de mot de passe a été envoyé à l'adresse email " . $email . ".");
                        return view('confirmation', ["messageConfirmation" => "Un lien de réinitialisation a été envoyé à votre adresse email."]);
                    } else {
                        $messagesErreur[] = "Échec de l'envoi de l'email de réinitialisation.";
                        return view('Formulaire.formulaireRecuperation', ["messagesErreur" => $messagesErreur]);
                    }
                }
            }
        }

        public function boutonChangerMotDePasse()
        {
            if (isset($_POST["boutonChangerMotDePasse"])) {
                $validationFormulaire = true; // Boolean indicating if the form data is valid
                $messagesErreur = array(); // Array containing error messages to display

                if (Recuperation::estValide($_POST["boutonChangerMotDePasse"]) === false) {
                    $messagesErreur[] = "Le lien de réinitialisation a expiré.";
                    $validationFormulaire = false;
                }

                // Validate the new password
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

                    // Change the user's password
                    $utilisateurConcerne->password = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
                    $utilisateurConcerne->save();

                    // Logs the password change
                    Logs::ecrireLog($utilisateurConcerne->email, "Le mot de passe du compte associé à l'adresse email " . $utilisateurConcerne->email . " a été modifié.");
                    // Delete the recovery code
                    Recuperation::where("codeRecuperation", $_POST["boutonChangerMotDePasse"])->delete();

                    return view('confirmation', ["messageConfirmation" => "Mot de passe modifié avec succès !"]);
                }
            }
        }
}
