<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Vue;
use App\Models\Carte;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardAdmin extends Controller
{
    protected $compte;
    protected $vue;
    protected $carte;
    protected $message;

    public function __construct(Compte $compte, Vue $vue, Carte $carte, Message $message)
    {
        $this->compte = $compte;
        $this->vue = $vue;
        $this->carte = $carte;
        $this->message = $message;
    }

    public function afficherDashboardAdmin(Request $request)
    {
        $search = $request->input('search');
        $entreprises = $this->carte->join('compte', 'carte.idCompte', '=', 'compte.idCompte')
            ->when($search, function ($query, $search) {
                return $query->where('carte.nomEntreprise', 'like', "%{$search}%")
                    ->orWhere('compte.email', 'like', "%{$search}%");
            })
            ->select('carte.*', 'compte.email as compte_email')
            ->get();

        foreach ($entreprises as $entreprise) {
            $entreprise->formattedTel = $this->formatPhoneNumber($entreprise->tel);
        }

        $message = $this->message->where('afficher', true)->orderBy('id', 'desc')->first();
        $messageContent = $message ? $message->message : 'Aucun message disponible';

        return view('admin.dashboardAdmin', compact('entreprises', 'search', 'messageContent'));
    }

    public function statistique(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        $yearlyViews = $this->vue->selectRaw('MONTH(date) as month, COUNT(*) as count')
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

        $totalViews = $this->vue->whereYear('date', $year)->count();
        $totalEntreprise = $this->carte->count();

        $years = range(date('Y'), date('Y') - 10);
        $selectedYear = $year;

        return view('admin.dashboardAdminStatistique', compact('yearlyData', 'years', 'selectedYear', 'month', 'totalViews', 'totalEntreprise'));
    }

    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }

    public function ajoutMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->message->create([
            'message' => $request->input('message'),
            'afficher' => true,
        ]);

        return redirect()->route('dashboardAdminMessage');
    }

    public function supprimerMessage(Request $request)
    {
        $message = $this->message->find($request->input('id'));
        if ($message) {
            $message->delete();
        }

        return redirect()->route('dashboardAdminMessage');
    }

    public function toggleMessage($id)
    {
        $message = $this->message->find($id);
        if ($message) {
            $message->afficher = !$message->afficher;
            $message->save();
        }

        return redirect()->route('dashboardAdminMessage');
    }

    public function modifierMessage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $message = $this->message->findOrFail($id);
        $message->message = $request->input('message');
        $message->save();

        return redirect()->route('dashboardAdminMessage')->with('success', 'Message mis à jour avec succès.');
    }

    public function afficherAllMessage()
    {
        $messages = $this->message->all();
        return view('admin.dashboardAdminMessage', compact('messages'));
    }

    public function refreshQrCode($id)
    {
        $compte = $this->compte->find($id);
        if ($compte) {
            $carte = $this->carte->where('idCompte', $compte->idCompte)->first();
            if ($carte) {
                $compte->QrCode($compte->idCompte, $carte->nomEntreprise);

                $carte->lienQr = "/entreprises/{$compte->idCompte}_{$carte->nomEntreprise}/QR_Codes/QR_Code.svg";
                $carte->save();
            }
        }

        return redirect()->route('dashboardAdmin');
    }
}
