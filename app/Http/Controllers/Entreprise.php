<?php

namespace App\Http\Controllers;

use App\Models\Carte;
use App\Models\Compte;
use App\Models\Logs;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Entreprise extends Controller
{
    /**
     * Supprime une entreprise et toutes ses données associées.
     *
     * @param int $id L'ID de la carte de l'entreprise à supprimer.
     * @return \Illuminate\Http\RedirectResponse Redirige vers la route dashboardAdmin avec un message de succès.
     */
    public function destroy($id)
    {
        $carte = Carte::findOrFail($id);

        // Suppression du compte associé
        $compte = Compte::findOrFail($carte->idCompte);
        $compte->delete();
        Log::info('Le compte de l\'entreprise ' . $carte->nomEntreprise . ' a été supprimé avec succès.');

        // Suppression de la carte
        $carte->delete();
        Log::info('La carte de l\'entreprise ' . $carte->nomEntreprise . ' a été supprimée avec succès.');

        // Suppression du dossier de l'entreprise
        $path = public_path('entreprises/' . $compte->idCompte );
        if (File::exists($path)) {
            File::deleteDirectory($path);
            Log::info('Le dossier de l\'entreprise ' . $carte->nomEntreprise . ' a été supprimé avec succès.');
        }

        // Enregistrement d'un log
        Logs::ecrireLog($compte->email, 'Suppression de compte');

        return redirect()->route('dashboardAdmin')->with('success', 'La carte a été supprimée avec succès.');
    }
}
