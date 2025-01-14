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

        return view('dashboardClient', [
            'compte' => $compte,
            'cartes' => $cartes,
            'employes' => $employes
        ]);
    }

    public function employer()
    {
        $employes = Employer::query()
            ->join('carte', 'employer.idEmp', '=', 'carte.idCarte')
            ->join('compte', 'carte.idCarte', '=', 'compte.idCompte')
            ->select('employer.*', 'compte.email as compte_email')
            ->get();
        return view('dashboardClientEmployer', ['employes' => $employes]);
    }

    public function destroy($id)
    {
         $employer = Employer::findOrFail($id);
         $idCompte = $employer->idCarte; // Récup l'ID du compte associé à l'employé
         $employer->delete();

         // Récup l'email du compte pour les logs
         $compte = Compte::find($idCompte);
         if ($compte) {
             $emailUtilisateur = $compte->email;
             // Écrire dans les logs
             Logs::ecrireLog($emailUtilisateur, "Suppression Employe");
         }

        return redirect()->route('dashboardClientEmployer')->with('success', 'L\'employé a été supprimé avec succès.');
    }
}
