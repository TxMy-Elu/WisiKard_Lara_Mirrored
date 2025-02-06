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

        //creation de la vcard
        Compte::creerVCard($entreprise->nomEntreprise, $entreprise->tel, $nouvelUtilisateur->email, $nouvelUtilisateur->idCompte);


        return $nouvelUtilisateur->idCompte;
    }

    public static function creerVCard($nomEntreprise, $tel, $email, $idCompte)
    {
        // Valider ou échapper les entrées pour éviter toute erreur dans la vCard
        $nomEntreprise = htmlspecialchars($nomEntreprise, ENT_QUOTES, 'UTF-8');
        $tel = htmlspecialchars($tel, ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

        // Créer le contenu valide de la vCard
        $vCard = "BEGIN:VCARD\r\n";
        $vCard .= "VERSION:4.0\r\n";
        $vCard .= "FN:{$nomEntreprise}\r\n"; // Nom complet
        $vCard .= "TEL;TYPE=HOME,VOICE:{$tel}\r\n"; // Numéro de téléphone
        $vCard .= "EMAIL;TYPE=HOME,INTERNET:{$email}\r\n"; // Adresse email
        $vCard .= "END:VCARD\r\n";

        // Définir le chemin complet pour enregistrer le fichier
        $directoryPath = public_path("entreprises/{$idCompte}_{$nomEntreprise}/VCF_Files");
        $filePath = "{$directoryPath}/contact.vcf";

        // Créer les répertoires manquants avec les permissions appropriées
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true); // Crée les dossiers de façon récursive
        }

        // Enregistrer le contenu dans le fichier .vcf
        file_put_contents($filePath, $vCard);

        // Retourner le chemin final du fichier créé
        return "vCard créée avec succès : {$filePath}";
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

    public static function QrCode($id, $entreprise)
    {

        $color1 = Carte::where('idCompte', $id)->first()->couleur1;
        $color2 = Carte::where('idCompte', $id)->first()->couleur2;

        //enleve le # pour le code couleur
        $color1 = substr($color1, 1);
        $color2 = substr($color2, 1);

        $url = "https://quickchart.io/qr?size=300&dark=" . $color1 . "&light=" . $color2 . "&format=svg&text=https://app.wisikard.fr/Templates?idCompte=" . $id;

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