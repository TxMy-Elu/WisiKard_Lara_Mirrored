<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use Illuminate\Support\Facades\DB;

class Inscription extends Controller
{
    /**
     * Affiche le formulaire d'inscription avec les rôles disponibles.
     *
     * @return \Illuminate\View\View Retourne la vue formulaireInscription avec les rôles disponibles.
     */
    public function afficherFormulaireInscription()
    {
        $roles = DB::table('compte')->select('role')->distinct()->get();
        return view('Formulaire.formulaireInscription', ['roles' => $roles]);
    }

    /**
     * Gère la soumission du formulaire d'inscription.
     *
     * @return \Illuminate\View\View Retourne la vue formulaireInscription avec des messages d'erreur ou de succès.
     */
    public function boutonInscription()
    {
        // Récupère les rôles de la table compte
        $roles = DB::table('compte')->select('role')->distinct()->get();

        if (isset($_POST["boutonInscription"])) {
            $validationFormulaire = true;
            $messagesErreur = array();

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
                return view('Formulaire.formulaireInscription', ["messagesErreur" => $messagesErreur, 'roles' => $roles]);
            } else {
                $motDePasseHashe = password_hash($_POST["motDePasse1"], PASSWORD_BCRYPT);

                Compte::inscription($_POST["email"], $motDePasseHashe, $_POST["role"], $_POST["entreprise"]);
                Logs::ecrireLog($_POST["email"], "Inscription");

                return view('Formulaire.formulaireInscription', ["messageSucces" => "Inscription réussie, vous pouvez maintenant vous connecter", 'roles' => $roles]);
            }
        }

        // Si le formulaire n'est pas soumis, afficher le formulaire avec les rôles
        return view('Formulaire.formulaireInscription', ['roles' => $roles]);
    }
}
