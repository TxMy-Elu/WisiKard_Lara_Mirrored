<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carte;

class DashboardAdmin extends Controller
{
    public function afficherDashboardAdmin()
    {
        $entreprises = Carte::all();

        foreach ($entreprises as $entreprise) {
            $entreprise->formattedTel = $this->formatPhoneNumber($entreprise->tel);
        }

        return view('dashboardAdmin', compact('entreprises'));
    }

    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }
}