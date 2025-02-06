<?php

namespace App\Http\Controllers;

use App\Models\Carte;
use App\Models\Compte;
use App\Models\Custom_link;
use App\Models\Employer;
use App\Models\Horaires;
use App\Models\Rediriger;
use App\Models\Social;
use App\Models\Template;
use App\Models\Vue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Templates extends Controller
{
    public function afficherTemplates(Request $request)
    {
        // Vérifier si "CompteEmp" est présent dans la requête
        $CompteEmp = $request->query('CompteEmp');
        $idCarte = null;
        $idEmp = null;
        $employe = null;
        $today = date('Y-m-d');

        if ($CompteEmp) {
            // Si CompteEmp est présent, le split au niveau de la virgule
            [$idCompte, $idEmp] = explode('x', $CompteEmp);
            // Convertir en entier pour s'assurer qu'on travaille avec des ID valides
            $idCompte = (int)$idCompte;
            $idEmp = (int)$idEmp;

            // Récupérer les infos de la carte de visite
            $carte = Carte::where('idCompte', $idCompte)->first();

            //idCarte
            $idCarte = $carte->idCarte ?? null;

            // Récupérer les infos de l'employé en fonction de l'idCarte et idEmp
            $employe = Employer::where('idCarte', $carte->idCarte)->where('idEmp', $idEmp)->first();

            //idTemplate
            $idTemplate = $carte->idTemplate ?? null;

            $vue = new Vue();
            $vue->date = $today;
            $vue->idCarte = $idCarte;
            $vue->idEmp = $idEmp;
            $vue->save();

        } else {
            // Sinon, récupérer l'idCompte
            $idCompte = $request->query('idCompte');

            // Récupérer d'abord l'idTemplate
            $idTemplate = Carte::where('idCompte', $idCompte)->value('idTemplate');

            // Prend toutes les informations nécessaires depuis la base de données
            $carte = Carte::where('idCompte', $idCompte)->first();
            $idCarte = $carte->idCarte ?? null;

            $vue = new Vue();
            $vue->date = $today;
            $vue->idCarte = $idCarte;
            $vue->save();
        }

        // Si $idCarte est toujours null, on ne peut rien afficher
        if (!$idCarte) {
            return response()->json(['message' => 'idCarte non trouvé.'], 404);
        }

        // Récupération des données du compte, template, etc.
        $compte = isset($idCompte) ? Compte::find($idCompte) : null;
        $lien = Rediriger::where('idCarte', $idCarte)->get(); // Tous les liens associés à une carte
        $custom = Custom_link::where('idCarte', $idCarte)->where('activer', 1)->get(); // Liens personnalisés activés (custom_link)
        $vue = Vue::where('idCarte', $idCarte)->get(); // Toutes les vues d'une carte
        $template = isset($idTemplate) ? Template::find($idTemplate) : null;

        // Récupérer les réseaux sociaux
        $logoSocial = Social::all()->map(function ($item) {
            return [
                'id' => $item->idSocial,
                'logo' => $item->lienLogo,
                'nom' => $item->nom,
            ];
        });

        // Récupérer les liens activés pour une carte spécifique
        $social = Rediriger::where('idCarte', $idCarte)
            ->where('activer', 1)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->idSocial,
                    'lien' => $item->lien,
                    'activer' => $item->activer,
                ];
            });

        // Fusionner les collections de réseaux sociaux actifs avec leurs logos
        $mergedSocial = $social->map(function ($item) use ($logoSocial) {
            $socialItem = $logoSocial->firstWhere('id', $item['id']);
            return [
                'lien' => $item['lien'],
                'logo' => $socialItem ? $socialItem['logo'] : null,
                'nom' => $socialItem ? $socialItem['nom'] : null,
            ];
        });

        // Récupérer les horaires pour la carte spécifique
        $horaires = Horaires::where('idCarte', $idCarte)->get();

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");
        $youtubeUrls = [];
        if (File::exists($videosPath)) {
            $youtubeUrls = json_decode(File::get($videosPath), true);
        }


        // Définir les fonctions spécifiques
        $fonctions = [
            ['nom' => 'nopub'],
            ['nom' => 'embedyoutube', 'option' => $idCarte ? Carte::find($idCarte)->lienCommande : null]
        ];

        // Renvoyer la bonne vue selon le template
        switch ($idTemplate ?? null) {
            case 1:
                return view('Templates.base', compact('carte', 'compte', 'social', 'vue', 'template', 'logoSocial', 'custom', 'employe', 'fonctions', 'lien', 'mergedSocial', 'horaires', 'youtubeUrls'));
            case 2:
                return view('Templates.pomme', compact('carte', 'compte', 'social', 'vue', 'template', 'logoSocial', 'custom', 'employe', 'fonctions', 'lien', 'mergedSocial', 'horaires', 'youtubeUrls'));
            case 3:
                return view('Templates.classy', compact('carte', 'compte', 'social', 'vue', 'template', 'logoSocial', 'custom', 'employe', 'fonctions', 'lien', 'mergedSocial', 'horaires', 'youtubeUrls'));
            case 4:
                return view('Templates.oxygen', compact('carte', 'compte', 'social', 'vue', 'template', 'logoSocial', 'custom', 'employe', 'fonctions', 'lien', 'mergedSocial', 'horaires', 'youtubeUrls'));
            default:
                // Si aucun template trouvé, retourner un message JSON ou une vue vide.
                return response()->json([
                    'message' => 'Aucun template trouvé',
                    'data' => compact('idCarte', 'employe')
                ], 404);
        }
    }

    public function afficherIframe(Request $request)
    {
        // Récupérer l'idCompte depuis la session
        $idCompte = session('connexion');
        // Récupérer l'idTemplate depuis l'URL
        $idTemplate = $request->query('idTemplate');

        // Prend toutes les informations nécessaires depuis la base de données
        $carte = Carte::where('idCompte', $idCompte)->first();
        $idCarte = $carte->idCarte ?? null;
        $compte = Compte::find($idCompte);
        $lien = Rediriger::where('idCarte', $idCarte)->get(); // Tous les liens associés à une carte
        $custom = Custom_link::where('idCarte', $idCarte)->where('activer', 1)->get(); // Liens personnalisés activés (custom_link)
        $vue = Vue::where('idCarte', $idCarte)->get(); // Toutes les vues d'une carte

        // Récupérer les réseaux sociaux
        $logoSocial = Social::all()->map(function ($item) {
            return [
                'id' => $item->idSocial,
                'logo' => $item->lienLogo,
                'nom' => $item->nom,
            ];
        });

        $social = Rediriger::where('idCarte', $idCarte)
            ->where('activer', 1)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->idSocial,
                    'lien' => $item->lien,
                    'activer' => $item->activer,
                ];
            });

        // Fusionner les collections de réseaux sociaux actifs avec leurs logos
        $mergedSocial = $social->map(function ($item) use ($logoSocial) {
            $socialItem = $logoSocial->firstWhere('id', $item['id']);
            return [
                'lien' => $item['lien'],
                'logo' => $socialItem ? $socialItem['logo'] : null,
                'nom' => $socialItem ? $socialItem['nom'] : null,
            ];
        });

        // Récupérer les horaires pour la carte spécifique
        $horaires = Horaires::where('idCarte', $idCarte)->get();

        // Définir les fonctions spécifiques
        $fonctions = [
            ['nom' => 'nopub'],
            ['nom' => 'embedyoutube', 'option' => $idCarte ? Carte::find($idCarte)->lienCommande : null]
        ];

        $employe = null;

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");
        $youtubeUrls = [];
        if (File::exists($videosPath)) {
            $youtubeUrls = json_decode(File::get($videosPath), true);
        }

        // Récupérer le modèle de la carte
        $template = Template::find($idTemplate);

        // Renvoyer la bonne vue selon l'idTemplate passé dans l'URL
        switch ($idTemplate) {
            case 1:
                return view('Templates.base', compact('carte', 'compte', 'social', 'vue', 'logoSocial', 'custom', 'employe', 'fonctions', 'lien', 'mergedSocial', 'horaires', 'youtubeUrls'));
            case 2:
                return view('Templates.pomme', compact('carte', 'compte', 'social', 'vue', 'logoSocial', 'custom', 'employe', 'fonctions', 'lien', 'mergedSocial', 'horaires', 'youtubeUrls'));
            case 3:
                return view('Templates.classy', compact('carte', 'compte', 'social', 'vue', 'logoSocial', 'custom', 'employe', 'fonctions', 'lien', 'mergedSocial', 'horaires', 'youtubeUrls'));
            case 4:
                return view('Templates.oxygen', compact('carte', 'compte', 'social', 'vue', 'logoSocial', 'custom', 'employe', 'fonctions', 'lien', 'mergedSocial', 'horaires', 'youtubeUrls'));
            default:
                abort(404, 'Template non trouvé');
        }
    }
}
