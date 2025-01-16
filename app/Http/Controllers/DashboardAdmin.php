<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Vue;
use Illuminate\Http\Request;
use App\Models\Carte;
use App\Models\Message;

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

        $message = Message::where('afficher', true)->orderBy('id', 'desc')->first();
        $messageContent = $message ? $message->message : 'Aucun message disponible';

        return view('admin.dashboardAdmin', compact('entreprises', 'search', 'messageContent'));
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
                    'data' => array_values(array_replace(array_fill(1, 12, 0), $yearlyViews)),
                ],
            ],
        ];

        //nombre de vue au total
        $totalViews = Vue::whereYear('date', $year)->count();

        //nombre total d'entreprise
        $totalEntreprise = Carte::count();


        $years = range(date('Y'), date('Y') - 10);
        $selectedYear = $year;

        return view('admin.dashboardAdminStatistique', compact('yearlyData',  'years', 'selectedYear', 'month', 'totalViews', 'totalEntreprise'));
    }

    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }

    public function ajoutMessage(Request $request)
    {
        $message = $request->input('message');

        Message::create([
            'message' => $message,
            'afficher' => true, // or false, depending on your requirement
        ]);

        return redirect()->route('dashboardAdminMessage');
    }

    public function supprimerMessage(Request $request)
    {
        $message = Message::find($request->input('id'));
        $message->delete();

        return redirect()->route('dashboardAdminMessage');
    }



    public function toggleMessage($id)
    {
        $message = Message::find($id);
        $message->afficher = !$message->afficher;
        $message->save();

        return redirect()->route('dashboardAdminMessage');
    }

    public function modifierMessage(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $message->message = $request->input('message');
        $message->save();

        return redirect()->route('dashboardAdminMessage')->with('success', 'Message mis à jour avec succès.');
    }


    public function afficherAllMessage()
    {
        $messages = Message::all();
        return view('admin.dashboardAdminMessage', compact('messages'));
    }

    public function refreshQrCode($id)
    {
        $compte = Compte::find($id);
        $carte = Carte::where('idCompte', $compte->idCompte)->first();
        $compte->QrCode($compte->idCompte, $carte->nomEntreprise);

        //update lienQr
        $carte->lienQr = "/entreprises/{$compte->idCompte}_{$carte->nomEntreprise}/QR_Codes/QR_Code.svg";
        $carte->save();

        return redirect()->route('dashboardAdmin');
    }
}