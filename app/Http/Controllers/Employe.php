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
      public function afficherFormulaireInscEmpl($id)
      {
          // Assuming you have a method to get the employee data
          $employe = Employer::findOrFail($id); // Use the passed $id

          return view('formulaire.formulaireEmploye', compact('employe'));
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
