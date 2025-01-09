<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carte;

class DashboardAdmin extends Controller
{
    public function afficherDashboardAdmin(Request $request)
    {
        $search = $request->input('search');
        $entreprises = Carte::query()
            ->join('compte', 'carte.idCompte', '=', 'compte.idCompte')
            ->when($search, function ($query, $search) {
                return $query->where('carte.nomEntreprise', 'like', "%{$search}%")
                    ->orWhere('compte.email', 'like', "%{$search}%");
            })
            ->select('carte.*', 'compte.email as compte_email')
            ->get();

        foreach ($entreprises as $entreprise) {
            $entreprise->formattedTel = $this->formatPhoneNumber($entreprise->tel);
        }

        return view('dashboardAdmin', compact('entreprises', 'search'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $entreprises = Carte::query()
            ->join('compte', 'carte.idCompte', '=', 'compte.idCompte')
            ->when($search, function ($query, $search) {
                return $query->where('carte.nomEntreprise', 'like', "%{$search}%")
                    ->orWhere('compte.email', 'like', "%{$search}%");
            })
            ->select('carte.*', 'compte.email as compte_email')
            ->get();

        foreach ($entreprises as $entreprise) {
            $entreprise->formattedTel = $this->formatPhoneNumber($entreprise->tel);
        }

        return response()->json($entreprises);
    }

    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }
}