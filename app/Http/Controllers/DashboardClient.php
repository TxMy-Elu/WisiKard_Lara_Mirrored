<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardClient extends Controller
{

    public function afficherDashboardClient(Request $request)
    {



        return view('dashboardClient', []);
    }
}
