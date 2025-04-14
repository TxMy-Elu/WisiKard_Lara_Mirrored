<?php
namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use App\Models\Employer;
use App\Models\Carte;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Employe extends Controller
{
    public function afficherFormulaireInscEmpl()
    {
        return view('Formulaire.formulaireInscriptionEmploye');
    }

    public function boutonInscriptionEmploye(Request $request)
    {
        $validationFormulaire = true; // Booléen qui indique si les données du Formulaire sont valides
        $messagesErreur = array(); // Tableau contenant les messages d'erreur à afficher

        if ($validationFormulaire === false) {
            return view('Formulaire.formulaireEmploye', ["messagesErreur" => $messagesErreur]);
        } else {

            // Récupérer l'id du compte connecté
            $idCompte = session('connexion');

            // Recueperer les informations de la carte
            $carte = Carte::where('idCompte', $idCompte)->first();

            // Récupérer l'id de la carte
            $idCarte = $carte->idCarte;


            $nom = $request->input('nom');
            $prenom = $request->input('prenom');
            $email = $request->input('email');
            $tel = $request->input('tel');
            $fonction = $request->input('fonction');

            $employer = new Employer();
            $employer->nom = $nom;
            $employer->prenom = $prenom;
            $employer->mail = $email;
            $employer->telephone = $tel;
            $employer->fonction = $fonction;
            $employer->idCarte = $idCarte;
            $employer->save();

            // Utiliser la fonction unifiée
            $employer->QrCodeEmploye($idCompte, $carte->nomEntreprise, $employer->idEmp);

            // Log the inscription
            $mailCompte = Compte::where('idCompte', $idCompte)->first();
            Logs::ecrireLog($mailCompte->email, "Inscription Employe");
            Log::info('Employé inscrit avec succès.');

            return redirect()->route('dashboardClientEmploye')->with('success', 'Employé inscrit avec succès !');
        }
    }

    public function edit($id)
    {
        $employe = Employer::findOrFail($id);
        return view('Formulaire.formulaireModifEmploye', compact('employe'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'tel' => 'nullable|string|max:20',
            'fonction' => 'nullable|string|max:255',
        ]);

        try {
            $employe = Employer::findOrFail($id);
            $employe->nom = $request->nom;
            $employe->prenom = $request->prenom;
            $employe->mail = $request->email;
            $employe->telephone = $request->tel;
            $employe->fonction = $request->fonction;
            $employe->save();

            // Log the modification
            $compte = Compte::find($employe->idCarte);
            if ($compte) {
                $emailUtilisateur = $compte->email;
                Logs::ecrireLog($emailUtilisateur, "Modification Employe");
                Log::info('Employé modifié avec succès.');
            }

            return redirect()->route('dashboardClientEmploye')->with('success', 'L\'employé a été modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la modification de l\'employé.');
        }
    }

}
