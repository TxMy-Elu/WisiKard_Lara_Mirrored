<?php

// app/Http/Controllers/Entreprise.php
namespace App\Http\Controllers;

use App\Models\Carte;
use App\Models\Compte;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class Entreprise extends Controller
{
    public function destroy($id)
    {
        $carte = Carte::findOrFail($id);


        //detruction du compte
        $compte = Compte::findOrFail($carte->idCompte);
        $compte->delete();
        $carte->delete();

        //delete le dossier de l'entreprise
        $path = public_path('entreprises/'.$compte->idCompte.'_'.$carte->nomEntreprise);
        if (file_exists($path)) {
            File::deleteDirectory($path);
        }

        return redirect()->route('dashboardAdmin')->with('success', 'La carte a été supprimée avec succès.');
    }


}