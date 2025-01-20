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
    public function afficherFormulaireInscEmpl()
    {
        return view('formulaire.formulaireInscriptionEmploye');
    }

    public function boutonInscriptionEmploye(Request $request)
    {
        $validationFormulaire = true; // Booléen qui indique si les données du Formulaire sont valides
        $messagesErreur = array(); // Tableau contenant les messages d'erreur à afficher

        if ($validationFormulaire === false) {
            return view('formulaire.formulaireEmploye', ["messagesErreur" => $messagesErreur]);
        } else {

            // Récupérer l'id du compte connecté
            $idCompte = session('connexion');

            // Recueperer les informations de la carte
            $carte = Carte::where('idCompte', $idCompte)->first();

            // Récupérer l'id de la carte
            $idCarte = $carte->idCarte;


            $nom = $request->input('nom');
            $prenom = $request->input('prenom');
            $email = $request->input('email');
            $tel = $request->input('tel');
            $fonction = $request->input('fonction');

            $employer = new Employer();
            $employer->nom = $nom;
            $employer->prenom = $prenom;
            $employer->mail = $email;
            $employer->telephone = $tel;
            $employer->fonction = $fonction;
            $employer->idCarte = $idCarte;
            $employer->save();

            $this->QrCode($idCompte, $carte->nomEntreprise, $employer->idEmp);

            return redirect()->route('dashboardClientEmploye')->with('success', 'Employé inscrit avec succès !');
        }
    }

    public function QrCode($id, $entreprise, $idEmp)
    {
        //concatenation de id et IdEmploye
        $code = $id . $idEmp;

        var_dump($code);

        $url = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&format=svg&text=127.0.0.1:9000/Templates?CompteEmp=" . $code;

        $ch = curl_init();

        // Configurer les options cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

        // Exécuter la requête cURL et obtenir le contenu
        $content = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Erreur cURL : ' . curl_error($ch);
        } else {
            // Fermer la session cURL
            curl_close($ch);

            // Chemin où enregistrer le fichier PNG
            $directoryPath = public_path("entreprises/{$id}_{$entreprise}/QR_Codes");
            $pngFilePath = "{$directoryPath}/QR_Code_{$idEmp}.svg";

            // Créer le répertoire s'il n'existe pas
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }

            // Enregistrer le contenu dans un fichier PNG
            file_put_contents($pngFilePath, $content);
        }
    }

}
