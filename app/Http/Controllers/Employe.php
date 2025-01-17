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
                // Appel de la méthode inscriptionEmploye du modèle Employer
                Employer::inscriptionEmploye(
                    $request->input('nom'),
                    $request->input('prenom'),
                    $request->input('fonction'),
                    $request->input('email'),
                    $request->input('telephone'),
                    Carte::where('idCompte', session('connexion'))->first()->idCarte
                );

                return redirect()->back()->with('success', 'Employé inscrit avec succès !');
            }
        }
    }
}
