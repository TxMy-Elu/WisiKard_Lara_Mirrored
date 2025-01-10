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

        // Weekly data
        $weeklyData = null;
        if ($month) {
            $weeklyViews = Vue::selectRaw('YEAR(date) as annee, MONTH(date) as mois, WEEK(date) as semaine, COUNT(*) as nombre_de_vues')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->groupBy('annee', 'mois', 'semaine')
                ->orderBy('annee', 'asc')
                ->orderBy('mois', 'asc')
                ->orderBy('semaine', 'asc')
                ->pluck('nombre_de_vues', 'semaine')
                ->toArray();

            $weeksInMonth = $this->getWeeksInMonth($year, $month);
            $weeklyData = [
                'labels' => $weeksInMonth,
                'datasets' => [
                    [
                        'label' => 'Nombre de vue par semaine',
                        'backgroundColor' => 'rgba(27, 153, 27, 0.2)',
                        'borderColor' => 'rgba(27, 153, 27, 1)',
                        'borderWidth' => 1,
                        'data' => array_values(array_replace(array_fill(0, count($weeksInMonth), 0), $weeklyViews)),
                    ],
                ],
            ];
        }

        $years = range(date('Y'), date('Y') - 10);
        $selectedYear = $year;

        return view('dashboardAdminStatistique', compact('yearlyData', 'weeklyData', 'years', 'selectedYear', 'month'));
    }

    private function getWeeksInMonth($year, $month)
    {
        $date = new \DateTime("$year-$month-01");
        $weeks = [];
        while ($date->format('m') == $month) {
            $weeks[] = 'Week ' . $date->format('W');
            $date->modify('+1 week');
        }
        return $weeks;
    }

    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }
}