<?php

namespace App\Http\Controllers;

use App\Models\Vue;
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

    public function statistique(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        // Yearly data
        $yearlyViews = Vue::selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $yearlyData = [
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'datasets' => [
                [
                    'label' => 'Nombre de vue par mois',
                    'backgroundColor' => 'rgba(153, 27, 27, 0.2)',
                    'borderColor' => 'rgba(153, 27, 27, 1)',
                    'borderWidth' => 1,
                    'data' => array_values(array_replace(array_fill(0, 12, 0), $yearlyViews)),
                ],
            ],
        ];

        //nombre de vue au total
        $totalViews = Vue::whereYear('date', $year)->count();

        //nombre total d'entreprise
        $totalEntreprise = Carte::count();


        $years = range(date('Y'), date('Y') - 10);
        $selectedYear = $year;

        return view('dashboardAdminStatistique', compact('yearlyData',  'years', 'selectedYear', 'month', 'totalViews', 'totalEntreprise'));
    }

    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }
}