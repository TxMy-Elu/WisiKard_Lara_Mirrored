<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compte extends Model
{
    protected $table = 'compte';
    protected $primaryKey = 'idCompte';
    public $timestamps = false;

    public function logs(): HasMany
    {
        return $this->hasMany(Logs::class);
    }


    public static function existeEmail($email)
    {
        $nb = self::where("email", $email)->count();

        if ($nb > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function estAdmin()
    {
        return $this->role === 'admin';
    }

    public function desactiverCompte()
    {
        $this->estDesactiver = 1;
        $this->save();
    }

    public function reactiverCompte()
    {
        $this->estDesactiver = 0;
        $this->tentativesCo = 0;
        $this->save();
    }

    public static function inscription($email, $motDePasseHache, $role, $nomEntreprise)
    {
        $nouvelUtilisateur = new Compte();
        $nouvelUtilisateur->email = $email;
        $nouvelUtilisateur->password = $motDePasseHache;
        $nouvelUtilisateur->role = $role;
        $nouvelUtilisateur->save();

        $entreprise = new Carte();
        $entreprise->idCompte = $nouvelUtilisateur->idCompte;
        $entreprise->nomEntreprise = $nomEntreprise;
        $entreprise->titre = "titre";
        $entreprise->tel = "tel";
        $entreprise->ville = "ville";
        $entreprise->idTemplate = 1;
        $entreprise->couleur1 = "#000000";
        $entreprise->couleur2 = "#FFFFFF";
        $entreprise->lienQr = "/entreprises/{$nouvelUtilisateur->idCompte}_{$nomEntreprise}/QR_Codes/QR_Code.svg";
        $entreprise->save();

        Compte::QrCode($nouvelUtilisateur->idCompte, $nomEntreprise);



        return $nouvelUtilisateur->idCompte;
    }

    public static function employe($nom, $prenom, $fonction)
    {
        // Créez un nouvel employé et enregistrez-le dans la base de données
        $employe = new Employer();
        $employe->nom = $nom;
        $employe->prenom = $prenom;
        $employe->fonction = $fonction;
        $employe->save();

        return $employe;
    }

    public function QrCode($id, $entreprise)
    {
        $url = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&&format=svg&text=127.0.0.1:9000/Templates?idCompte=" . $id;

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
            $pngFilePath = "{$directoryPath}/QR_Code.svg";

            // Créer le répertoire s'il n'existe pas
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }

            // Enregistrer le contenu dans un fichier PNG
            file_put_contents($pngFilePath, $content);
        }
    }
}