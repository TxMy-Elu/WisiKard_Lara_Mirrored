<?php

// app/Http/Controllers/Entreprise.php
namespace App\Http\Controllers;

use App\Models\Carte;
use App\Models\Compte;
use Illuminate\Http\Request;

class Entreprise extends Controller
{
    public function destroy($id)
    {
        $carte = Carte::findOrFail($id);
        $carte->delete();

        return redirect()->route('dashboardAdmin')->with('success', 'La carte a été supprimée avec succès.');
    }


}