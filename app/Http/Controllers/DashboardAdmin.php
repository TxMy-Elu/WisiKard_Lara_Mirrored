<?php

namespace App\Http\Controllers;

use App\Models\Carte;
use App\Models\Compte;

class DashboardAdmin extends Controller
{
    public function afficherDashboardAdmin()
    {
        $entreprises = Carte::all();
        $compte = Compte::all();
        return view('dashboardAdmin', ['entreprises' => $entreprises, 'compte' => $compte]);
    }
}
