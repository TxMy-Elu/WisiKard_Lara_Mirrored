<?php

namespace App\Http\Controllers;

class Connexion extends Controller
{
    public function afficherFormulaireConnexion()
    {
        return view('formulaireConnexion', []);
    }
}