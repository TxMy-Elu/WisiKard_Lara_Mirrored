<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carte;
use App\Models\Employer;
use App\Models\Vue;

class DashboardClient extends Controller
{

    public function afficherDashboardClient(Request $request)
    {



        return view('dashboardClient', []);
    }

    public function employer(){
            $employes = Employer::query()
                ->join('carte', 'employer.idEmp', '=', 'carte.idCarte')
                ->join('compte', 'carte.idCarte', '=', 'compte.idCompte')
                ->select('employer.*', 'compte.email as compte_email')
                ->get();
               // dd($employes);
                return view('dashboardClientEmployer', ['employes' => $employes]) ;

    }
    public function destroy($id)
    {
        $employer = Employer::findOrFail($id);

        Vue::where('idEmp', $id)->delete();

        $employer->delete();

        return redirect()->route('dashboardClientEmployer')->with('success', 'L\'employé a été supprimé avec succès.');
    }

}
