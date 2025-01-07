<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compte;
use App\Models\Logs;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Profil extends Controller
{
    public function afficherPageProfil() {
        // Récupération des infos du JWT contenus dans le cookie. Grâce à l'id de l'utilisateur enregistré dans le JWT on peut récupérer les infos du profil utilisateur et les afficher sur la page du profil
        $cle = "T3mUjGjhC6WuxyNGR2rkUt2uQgrlFUHx";
        $jwt = JWT::decode($_COOKIE["auth"], new Key($cle, 'HS256'));
        $infosAuth = (array) $jwt;
        $compte = Compte::find($infosAuth["sub"]);

        return view('profil', ["infosUtilisateur" => $compte]);
    }
}
