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

//Routes Templates
Route::get('/Templates', [Templates::class, 'afficherTemplates'])->name('Templates');

//Route Iframe
Route::get('/Templates/iframe/pomme', [Templates::class, 'iframePomme'])->name('Templates.iframes.pomme');
Route::get('/Templates/iframe/fraise', [Templates::class, 'iframeFraise'])->name('Templates.iframes.fraise');
Route::get('/Templates/iframe/peche', [Templates::class, 'iframePeche'])->name('Templates.iframes.peche');

// Routes protégées (accessibles uniquement aux utilisateurs authentifiés)
Route::middleware([App\Http\Middleware\Authentification::class])->group(function () {
    // Dashboard Admin
    Route::get('/dashboardAdmin', [DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');
    Route::post('/dashboardAdmin', [DashboardAdmin::class, 'afficherDashboardAdmin'])->name('dashboardAdmin');
    Route::get('/dashboardAdminStatistique', [DashboardAdmin::class, 'statistique'])->name('dashboardAdminStatistique');
    Route::get('/dashboardAdminMessage', [DashboardAdmin::class, 'afficherAllMessage'])->name('dashboardAdminMessage');
    Route::post('/ajoutMessage', [DashboardAdmin::class, 'ajoutMessage'])->name('ajoutMessage');
    Route::patch('/toggleMessage/{id}', [DashboardAdmin::class, 'toggleMessage'])->name('toggleMessage');
    Route::get('/refreshQrCode', [DashboardAdmin::class, 'refreshQrCode'])->name('refreshQrCode');



    // Dashboard Client
    Route::get('/dashboardClient', [DashboardClient::class, 'afficherDashboardClient'])->name('dashboardClient');
    Route::get('/dashboardClientStatistique', [DashboardClient::class, 'statistique'])->name('dashboardClientStatistique');
    Route::get('/dashboardClientEmploye/{idCarte}', [DashboardClient::class, 'employer'])->name('dashboardClientEmploye');
    Route::get('/dashboardClientEmploye', [DashboardClient::class, 'employer'])->name('dashboardClientEmploye');
    Route::get('/dashboardClientSocial', [DashboardClient::class, 'social'])->name('dashboardClientSocial');
    Route::post('/updateSocialLink', [DashboardClient::class, 'updateSocialLink'])->name('client.updateSocialLink');
    Route::post('/dashboardClientPDF/upload', [DashboardClient::class, 'uploadFile'])->name('dashboardClientPDF.upload');

    Route::get('/refresh-qr-code/{id}', [DashboardAdmin::class, 'refreshQrCode'])->name('refreshQrCode');
    Route::get('/refreshQrCodeEmp/{id}/{empId}', [DashboardClient::class, 'refreshQrCodeEmp'])->name('refreshQrCodeEmp');

    Route::get('/formulaireEntreprise', [DashboardClient::class, 'afficherFormulaireEntreprise'])->name('formulaireEntreprise');
    Route::post('/updateEntreprise', [DashboardClient::class, 'updateEntreprise'])->name('updateEntreprise');

    //IMAGE
    Route::delete('/dashboardClientPDF/deleteImage/{filename}', [DashboardClient::class, 'deleteImage'])->name('dashboardClientPDF.deleteImage');

    //PDF
    Route::get('/dashboardClientPDF', [DashboardClient::class, 'afficherDashboardClientPDF'])->name('dashboardClientPDF');
    Route::post('/dashboardClientPDF/renamePdf', [App\Http\Controllers\DashboardClientPDFController::class, 'renamePdf'])->name('dashboardClientPDF.renamePdf');
    Route::delete('/dashboardClientPDF/deletePDF/{filename}', [DashboardClient::class, 'deletePDF'])->name('dashboardClientPDF.deletePDF');

    //Logo
    Route::delete('/dashboardClientPDF/deleteLogo', [DashboardClient::class, 'deleteLogo'])->name('dashboardClientPDF.deleteLogo');
    //Video
    Route::delete('/dashboardClientPDF/deleteVideo/{index}', [DashboardClient::class, 'deleteVideo'])->name('dashboardClientPDF.deleteVideo');
    //Slider
    Route::post('/dashboardClientPDF/uploadSlider', [DashboardClient::class, 'uploadSlider'])->name('dashboardClientPDF.uploadSlider');
    Route::get('/dashboardClientPDF/afficherSlider', [DashboardClient::class, 'afficherSlider'])->name('dashboardClientPDF.afficherSlider');
    Route::delete('/dashboardClientPDF/deleteSliderImage/{filename}', [DashboardClient::class, 'deleteSliderImage'])->name('dashboardClientPDF.deleteSliderImage');

    //Route de tom
    Route::post('/updateTemplate', [DashboardClient::class, 'updateTemplate'])->name('updateTemplate');
    Route::post('/dashboardClientInfo', [DashboardClient::class, 'updateInfo'])->name('dashboardClientInfo');

    //Custom Link
    Route::post('/dashboardClientCustomLink', [DashboardClient::class, 'updateCustomLink'])->name('dashboardClientCustomLink');

    //color
    Route::post('/dashboardClientColor', [DashboardClient::class, 'updateColor'])->name('dashboardClientColor');

    //QrCode download (downloadQrCodes / downloadQrCodesColor )
    Route::get('/downloadQrCodes', [DashboardClient::class, 'downloadQrCodes'])->name('downloadQrCodes');
    Route::get('/downloadQrCodesColor', [DashboardClient::class, 'downloadQrCodesColor'])->name('downloadQrCodesColor');

    // Entreprise
    Route::delete('/entreprise/{id}', [Entreprise::class, 'destroy'])->name('entreprise.destroy');

    // EmployeinscriptionEmp
    Route::get('/inscriptionEmp', [Employe::class, 'afficherFormulaireInscEmpl'])->name('afficherFormInsEmploye');
    Route::post('/inscriptionEmploye', [Employe::class, 'boutonInscriptionEmploye'])->name('inscriptionEmploye.post');
    Route::delete('/employe/{id}', [DashboardClient::class, 'destroy'])->name('employe.destroy');
    Route::post('/inscriptionEmp', [Employe::class, 'boutonInscriptionEmploye'])->name('validationFormulaireInscriptionEmploye');

    Route::get('/employe/{id}/edit', [Employe::class, 'edit'])->name('employe.edit');
    Route::put('/employe/{id}', [Employe::class, 'update'])->name('employe.update');


    Route::delete('/employe/{id}', [DashboardClient::class, 'destroy'])->name('employe.destroy');
    Route::post('/employe', [Inscription::class, 'boutonInscription'])->name('validationFormulaireInscription');
});
