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
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class Templates extends Controller
{
    private function shouldCountView(Request $request, $idCarte, $idEmp = null)
    {
        $ip = $request->ip();
        $cookieKey = "viewed_card_{$idCarte}" . ($idEmp ? "_{$idEmp}" : "");
        
        // Vérifier si un cookie existe déjà
        if (Cookie::has($cookieKey)) {
            return false;
        }

        // Vérifier dans la base de données si cette IP a déjà vu cette carte aujourd'hui
        $recentView = DB::table('vue')
            ->where('idCarte', $idCarte)
            ->where('idEmp', $idEmp)
            ->where('ip_address', $ip)
            ->where('date', '>=', now()->subHours(24))
            ->exists();

        if (!$recentView) {
            // Créer un cookie qui expire dans 24 heures
            Cookie::queue($cookieKey, 'viewed', 60 * 24);
            return true;
        }

        return false;
    }

    private function getBaseData($idCarte, $idCompte)
    {
        $data = [
            'carte' => null,
            'compte' => null,
            'lien' => [],
            'custom' => [],
            'vue' => [],
            'template' => null,
            'mergedSocial' => [],
            'horaires' => [],
            'youtubeUrls' => []
        ];

        // Récupération des données de base
        $data['compte'] = Compte::find($idCompte);
        $data['lien'] = Rediriger::where('idCarte', $idCarte)->get();
        $data['custom'] = Custom_link::where('idCarte', $idCarte)->where('activer', 1)->get();
        $data['vue'] = Vue::where('idCarte', $idCarte)->get();
        $data['horaires'] = Horaires::where('idCarte', $idCarte)->get();

        // Récupération des réseaux sociaux
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

        $data['mergedSocial'] = $social->map(function ($item) use ($logoSocial) {
            $socialItem = $logoSocial->firstWhere('id', $item['id']);
            return [
                'lien' => $item['lien'],
                'logo' => $socialItem ? $socialItem['logo'] : null,
                'nom' => $socialItem ? $socialItem['nom'] : null,
            ];
        });

        // Récupération des vidéos
        $videosPath = public_path("entreprises/{$idCompte}/videos/videos.json");
        $data['youtubeUrls'] = File::exists($videosPath) 
            ? json_decode(File::get($videosPath), true) 
            : [];

        return $data;
    }

    private function renderTemplate($idTemplate, $data)
    {
        // Récupérer le template depuis la base de données
        $template = Template::find($idTemplate);
        if (!$template) {
            return response()->json([
                'message' => 'Aucun template trouvé',
                'data' => ['idCarte' => $data['carte']->idCarte ?? null, 'employe' => $data['employe'] ?? null]
            ], 404);
        }

        return view('Templates.' . $template->nom, $data);
    }

    /**
     * Affiche les templates de cartes de visite en fonction des paramètres de la requête.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Retourne la vue correspondant au template spécifié avec les données nécessaires ou un message JSON en cas d'erreur.
     */
    public function afficherTemplates(Request $request)
    {
        // Redirection des anciens liens utilisant idCompte vers idKard
        if ($request->has('idCompte')) {
            $oldId = $request->query('idCompte');
            return redirect()->to("/Kard/{$request->companyName}?idKard=" . $oldId);
        }

        // Vérifier si "CompteEmp" est présent dans la requête
        $CompteEmp = $request->query('Emp');
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
            
            if (!$carte) {
                abort(404);
            }

            //idCarte
            $idCarte = $carte->idCarte ?? null;

            // Récupérer les infos de l'employé en fonction de l'idCarte et idEmp
            $employe = Employer::where('idCarte', $carte->idCarte)->where('idEmp', $idEmp)->first();

            //idTemplate
            $idTemplate = $carte->idTemplate ?? null;

            // Ajouter la vue uniquement si nécessaire
            if ($this->shouldCountView($request, $idCarte, $idEmp)) {
                $vue = new Vue();
                $vue->date = $today;
                $vue->idCarte = $idCarte;
                $vue->idEmp = $idEmp;
                $vue->ip_address = $request->ip();
                $vue->save();
            }

        } else {
            // Sinon, récupérer l'idKard au lieu de idCompte
            $idCompte = $request->query('idKard');

            // Récupérer d'abord l'idTemplate
            $idTemplate = Carte::where('idCompte', $idCompte)->value('idTemplate');

            // Prend toutes les informations nécessaires depuis la base de données
            $carte = Carte::where('idCompte', $idCompte)->first();
            
            if (!$carte) {
                abort(404);
            }

            $idCarte = $carte->idCarte ?? null;

            // Ajouter la vue uniquement si nécessaire
            if ($this->shouldCountView($request, $idCarte)) {
                $vue = new Vue();
                $vue->date = $today;
                $vue->idCarte = $idCarte;
                $vue->ip_address = $request->ip();
                $vue->save();
            }
        }

        // Si $idCarte est toujours null, on ne peut rien afficher
        if (!$idCarte) {
            return response()->json(['message' => 'idCarte non trouvé.'], 404);
        }

        $data = $this->getBaseData($idCarte, $idCompte);
        $data['carte'] = $carte;
        $data['employe'] = $employe;
        $data['template'] = Template::find($idTemplate);

        return $this->renderTemplate($idTemplate, $data);
    }

    /**
     * Affiche les templates de cartes de visite dans un iframe en fonction des paramètres de la requête.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Retourne la vue correspondant au template spécifié avec les données nécessaires ou une erreur 404 si le template n'est pas trouvé.
     */
    public function afficherIframe(Request $request)
    {
        // Vérifier si "CompteEmp" est présent dans la requête
        $CompteEmp = $request->query('CompteEmp');
        $idCompte = null;
        $idEmp = null;
        $employe = null;
        $today = date('Y-m-d');


        // Sinon, récupérer l'idCompte
        $idCompte = session('connexion');

        // Récupérer d'abord l'idTemplate
        $idTemplate = $request->query('idTemplate');

        // Prend toutes les informations nécessaires depuis la base de données
        $carte = Carte::where('idCompte', $idCompte)->first();
        $idCarte = $carte->idCarte ?? null;

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

        $folderName = "{$idCompte}";

        $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");
        $youtubeUrls = [];
        if (File::exists($videosPath)) {
            $youtubeUrls = json_decode(File::get($videosPath), true);
        }

        $data = $this->getBaseData($idCarte, $idCompte);
        $data['carte'] = $carte;
        $data['employe'] = $employe;
        $data['template'] = $template;

        return $this->renderTemplate($idTemplate, $data);
    }
}
