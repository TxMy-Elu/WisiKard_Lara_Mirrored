<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carte;
use App\Models\Employer;

class DashboardClient extends Controller
{
    public function afficherDashboardClient(Request $request, $id)
    {
        $compte = Compte::find($id);

        return view('dashboardClient', ['compte' => $compte]);
    }



    public function employer()
    {
        $employes = Employer::query()
            ->join('carte', 'employer.idEmp', '=', 'carte.idCarte')
            ->join('compte', 'carte.idCarte', '=', 'compte.idCompte')
            ->select('employer.*', 'compte.email as compte_email')
            ->get();
        return view('dashboardClientEmployer', ['employes' => $employes]);
    }

    public function destroy($id)
    {
        $employer = Employer::findOrFail($id);
        $employer->delete();

        return redirect()->route('dashboardClientEmployer')->with('success', 'L\'employé a été supprimé avec succès.');
    }
}
