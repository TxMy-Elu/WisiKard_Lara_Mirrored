<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;

/* A FAIRE (fiche 2, partie 2, question 2) : inclure ci-dessous les use PHP pour les librairies gérant l'A2F */


class Inscription extends Controller
{
    public function afficherFormulaireInscription()
    {
        return view('formulaireInscription', []);
    }

    public function boutonInscription()
    {
        if (isset($_POST["boutonInscription"])) {
            $validationFormulaire = true; // Booléen qui indique si les données du formulaire sont valides
            $messagesErreur = array(); // Tableau contenant les messages d'erreur à afficher

            /* A FAIRE : vérification du formulaire d'inscription */

            // CORRIGÉ
            if (Compte::existeEmail($_POST["email"])) {
                $messagesErreur[] = "Cette adresse email a déjà été utilisée";
                $validationFormulaire = false;
            }
            if ($_POST["motDePasse1"] != $_POST["motDePasse2"]) {
                $messagesErreur[] = "Les deux mots de passe saisis ne sont pas identiques";
                $validationFormulaire = false;
            }
            if (preg_match("/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[!@#%^&*()\$_+÷%§€\-=\[\]{}|;':\",.\/<>?~`]).{12,}$/", $_POST["motDePasse1"]) === 0) {
                $messagesErreur[] = "Le mot de passe doit contenir au minimum 12 caractères comportant au moins une minuscule, une majuscule, un chiffre et un caractère spécial.";
                $validationFormulaire = false;
            }


            if ($validationFormulaire === false) {
                return view('formulaireInscription', ["messagesErreur" => $messagesErreur]);

            } else {

                /* A FAIRE (fiche 2, partie 1, question 7) : on inscrit l'utilisateur dans la base + écriture dans les logs */


                // CORRIGÉ
                $motDePasseHashe = password_hash($_POST["motDePasse1"], PASSWORD_BCRYPT);
                Compte::inscription($_POST["email"], $motDePasseHashe);
                Logs::ecrireLog($_POST["email"], "Inscription");

                return view('formulaireConnexion', ["messageSucces" => "Inscription réussie, vous pouvez maintenant vous connecter"]);
            }
        }
    }
}
