<?php

use App\Http\Controllers\Connexion;
use App\Http\Controllers\DashboardAdmin;
use App\Http\Controllers\DashboardClient;
use App\Http\Controllers\Entreprise;
use App\Http\Controllers\Inscription;
use App\Http\Controllers\RecuperationCompte;
use App\Http\Controllers\Employe;
use App\Http\Controllers\Templates;
use App\Http\Middleware\NonAuthentifie;
use App\Http\Middleware\Authentification;

use Illuminate\Support\Facades\Route;


// Routes publiques (accessibles sans authentification)
Route::get('/', [App\Http\Controllers\Connexion::class, 'afficherFormulaireConnexion'])->name('accueil');
Route::get('/connexion', [App\Http\Controllers\Connexion::class, 'afficherFormulaireConnexion'])->name('connexion');
Route::post('/connexion', [App\Http\Controllers\Connexion::class, 'validationFormulaire'])->name('validationFormulaireConnexion');
Route::get('/inscription', [App\Http\Controllers\Inscription::class, 'afficherFormulaireInscription'])->name('inscription');
Route::post('/inscription', [App\Http\Controllers\Inscription::class, 'boutonInscription'])->name('validationFormulaireInscription');
Route::get('/motDePasseOublie', [App\Http\Controllers\RecuperationCompte::class, 'afficherFormulaireRecuperation'])->name('motDePasseOublie');
Route::post('/motDePasseOublie', [App\Http\Controllers\RecuperationCompte::class, 'boutonRecuperer'])->name('validationEmailMotDePasseOublie');
Route::get('/reinitialisation', [App\Http\Controllers\RecuperationCompte::class, 'afficherFormulaireChangementMotDePasse'])->name('reinitialisation');
Route::post('/reinitialisation', [App\Http\Controllers\RecuperationCompte::class, 'boutonChangerMotDePasse'])->name('validationChangementMotDePasse');
Route::get('/reactivation', [App\Http\Controllers\Connexion::class, 'reactivationCompte'])->name('reactivation');
Route::get('/deconnexion', [App\Http\Controllers\Connexion::class, 'deconnexion'])->name('deconnexion');

// Routes templates
Route::get('/templates', [App\Http\Controllers\Templates::class, 'afficherTemplates'])->name('templates');

// Routes protégées (accessibles uniquement aux utilisateurs authentifiés)
Route::middleware([App\Http\Middleware\Authentification::class])->group(function () {
    // Dashboard Admin
    Route::get('/dashboardAdmin', [App\Http\Controllers\DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');
    Route::post('/dashboardAdmin', [App\Http\Controllers\DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');
    Route::get('/dashboardAdminStatistique', [App\Http\Controllers\DashboardAdmin::class, 'statistique'])->name('dashboardAdminStatistique');
    Route::get('/dashboardAdminMessage', [App\Http\Controllers\DashboardAdmin::class, 'afficherAllMessage'])->name('dashboardAdminMessage');
    Route::post('/ajoutMessage', [App\Http\Controllers\DashboardAdmin::class, 'ajoutMessage'])->name('ajoutMessage');
    Route::patch('/toggleMessage/{id}', [App\Http\Controllers\DashboardAdmin::class, 'toggleMessage'])->name('toggleMessage');
    Route::put('/modifierMessage/{id}', [App\Http\Controllers\DashboardAdmin::class, 'modifierMessage'])->name('modifierMessage');

    // Dashboard Client
    Route::get('/dashboardClient', [App\Http\Controllers\DashboardClient::class, 'afficherDashboardClient'])->name('dashboardClient');
    Route::get('/dashboardClientStatistique', [App\Http\Controllers\DashboardClient::class, 'statistique'])->name('dashboardClientStatistique');
    Route::get('/dashboardClientEmploye/{idCarte}', [App\Http\Controllers\DashboardClient::class, 'employer'])->name('dashboardClientEmploye');
    Route::get('/dashboardClientEmploye', [App\Http\Controllers\DashboardClient::class, 'employer'])->name('dashboardClientEmploye');
    Route::get('/dashboardClientSocial', [App\Http\Controllers\DashboardClient::class, 'social'])->name('dashboardClientSocial');
    Route::post('/updateSocialLink', [App\Http\Controllers\DashboardClient::class, 'updateSocialLink'])->name('client.updateSocialLink');
    Route::get('/dashboardClientPDF', [App\Http\Controllers\DashboardClient::class, 'afficherDashboardClientPDF'])->name('dashboardClientPDF');
    Route::post('/dashboardClientPDF/upload', [App\Http\Controllers\DashboardClient::class, 'uploadFile'])->name('dashboardClientPDF.upload');

    // Entreprise
    Route::delete('/entreprise/{id}', [App\Http\Controllers\Entreprise::class, 'destroy'])->name('entreprise.destroy');

    // Employe
    Route::get('/inscriptionEmp', [App\Http\Controllers\Employe::class, 'afficherFormulaireInscEmpl'])->name('afficherFormulaireInscEmpl');
    Route::post('/inscriptionEmp', [App\Http\Controllers\Employe::class, 'boutonInscriptionEmploye'])->name('validationFormulaireInscriptionEmploye');
    Route::delete('/employe/{id}', [App\Http\Controllers\DashboardClient::class, 'destroy'])->name('employe.destroy');
    Route::post('/employe', [App\Http\Controllers\Inscription::class, 'boutonInscription'])->name('validationFormulaireInscription');
    Route::get('/employe/modifier/{id}', [App\Http\Controllers\DashboardClient::class, 'afficherFormulaireModifEmpl'])->name('employe.modifier');
    Route::post('/employe/modifier/{id}', [App\Http\Controllers\DashboardClient::class, 'modifierEmploye'])->name('employe.modifier.post');
});