<?php

use App\Http\Controllers\Connexion;
use App\Http\Controllers\DashboardAdmin;
use App\Http\Controllers\DashboardClient;
use App\Http\Controllers\Entreprise;
use App\Http\Controllers\Inscription;
use App\Http\Controllers\RecuperationCompte;
use App\Http\Middleware\NonAuthentifie;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [Connexion::class, 'afficherFormulaireConnexion'])->middleware([NonAuthentifie::class])->name('accueil');

Route::get('/connexion', [Connexion::class, 'afficherFormulaireConnexion'])->middleware([NonAuthentifie::class])->name('connexion');
Route::post('/connexion', [Connexion::class, 'validationFormulaire'])->middleware([NonAuthentifie::class])->name('validationFormulaireConnexion');

Route::get('/inscription', [Inscription::class, 'afficherFormulaireInscription'])->name('inscription');
Route::post('/inscription', [Inscription::class, 'boutonInscription'])->name('validationFormulaireInscription');

Route::get('/motDePasseOublie', [RecuperationCompte::class, 'afficherFormulaireRecuperation'])->name('motDePasseOublie');
Route::post('/motDePasseOublie', [RecuperationCompte::class, 'boutonRecuperer'])->name('validationEmailMotDePasseOublie');

Route::get('/reinitialisation', [RecuperationCompte::class, 'afficherFormulaireChangementMotDePasse'])->name('reinitialisation');
Route::post('/reinitialisation', [RecuperationCompte::class, 'boutonChangerMotDePasse'])->name('validationChangementMotDePasse');

Route::get('/reactivation', [Connexion::class, 'reactivationCompte'])->name('reactivation');

Route::get('/deconnexion', [Connexion::class, 'deconnexion'])->name('deconnexion');

Route::post('/dashboardAdmin', [DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');

Route::get('/dashboardAdmin', [DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');
Route::get('/dashboardClient', [DashboardClient::class, 'afficherDashboardClient'])->name('dashboardClient');

Route::post('/dashboardClient', [DashboardClient::class, 'afficherDashboardClient'])->name('dashboardClient');

Route::get('/dashboardAdminStatistique', [DashboardAdmin::class, 'statistique'])->name('dashboardAdminStatistique');
Route::delete('/entreprise/{id}', [Entreprise::class, 'destroy'])->name('entreprise.destroy');
