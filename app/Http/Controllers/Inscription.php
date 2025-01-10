<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use App\Models\Carte;

class Inscription extends Controller
{
    public function afficherFormulaireInscription()
    {
        return view('formulaireInscription', []);
    }

    // app/Http/Controllers/Inscription.php

    public function boutonInscription()
    {
        if (isset($_POST["boutonInscription"])) {
            $validationFormulaire = true; // Boolean indicating if the form data is valid
            $messagesErreur = array(); // Array containing error messages to display

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
                $motDePasseHashe = password_hash($_POST["motDePasse1"], PASSWORD_BCRYPT);

                // Account registration
                $idCompte = Compte::inscription($_POST["email"], $motDePasseHashe, $_POST["role"]);
                if ($idCompte) {
                    Logs::ecrireLog($_POST["email"], "Inscription");

                    // Insert a default card
                    Carte::create([
                        'nomEntreprise' => 'Nom par défaut',
                        'titre' => 'Titre par défaut',
                        'tel' => '0000000000',
                        'ville' => 'Ville par défaut',
                        'idCompte' => $idCompte,
                        'idTemplate' => 1 // Ensure this template exists
                    ]);

                    return redirect()->route('dashboardAdmin')->with('messageSucces', 'Inscription réussie, vous êtes maintenant connecté');
                } else {
                    $messagesErreur[] = "Erreur lors de l'inscription du compte.";
                    return view('formulaireInscription', ["messagesErreur" => $messagesErreur]);
                }
            }
        }
    }
}
