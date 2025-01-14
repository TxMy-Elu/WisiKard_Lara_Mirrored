<?php
namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use App\Models\Employer;
use App\Models\Carte;
use Illuminate\Support\Facades\DB; // pour role
use Illuminate\Http\Request;

class Employe extends Controller
{
    public function afficherFormulaireInscEmpl(Request $request)
    {
        // Récupérer l'ID de l'utilisateur connecté
        $idCompte = session('connexion');

        // Récupérer l'idCarte associé au compte connecté
        $idCarte = Carte::where('idCompte', $idCompte)->first()->idCarte;

        return view('formulaire.formulaireEmploye', ["idCarte" => $idCarte]);
    }

    public function boutonInscriptionEmploye(Request $request)
    {
        if (isset($_POST["boutonInscriptionEmploye"])) {
            $validationFormulaire = true; // Booléen qui indique si les données du Formulaire sont valides
            $messagesErreur = array(); // Tableau contenant les messages d'erreur à afficher

            if ($validationFormulaire === false) {
                return view('formulaire.formulaireEmploye', ["messagesErreur" => $messagesErreur]);
            } else {
                // Créez un nouvel employé
                $employe = new Employer();
                $employe->nom = $request->input('nom');
                $employe->prenom = $request->input('prenom');
                $employe->fonction = $request->input('fonction');
                $employe->mail = $request->input('email');
                $employe->telephone = $request->input('telephone');

                $session = session('connexion');
                $employe->idCarte = Carte::where('idCompte', $session)->first()->idCarte;

                $employe->save();

                $idCompte = session('connexion'); // ou $request->input('idCompte') si vous passez l'ID du compte via le formulaire
                // Récupérer l'ID du compte à partir de la session ou des données du Formulaire
                $idCompte = session('connexion'); // ou $_POST['idCompte'] si vous passez l'ID du compte via le Formulaire

                // Récupérer l'email du compte pour les logs
                $compte = Compte::find($idCompte);
                if ($compte) {
                    $emailUtilisateur = $compte->email;
                    // Écrire dans les logs
                    Logs::ecrireLog($emailUtilisateur, "Inscription Employe");
                }

                return view('formulaire.formulaireEmploye', ["messageSucces" => "Employé inscrit !"]);
            }
        }
    }
}
