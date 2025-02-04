<?php
namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use App\Models\Employer;
use App\Models\Carte;
use App\Models\Inscription_attente;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InscriptionAttente extends Controller
{
     public function InscriptionAttente(Request $request)
        {
            $search = $request->input('search');
            $entreprises = $this->carte->join('inscript_attente', 'carte.idCompte', '=', 'inscript_attente.id_inscripAttente')
                ->when($search, function ($query, $search) {
                    return $query->where('carte.nomEntreprise', 'like', "%{$search}%")
                        ->orWhere('inscript_attente.mail', 'like', "%{$search}%");
                })
                ->select('carte.*', 'inscript_attente.mail as compte_email', 'inscript_attente.role as compte_role')
                ->get();

            foreach ($entreprises as $entreprise) {
                $entreprise->formattedTel = $this->formatPhoneNumber($entreprise->tel);
            }

            $message = $this->message->where('afficher', true)->orderBy('id', 'desc')->first();
            $messageContent = $message ? $message->message : 'Aucun message disponible';

            return view('Admin.dashboardAdminInscriptionAttente', compact('entreprises', 'search', 'messageContent'));
        }

}