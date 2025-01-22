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

        $compte = Compte::find($idCompte);
        $logoSocial = Social::all();
        $social = Rediriger::where('idCarte', $carte->idCarte)
            ->where('activer', 1)
            ->get();
        $custom = Custom_Link::where('idCarte', $carte->idCarte) ->where('activer', 1)->get();
        $vue = Vue::where('idCarte', $carte->idCarte)->get();
        $template = Template::where('idTemplate', $idTemplate)->get();

        // Récupérer les employés associés à la carte
        $employe = Employer::where('idCarte', $carte->idCarte)->first();

        // Récupérer les fonctions spécifiques
        $fonctions = [
            ['nom' => 'nopub'],
            ['nom' => 'embedyoutube', 'option' => $carte->lienCommande]
        ];

        switch ($idTemplate) {
            case 1:
                return view('Templates.oxygen', compact('carte', 'compte', 'social', 'vue', 'template', 'logoSocial', 'custom', 'employe', 'fonctions'));
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