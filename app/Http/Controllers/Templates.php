<?php
// app/Http/Controllers/Connexion.php

namespace App\Http\Controllers;

use App\Models\Carte;
use App\Models\Compte;
use App\Models\Custom_Link;
use App\Models\Employer;
use App\Models\Rediriger;
use App\Models\Social;
use App\Models\Template;
use App\Models\Vue;
use Illuminate\Http\Request;


class Templates extends Controller
{
    public function afficherTemplates(Request $request)
    {
        $idCompte = $request->query('idCompte');

        $idTemplate = Carte::where('idCompte', $idCompte)->value('idTemplate');

        // Prend tout les infos de la carte et les envoie à la vue
        $carte = Carte::where('idCompte', $idCompte)->first();

        $compte = Compte::find($idCompte); // On récupère le compte spécifique
        $lien = Rediriger::where('idCarte', $carte->idCarte)->get(); // Tous les liens associés à une carte
        $custom = Custom_Link::where('idCarte', $carte->idCarte)->where('activer', 1)->get(); // Liens personnalisés activés pour une carte
        $vue = Vue::where('idCarte', $carte->idCarte)->get(); // Toutes les vues d'une carte
        $template = Template::find($idTemplate); // Récupère un template spécifique

        // Récupérer tous les réseaux sociaux
        $logoSocial = Social::all()->map(function ($item) {
            return [
                'id' => $item->idSocial,
                'logo' => $item->lienLogo,
                'nom' => $item->nom,
            ];
        });

// Récupérer les liens activés pour une carte spécifique
        $social = Rediriger::where('idCarte', $carte->idCarte)->where('activer', 1)->get()->map(function ($item) {
            return [
                'id' => $item->idSocial,
                'lien' => $item->lien,
                'activer' => $item->activer,
            ];
        });

// Fusionner les collections
        $mergedSocial = $social->map(function ($item) use ($logoSocial) {
            $socialItem = $logoSocial->firstWhere('id', $item['id']);
            return [
                'lien' => $item['lien'],
                'logo' => $socialItem ? $socialItem['logo'] : null,
                'nom' => $socialItem ? $socialItem['nom'] : null,
            ];
        });



        // Récupérer les employés associés à la carte
        $employe = Employer::where('idCarte', $carte->idCarte)->first();

        // Récupérer les fonctions spécifiques
        $fonctions = [
            ['nom' => 'nopub'],
            ['nom' => 'embedyoutube', 'option' => $carte->lienCommande]
        ];

        switch ($idTemplate) {
            case 1:
                return view('Templates.oxygen', compact('carte', 'compte', 'social', 'vue', 'template', 'logoSocial', 'custom', 'employe', 'fonctions', 'lien', 'mergedSocial'));
            case 2:
                return view('Templates.fraise', compact('carte', 'compte', 'social', 'vue', 'template', 'logoSocial', 'custom', 'employe', 'fonctions'));
            case 3:
                return view('Templates.peche', compact('carte', 'compte', 'social', 'vue', 'template', 'logoSocial', 'custom', 'employe', 'fonctions'));

        }

        // return var_dump($carte, $idTemplate, $idCompte);
    }


    public function iframePomme()
    {
        return view('Templates.Iframe.pomme');
    }

    public function iframeFraise()
    {
        return view('Templates.Iframe.fraise');
    }

    public function iframePeche()
    {
        return view('Templates.Iframe.peche');
    }

}