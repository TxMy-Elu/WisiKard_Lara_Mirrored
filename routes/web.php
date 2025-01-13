<?php

use App\Http\Controllers\Connexion;
use App\Http\Controllers\DashboardAdmin;
use App\Http\Controllers\DashboardClient;
use App\Http\Controllers\Entreprise;
use App\Http\Controllers\Inscription;
use App\Http\Controllers\RecuperationCompte;
use App\Http\Middleware\NonAuthentifie;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\Authentification;
use Illuminate\Support\Facades\Route;

// Routes publiques (accessibles sans authentification)
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

// Routes protégées (accessibles uniquement aux utilisateurs authentifiés)
Route::middleware([Authentification::class])->group(function () {
    Route::get('/dashboardAdmin', [DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');
    Route::post('/dashboardAdmin', [DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');
    Route::get('/dashboardClient', [DashboardClient::class, 'afficherDashboardClient'])->name('dashboardClient');
    Route::post('/dashboardClient', [DashboardClient::class, 'afficherDashboardClient'])->name('dashboardClient');
    Route::get('/dashboardAdminStatistique', [DashboardAdmin::class, 'statistique'])->name('dashboardAdminStatistique');
    Route::delete('/entreprise/{id}', [Entreprise::class, 'destroy'])->name('entreprise.destroy');
    Route::get('/dashboardClientEmployer', [DashboardClient::class, 'employer'])->name('dashboardClientEmployer');
    Route::delete('/employe/{id}', [DashboardClient::class, 'destroy'])->name('employe.destroy');
    Route::get('/dashboardAdminMessage', [DashboardAdmin::class, 'afficherAllMessage'])->name('dashboardAdminMessage');
    Route::post('/ajoutMessage', [DashboardAdmin::class, 'ajoutMessage'])->name('ajoutMessage');
    Route::patch('/toggleMessage/{id}', [DashboardAdmin::class, 'toggleMessage'])->name('toggleMessage');
    Route::put('/modifierMessage/{id}', [DashboardAdmin::class, 'modifierMessage'])->name('modifierMessage');
});
