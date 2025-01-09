<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Reactivation;
use App\Models\Compte;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class DashboardAdmin extends Controller
{
    public function afficherDashboardAdmin()
    {
        return view('dashboardAdmin', []);
    }

}
