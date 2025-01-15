<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Rediriger;
use App\Models\Vue;
use Illuminate\Http\Request;
use App\Models\Carte;
use App\Models\Employer;
use App\Models\Compte;
use App\Models\Logs;
use App\Models\Social;
// Pour uploadFile
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

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

        // Récupérer le message
        $message = Message::where('afficher', true)->orderBy('id', 'desc')->first();
        $messageContent = $message ? $message->message : 'Aucun message disponible';

        return view('client.dashboardClient', [
            'compte' => $compte,
            'cartes' => $cartes,
            'employes' => $employes,
            'carte' => $carte, // Passez les informations de la carte à la vue
            'messageContent' => $messageContent
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

        // Vérif si des résultats sont trouvés
        if ($employes->isEmpty() && !empty($search)) {
            return view('client.dashboardClientEmploye', [
                'employes' => $employes,
                'search' => $search,
                'error' => 'Aucun résultat trouvé pour votre recherche.'
            ]);
        }

        return view('client.dashboardClientEmploye', [
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

        return redirect()->route('dashboardClientEmploye', ['idCarte' => $idCarte])->with('success', 'L\'employé a été supprimé avec succès.');
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

    public function statistique(Request $request)
    {
        $session = session('connexion');

        // Récupérer l'année et la semaine à partir de la requête
        $year = $request->query('year', date('Y'));
        $selectedWeek = $request->input('week', date('W')); // Utiliser la semaine actuelle par défaut

        // Récupérer l'idCarte associé au compte connecté
        $idCarte = Carte::where('idCompte', $session)->first()->idCarte;

        // Données annuelles
        $yearlyViews = Vue::selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->whereYear('date', $year)
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $session)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $yearlyData = [
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'datasets' => [
                [
                    'label' => 'Nombre de vues par mois',
                    'backgroundColor' => 'rgba(153, 27, 27, 0.2)',
                    'borderColor' => 'rgba(153, 27, 27, 1)',
                    'borderWidth' => 1,
                    'data' => array_values(array_replace(array_fill(1, 12, 0), $yearlyViews)),
                ],
            ],
        ];

        // Données annuelles par employé
        $employerViews = Vue::selectRaw('employer.nom as nom, COUNT(*) as count')
            ->join('employer', 'vue.idEmp', '=', 'employer.idEmp')
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->whereYear('date', $year)
            ->where('carte.idCarte', $idCarte)
            ->groupBy('nom')
            ->pluck('count', 'nom')
            ->toArray();

        // Génération de couleurs aléatoires pour les graphiques
        $colors = [];
        foreach ($employerViews as $key => $value) {
            do {
                $r = mt_rand(0, 255);
                $g = mt_rand(0, 255);
                $b = mt_rand(0, 255);
            } while (($r > 200 && $g < 100 && $b > 200) || ($r < 100 && $g > 200 && $b < 100)); // Exclude pink and green
            $colors[] = sprintf('rgba(%d, %d, %d, 0.755)', $r, $g, $b);
        }

        $employerData = [
            'labels' => array_keys($employerViews),
            'datasets' => [
                [
                    'label' => 'Nombre de vues par employé',
                    'backgroundColor' => $colors,
                    'borderColor' => 'rgba(0, 0, 0, 0.1)',
                    'borderWidth' => 1,
                    'data' => array_values($employerViews),
                ],
            ],
        ];

        // Nombre total de vues en fonction de l'année et de l'idCarte
        $totalViewsCard = Vue::whereYear('date', $year)
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $session)
            ->count();

        // Nombre de vues par semaine
        $weeklyViewsQuery = Vue::selectRaw('WEEK(date, 1) as week, COUNT(*) as count')
            ->whereYear('date', $year)
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $session)
            ->groupBy('week');

        $weeklyViews = $weeklyViewsQuery->pluck('count', 'week')->toArray();

        // Années disponibles pour la sélection
        $years = range(date('Y'), date('Y') - 10);
        $selectedYear = $year;

        return view('client.dashboardClientStatistique', compact('yearlyData', 'years', 'selectedYear', 'totalViewsCard', 'weeklyViews', 'selectedWeek', 'employerData'));
    }

    public function afficherFormulaireModifEmpl($id)
    {
        $employe = Employer::findOrFail($id);
        return view('formulaire.formulaireModifEmploye', compact('employe'));
    }

    public function modifierEmploye(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tel' => 'required|string|max:20',
            'fonction' => 'required|string|max:255',
        ]);

        try {
            $employe = Employer::findOrFail($id);
            $employe->nom = $request->nom;
            $employe->prenom = $request->prenom;
            $employe->mail = $request->email;
            $employe->telephone = $request->tel;
            $employe->fonction = $request->fonction;
            $employe->save();

            // Récupérer l'email du compte pour les logs
            $compte = Compte::find($employe->idCarte);
            if ($compte) {
                $emailUtilisateur = $compte->email;
                // Écrire dans les logs
                Logs::ecrireLog($emailUtilisateur, "Modification Employe");
            }

            return redirect()->route('dashboardClientEmploye')->with('success', 'L\'employé a été modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la modification de l\'employé.');
        }
    }

    public function afficherDashboardClientPDF()
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        return view('client.dashboardClientPDF', compact('carte'));
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $file = $request->file('file');
        $fileType = $file->getClientOriginalExtension();
        $filePath = '';

        switch ($fileType) {
            case 'pdf':
                $filePath = public_path("entreprises/{$entrepriseName}/pdf");
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
                $filePath = public_path("entreprises/{$entrepriseName}/images");
                break;
            default:
                return redirect()->back()->with('error', 'Type de fichier non supporté.');
        }

        if (!File::exists($filePath)) {
            File::makeDirectory($filePath, 0755, true);
        }

        $fileName = time() . '.' . $fileType;
        $file->move($filePath, $fileName);

        return redirect()->back()->with('success', 'Fichier téléchargé avec succès.');
    }
}

