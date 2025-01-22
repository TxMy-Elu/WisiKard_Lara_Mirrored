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
        $code = $id ."x".$idEmp;


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

    public function edit($id)
    {
        $employe = Employer::findOrFail($id);
        return view('formulaire.formulaireModifEmploye', compact('employe'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tel' => 'required|string|max:20',
            'fonction' => 'required|string|max:255',
        ]);

        try {
            $employe = Employer::findOrFail($id);
            $employe->nom = $request->nom;
            $employe->prenom = $request->prenom;
            $employe->mail = $request->email;
            $employe->telephone = $request->tel;
            $employe->fonction = $request->fonction;
            $employe->save();

            // Log the modification
            $compte = Compte::find($employe->idCarte);
            if ($compte) {
                $emailUtilisateur = $compte->email;
                Logs::ecrireLog($emailUtilisateur, "Modification Employe");
            }

            return redirect()->route('dashboardClientEmploye')->with('success', 'L\'employé a été modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la modification de l\'employé.');
        }
    }

}
