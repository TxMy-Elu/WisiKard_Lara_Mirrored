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

            // Vérifier le rôle de l'utilisateur
            if ($compte->role === 'employe') {
                return redirect()->route('dashboardClientEmploye');
            }

            // Récupérer les cartes associées au compte
            $cartes = Carte::where('idCompte', $idCompte)->get();

            // Récupérer les employés associés au compte
            $employes = Employer::join('carte', 'employer.idCarte', '=', 'carte.idCarte')
                ->where('carte.idCompte', $idCompte)
                ->select('employer.*')
                ->get();

            // Récupérer l'idCarte associé au compte connecté
            $idCarte = $cartes->first()->idCarte;

            // Récupérer les informations de la carte
            $carte = Carte::find($idCarte);

            return view('client.dashboardClient', [
                'compte' => $compte,
                'cartes' => $cartes,
                'employes' => $employes,
                'carte' => $carte // Passez les informations de la carte à la vue
            ]);
        }

     public function employer(Request $request)
        {
            $idCompte = session('connexion');
            $search = $request->input('search');

            // Récupérer les employés associés à la carte du compte connecté
            $employes = Employer::join('carte', 'employer.idCarte', '=', 'carte.idCarte')
                ->where('carte.idCompte', $idCompte)
                ->when($search, function ($query, $search) {
                    return $query->where('employer.nom', 'like', "%{$search}%")
                        ->orWhere('employer.prenom', 'like', "%{$search}%")
                        ->orWhere('employer.fonction', 'like', "%{$search}%");
                })
                ->select('employer.*')
                ->get();

            return view('client.dashboardClientEmployer', [
                'employes' => $employes,
                'search' => $search
            ]);
        }

    public function ajoutEmployer(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tel' => 'required|string|max:20',
            'idCarte' => 'required|integer'
        ]);

        // Créer un nouvel employé
        Employer::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'tel' => $request->tel,
            'idCarte' => $request->idCarte
        ]);

        // Récupérer l'email du compte pour les logs
        $compte = Compte::find($request->idCarte);
        if ($compte) {
            $emailUtilisateur = $compte->email;
            // Écrire dans les logs
            Logs::ecrireLog($emailUtilisateur, "Ajout Employe");
        }

        return redirect()->back()->with('success', 'L\'employé a été ajouté avec succès.');
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
