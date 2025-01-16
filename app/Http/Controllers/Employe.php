<?php
namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use App\Models\Employer;
use App\Models\Carte;
use Illuminate\Support\Facades\DB; // pour role
use Illuminate\Http\Request;

class Employe extends Controller
{
    public function afficherFormulaireInscEmpl(Request $request)
    {
        // Récupérer l'ID de l'utilisateur connecté
        $idCompte = session('connexion');

        // Récupérer l'idCarte associé au compte connecté
        $idCarte = Carte::where('idCompte', $idCompte)->first()->idCarte;

        return view('formulaire.formulaireEmploye', ["idCarte" => $idCarte]);
    }

    public function boutonInscriptionEmploye(Request $request)
    {
        if (isset($_POST["boutonInscriptionEmploye"])) {
            $validationFormulaire = true; // Booléen qui indique si les données du Formulaire sont valides
            $messagesErreur = array(); // Tableau contenant les messages d'erreur à afficher

            if ($validationFormulaire === false) {
                return view('formulaire.formulaireEmploye', ["messagesErreur" => $messagesErreur]);
            } else {
                // Créez un nouvel employé
                $employe = new Employer();
                $employe->nom = $request->input('nom');
                $employe->prenom = $request->input('prenom');
                $employe->fonction = $request->input('fonction');
                $employe->mail = $request->input('email');
                $employe->telephone = $request->input('telephone');
                $employe->lienQr = "/entreprises/{$nouvelUtilisateur->idCompte}_{$nomEntreprise}/QR_Codes/QR_Code_{$idEmp}.svg";

                $session = session('connexion');
                $employe->idCarte = Carte::where('idCompte', $session)->first()->idCarte;

                $employe->save();
                Employer::QrCode($nouvelUtilisateur->idCompte, $nomEntreprise, $idEmp);

                // Récupérer l'ID de l'employé nouvellement créé
                $idEmp = $employe->idEmp;

                // Appeler la méthode QrCodeEmploye avec les bons paramètres
                $employe->QrCodeEmploye($session, $employe->carte->nomEntreprise, $idEmp);

                // Récupérer l'email du compte pour les logs
                $compte = Compte::find($session);
                if ($compte) {
                    $emailUtilisateur = $compte->email;
                    // Écrire dans les logs
                    Logs::ecrireLog($emailUtilisateur, "Inscription Employe");
                }

                return redirect()->back()->with('success', 'Employé inscrit avec succès !');
            }
        }
    }
    public function QrCodeEmploye($id, $entreprise, $idEmp)
    {
        $url = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&&format=svg&text=127.0.0.1:9000/Templates?idEmp=" . $idEmp;

        $ch = curl_init();

        // Configurer les options cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

        // Exécuter la requête cURL et obtenir le contenu
        $content = curl_exec($ch);

        if (curl_errno($ch)) {
            Log::error('Erreur cURL : ' . curl_error($ch));
        } else {
            // Fermer la session cURL
            curl_close($ch);

            // Chemin où enregistrer le fichier SVG
            $directoryPath = public_path("entreprises/{$id}_{$entreprise}/QR_Codes");
            $svgFilePath = "{$directoryPath}/QR_Code_{$idEmp}.svg";

            // Créer le répertoire s'il n'existe pas
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }

            // Enregistrer le contenu dans un fichier SVG
            file_put_contents($svgFilePath, $content);

            Log::info("QR Code généré et enregistré à : $svgFilePath");
        }
    }
}
