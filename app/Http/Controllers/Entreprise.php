<?php

// app/Http/Controllers/Entreprise.php
namespace App\Http\Controllers;

use App\Models\Carte;
use App\Models\Compte;
use App\Models\Logs;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Entreprise extends Controller
{
    public function destroy($id)
    {
        $carte = Carte::findOrFail($id);


        //detruction du compte
        $compte = Compte::findOrFail($carte->idCompte);
        $compte->delete();
        Log::info('Le compte de l\'entreprise '.$carte->nomEntreprise.' a été supprimé avec succès.');
        $carte->delete();
        log::info('La carte de l\'entreprise '.$carte->nomEntreprise.' a été supprimé avec succès.');

        //delete le dossier de l'entreprise
        $path = public_path('entreprises/'.$compte->idCompte.'_'.$carte->nomEntreprise);
        if (file_exists($path)) {
            File::deleteDirectory($path);
            Log::info('Le dossier de l\'entreprise '.$carte->nomEntreprise.' a été supprimé avec succès.');
        }

        Logs::ecrireLog($compte->email, 'Suppression de compte');
        return redirect()->route('dashboardAdmin')->with('success', 'La carte a été supprimée avec succès.');
    }


}