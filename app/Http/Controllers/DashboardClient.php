<?php
namespace App\Http\Controllers;

use App\Models\Rediriger;
use Illuminate\Http\Request;
use App\Models\Carte;
use App\Models\Employer;
use App\Models\Compte;
use App\Models\Logs;
use App\Models\Social;

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

        return view('client.dashboardClient', [
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
        return view('client.dashboardClientEmployer', ['employes' => $employes]);
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

        return redirect()->route('client.dashboardClientEmployer')->with('success', 'L\'employé a été supprimé avec succès.');
    }

    public function social()
    {
        $idCompte = session('connexion');
        $idCarte = Carte::where('idCompte', $idCompte)->first()->idCarte;

        // Récupérer tous les réseaux sociaux
        $allSocial = Social::all();

        // Récupérer les réseaux sociaux activés pour l'entreprise
        $activatedSocial = Rediriger::where('idCarte', $idCarte)
            ->join('social', 'rediriger.idSocial', '=', 'social.idSocial')
            ->select('social.idSocial', 'rediriger.activer', 'rediriger.lien')
            ->get();

        // Créer un tableau associatif pour les réseaux sociaux activés
        $activatedSocialArray = [];
        foreach ($activatedSocial as $social) {
            $activatedSocialArray[$social->idSocial] = ['activer' => $social->activer, 'lien' => $social->lien];
        }

        return view('client.dashboardClientSocial', [
            'allSocial' => $allSocial,
            'activatedSocial' => $activatedSocialArray,
            'idCarte' => $idCarte // Passez la variable $idCarte à la vue
        ]);
    }


    public function updateSocialLink(Request $request)
    {
        $request->validate([
            'idSocial' => 'required|integer',
            'idCarte' => 'required|integer',
            'lien' => 'nullable|url'
        ]);

        // Vérifiez si un enregistrement existe déjà
        $rediriger = Rediriger::where('idSocial', $request->idSocial)
            ->where('idCarte', $request->idCarte)
            ->first();

        if ($rediriger) {
            // Mettre à jour le lien existant
            $rediriger->lien = $request->lien;
            $rediriger->activer = $request->has('activer') ? 1 : 0; // Activer ou désactiver en fonction de la présence du champ
            $rediriger->save();
        } else {
            // Créer un nouvel enregistrement
            Rediriger::create([
                'idSocial' => $request->idSocial,
                'idCarte' => $request->idCarte,
                'lien' => $request->lien,
                'activer' => $request->has('activer') ? 1 : 0 // Activer par défaut si le champ est présent
            ]);
        }

        return redirect()->back()->with('success', 'Lien mis à jour avec succès.');
    }





}
