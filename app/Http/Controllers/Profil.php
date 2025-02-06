<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compte;
use App\Models\Logs;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Profil extends Controller
{
    /**
     * Affiche la page de profil de l'utilisateur.
     *
     * @return \Illuminate\View\View Retourne la vue 'profil' avec les informations de l'utilisateur.
     */
    public function afficherPageProfil()
    {
        // Récupération des informations du JWT contenu dans le cookie. Grâce à l'ID de l'utilisateur enregistré dans le JWT, on peut récupérer les informations du profil utilisateur et les afficher sur la page du profil.
        $cle = "T3mUjGjhC6WuxyNGR2rkUt2uQgrlFUHx";
        $jwt = JWT::decode($_COOKIE["auth"], new Key($cle, 'HS256'));
        $infosAuth = (array) $jwt;
        $compte = Compte::find($infosAuth["sub"]);

        return view('profil', ["infosUtilisateur" => $compte]);
    }
}
