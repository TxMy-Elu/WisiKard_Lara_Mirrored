<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carte;
use App\Models\Employer;
use App\Models\Compte;
use App\Models\Logs;

class DashboardClient extends Controller
{
    public function afficherDashboardClient(Request $request)
    {
        // Récupérer l'ID de l'utilisateur connecté
        $idCompte = session('connexion');

        // Récupérer les informations du compte
        $compte = Compte::find($idCompte);

        // Récupérer les cartes associées au compte
        $cartes = Carte::where('idCompte', $idCompte)->get();

        // Récupérer les employés associés au compte
        $employes = Employer::join('carte', 'employer.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $idCompte)
            ->select('employer.*')
            ->get();

        // Récupérer l'idCarte associé au compte connecté
        $idCarte = $cartes->first()->idCarte;

        return view('dashboardClientEmployer', [
            'compte' => $compte,
            'cartes' => $cartes,
            'employes' => $employes,
            'idCarte' => $idCarte // Passez l'idCarte à la vue
        ]);
    }

    public function employer($idCarte)
    {
        // Récupérer les employés ayant le même idCarte
        $employes = Employer::where('idCarte', $idCarte)->get();

        return view('dashboardClientEmployer', [
            'employes' => $employes,
            'idCarte' => $idCarte // Passez la variable idCarte à la vue
        ]);
    }

    public function destroy($id)
    {
        $employer = Employer::findOrFail($id);
        $idCarte = $employer->idCarte; // Récupérer l'ID de la carte associée à l'employé
        $employer->delete();

        // Récupérer l'email du compte pour les logs
        $compte = Compte::find($idCarte);
        if ($compte) {
            $emailUtilisateur = $compte->email;
            // Écrire dans les logs
            Logs::ecrireLog($emailUtilisateur, "Suppression Employe");
        }

        return redirect()->route('dashboardClientEmployer', ['idCarte' => $idCarte])->with('success', 'L\'employé a été supprimé avec succès.');
    }
}

