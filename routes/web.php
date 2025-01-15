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
Route::get('/', [Connexion::class, 'afficherFormulaireConnexion'])->name('accueil');
Route::get('/connexion', [Connexion::class, 'afficherFormulaireConnexion'])->name('connexion');
Route::post('/connexion', [Connexion::class, 'validationFormulaire'])->name('validationFormulaireConnexion');
Route::get('/inscription', [Inscription::class, 'afficherFormulaireInscription'])->name('inscription');
Route::post('/inscription', [Inscription::class, 'boutonInscription'])->name('validationFormulaireInscription');
Route::get('/motDePasseOublie', [RecuperationCompte::class, 'afficherFormulaireRecuperation'])->name('motDePasseOublie');
Route::post('/motDePasseOublie', [RecuperationCompte::class, 'boutonRecuperer'])->name('validationEmailMotDePasseOublie');
Route::get('/reinitialisation', [RecuperationCompte::class, 'afficherFormulaireChangementMotDePasse'])->name('reinitialisation');
Route::post('/reinitialisation', [RecuperationCompte::class, 'boutonChangerMotDePasse'])->name('validationChangementMotDePasse');
Route::get('/reactivation', [Connexion::class, 'reactivationCompte'])->name('reactivation');
Route::get('/deconnexion', [Connexion::class, 'deconnexion'])->name('deconnexion');

//Routes templates
Route::get('/templates', [Templates::class, 'afficherTemplates'])->name('templates');


// Routes protégées (accessibles uniquement aux utilisateurs authentifiés)
Route::middleware([Authentification::class])->group(function () {
    // Dashboard Admin
    Route::get('/dashboardAdmin', [DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');
    Route::post('/dashboardAdmin', [DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');
    Route::get('/dashboardAdminStatistique', [DashboardAdmin::class, 'statistique'])->name('dashboardAdminStatistique');
    Route::get('/dashboardAdminMessage', [DashboardAdmin::class, 'afficherAllMessage'])->name('dashboardAdminMessage');
    Route::post('/ajoutMessage', [DashboardAdmin::class, 'ajoutMessage'])->name('ajoutMessage');
    Route::patch('/toggleMessage/{id}', [DashboardAdmin::class, 'toggleMessage'])->name('toggleMessage');
    Route::put('/modifierMessage/{id}', [DashboardAdmin::class, 'modifierMessage'])->name('modifierMessage');

    // Dashboard Client
    Route::get('/dashboardClient', [DashboardClient::class, 'afficherDashboardClient'])->name('dashboardClient');
    Route::get('/dashboardClientStatistique', [DashboardClient::class, 'statistique'])->name('dashboardClientStatistique');
    Route::get('/dashboardClientEmploye/{idCarte}', [DashboardClient::class, 'employer'])->name('dashboardClientEmploye');
    Route::get('/dashboardClientEmploye', [DashboardClient::class, 'employer'])->name('dashboardClientEmploye');
    //Route::get('/dashboardClientEmploye', [DashboardClient::class, 'employer'])->name('dashboardClientEmploye');
    Route::get('/dashboardClientSocial', [DashboardClient::class, 'social'])->name('dashboardClientSocial');
    Route::post('/updateSocialLink', [DashboardClient::class, 'updateSocialLink'])->name('client.updateSocialLink');
    Route::get('/dashboardClientPDF', [DashboardClient::class, 'afficherDashboardClientPDF'])->name('dashboardClientPDF');
    Route::post('/dashboardClientPDF/upload', [DashboardClient::class, 'uploadFile'])->name('dashboardClientPDF.upload');



    // Entreprise
    Route::delete('/entreprise/{id}', [Entreprise::class, 'destroy'])->name('entreprise.destroy');

    // Employe
    Route::get('/inscriptionEmp', [Employe::class, 'afficherFormulaireInscEmpl'])->name('afficherFormulaireInscEmpl');
    Route::post('/inscriptionEmp', [Employe::class, 'boutonInscriptionEmploye'])->name('validationFormulaireInscriptionEmploye');
    Route::delete('/employe/{id}', [DashboardClient::class, 'destroy'])->name('employe.destroy');
    Route::post('/employe', [Inscription::class, 'boutonInscription'])->name('validationFormulaireInscription');
    Route::get('/employe/modifier/{id}', [DashboardClient::class, 'afficherFormulaireModifEmpl'])->name('employe.modifier');
    Route::post('/employe/modifier/{id}', [DashboardClient::class, 'modifierEmploye'])->name('employe.modifier.post');

});