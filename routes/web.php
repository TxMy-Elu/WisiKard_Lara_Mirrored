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
use App\Http\Middleware\AdminMiddleware;

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

// Routes Templates
Route::get('/Templates', [Templates::class, 'afficherTemplates'])->name('Templates');

Route::get('/iframe', [Templates::class, 'afficherIframe'])->name('Iframe');


// Routes protégées par authentification
Route::middleware([Authentification::class])->group(function () {

    // Groupe réservé aux Administrateurs (AdminMiddleware)
    Route::middleware([AdminMiddleware::class])->group(function () {
        // Dashboard Admin
        Route::get('/dashboardAdmin', [DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');
        Route::post('/dashboardAdmin', [DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');
        Route::get('/dashboardAdminStatistique', [DashboardAdmin::class, 'statistique'])->name('dashboardAdminStatistique');
        Route::get('/dashboardAdminMessage', [DashboardAdmin::class, 'afficherAllMessage'])->name('dashboardAdminMessage');
        Route::post('/ajoutMessage', [DashboardAdmin::class, 'ajoutMessage'])->name('ajoutMessage');
        Route::patch('/toggleMessage/{id}', [DashboardAdmin::class, 'toggleMessage'])->name('toggleMessage');
        Route::get('/refreshQrCode/{id}', [DashboardAdmin::class, 'refreshQrCode'])->name('refreshQrCode');
        Route::put('/modifierMessage/{id}', [DashboardAdmin::class, 'modifierMessage'])->name('modifierMessage');
    });

    // Groupe réservé aux Clients (après authentification)
    Route::get('/dashboardClient', [DashboardClient::class, 'afficherDashboardClient'])->name('dashboardClient');
    Route::get('/dashboardClientStatistique', [DashboardClient::class, 'statistique'])->name('dashboardClientStatistique');
    Route::get('/dashboardClientEmploye/{idCarte}', [DashboardClient::class, 'employer'])->name('dashboardClientEmploye');
    Route::get('/dashboardClientEmploye', [DashboardClient::class, 'employer'])->name('dashboardClientEmploye');
    Route::get('/dashboardClientSocial', [DashboardClient::class, 'social'])->name('dashboardClientSocial');
    Route::post('/updateSocialLink', [DashboardClient::class, 'updateSocialLink'])->name('client.updateSocialLink');

    Route::get('/refreshQrCodeEmp/{id}/{empId}', [DashboardClient::class, 'refreshQrCodeEmp'])->name('refreshQrCodeEmp');
    Route::get('/formulaireEntreprise', [DashboardClient::class, 'afficherFormulaireEntreprise'])->name('formulaireEntreprise');
    Route::post('/updateEntreprise', [DashboardClient::class, 'updateEntreprise'])->name('updateEntreprise');

    // Gestion des PDF
    Route::get('/dashboardClientPDF', [DashboardClient::class, 'afficherDashboardClientPDF'])->name('dashboardClientPDF');
    Route::post('/dashboardClientPDF/uploadPdf', [DashboardClient::class, 'uploadPdf'])->name('dashboardClientPDF.uploadPdf');

    // Gestion des sliders
    Route::post('/dashboardClientPDF/uploadSlider', [DashboardClient::class, 'uploadSlider'])->name('dashboardClientPDF.uploadSlider');
    Route::delete('/dashboardClientPDF/deleteSliderImage', [DashboardClient::class, 'deleteSliderImage'])->name('dashboardClientPDF.deleteSliderImage');
    //Youtube
    Route::post('/dashboardClientPDF/uploadYouTubeVideo', [DashboardClient::class, 'uploadYouTubeVideo'])->name('dashboardClientPDF.uploadYouTubeVideo');
    Route::delete('/dashboardClientPDF/deleteVideo/{index}', [DashboardClient::class, 'deleteVideo'])->name('dashboardClientPDF.deleteVideo');
    //Img
    Route::post('/dashboardClientPDF/upload', [DashboardClient::class, 'uploadFile'])->name('dashboardClientPDF.upload');
    Route::delete('/dashboardClientPDF/deleteImage/{filename}', [DashboardClient::class, 'deleteImage'])->name('dashboardClientPDF.deleteImage');
    //UrlRDV
    Route::post('/dashboardClientPDF/urlsrdv', [DashboardClient::class, 'urlsrdv'])->name('dashboardClientPDF.urlsrdv');
    Route::delete('/dashboardClientPDF/deleteRDV/{index}', [DashboardClient::class, 'deleteRDV'])->name('dashboardClientPDF.deleteRDV');
    //Logo
    Route::delete('/dashboardClientPDF/deleteLogo', [DashboardClient::class, 'deleteLogo'])->name('dashboardClientPDF.deleteLogo');
    Route::post('/dashboardClientPDF/uploadLogo', [DashboardClient::class, 'uploadLogo'])->name('dashboardClientPDF.uploadLogo');

    // Personnalisation des Clients
    Route::post('/updateTemplate', [DashboardClient::class, 'updateTemplate'])->name('updateTemplate');
    Route::post('/dashboardClientInfo', [DashboardClient::class, 'updateInfo'])->name('dashboardClientInfo');
    Route::post('/dashboardClientCustomLink', [DashboardClient::class, 'updateCustomLink'])->name('dashboardClientCustomLink');
    Route::post('/activeSocialLink', [DashboardClient::class, 'updateSocialLinkCustom'])->name('activeSocialLink');
    Route::post('/dashboardClientColor', [DashboardClient::class, 'updateColor'])->name('dashboardClientColor');
    Route::patch('/updateFont', [DashboardClient::class, 'updateFont'])->name('updateFont');

    // QrCode pour des fonctionnalités client
    Route::get('/downloadQrCodes', [DashboardClient::class, 'downloadQrCodes'])->name('downloadQrCodes');
    Route::get('/downloadQrCodesColor', [DashboardClient::class, 'downloadQrCodesColor'])->name('downloadQrCodesColor');

    // Gestion Entreprises
    Route::delete('/entreprise/{id}', [Entreprise::class, 'destroy'])->name('entreprise.destroy');

    // Gestion des Employés
    Route::get('/inscriptionEmp', [Employe::class, 'afficherFormulaireInscEmpl'])->name('afficherFormInsEmploye');
    Route::post('/inscriptionEmploye', [Employe::class, 'boutonInscriptionEmploye'])->name('inscriptionEmploye.post');
    Route::delete('/employe/{id}', [DashboardClient::class, 'destroy'])->name('employe.destroy');
    Route::get('/employe/{id}/edit', [Employe::class, 'edit'])->name('employe.edit');
    Route::put('/employe/{id}', [Employe::class, 'update'])->name('employe.update');

    
});
