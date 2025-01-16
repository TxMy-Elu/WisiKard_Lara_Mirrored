<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use Illuminate\Support\Facades\DB;// pour role

class Inscription extends Controller
{
    public function afficherFormulaireInscription()
    {
        $roles = DB::table('compte')->select('role')->distinct()->get();
        return view('formulaire.formulaireInscription', ['roles' => $roles]);
    }


    public function boutonInscription()
    {
        //recupere le role de la table compte
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
                return view('formulaire.formulaireInscription', ["messagesErreur" => $messagesErreur, 'roles' => $roles]);
            } else {
                $motDePasseHashe = password_hash($_POST["motDePasse1"], PASSWORD_BCRYPT);

                Compte::inscription($_POST["email"], $motDePasseHashe, $_POST["role"], $_POST["entreprise"]);
                Logs::ecrireLog($_POST["email"], "Inscription");

                return view('formulaire.formulaireInscription', ["messageSucces" => "Inscription réussie, vous pouvez maintenant vous connecter", 'roles' => $roles]);
            }
        }

        // If the form is not submitted, display the form with roles
        return view('formulaire.formulaireInscription', ['roles' => $roles]);
    }
}
