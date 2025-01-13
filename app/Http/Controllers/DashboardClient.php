<?php

namespace App\Http\Controllers;

use App\Models\Vue;
use Illuminate\Http\Request;
use App\Models\Carte;

class DashboardAdmin extends Controller
{
    public function afficherDashboardClient(Request $request)
    {

        return view('dashboardClient');
    }

    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }
}