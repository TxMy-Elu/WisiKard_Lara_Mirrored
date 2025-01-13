<?php
namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use App\Models\Employer; // Ajoutez cette ligne pour importer le modèle Employer
use Illuminate\Support\Facades\DB; // pour role

class Employe extends Controller
{
    public function afficherFormulaireInscEmpl(Request $request)
    {
        return view('formulaireEmploye', []);
    }

    public function boutonInscriptionEmploye()
    {
        if (isset($_POST["boutonInscriptionEmploye"])) {
            $validationFormulaire = true; // Booléen qui indique si les données du formulaire sont valides
            $messagesErreur = array(); // Tableau contenant les messages d'erreur à afficher

            if ($validationFormulaire === false) {
                return view('formulaireEmploye', ["messagesErreur" => $messagesErreur]);
            } else {
                $motDePasseHashe = password_hash($_POST["motDePasse1"], PASSWORD_BCRYPT);

                // Créez un nouvel employé
                $employe = new Employer();
                $employe->nom = $_POST["nom"];
                $employe->prenom = $_POST["prenom"];
                $employe->fonction = $_POST["fonction"];
                $employe->save();

                // Récupérer l'ID du compte à partir de la session ou des données du formulaire
                $idCompte = session('connexion'); // ou $_POST['idCompte'] si vous passez l'ID du compte via le formulaire

                Logs::ecrireLog($idCompte, "Inscription Employe"); // Utilisez l'ID du compte pour les logs

                return view('formulaireEmploye', ["messageSucces" => "Employé inscrit !"]);
            }
        }
    }
}
