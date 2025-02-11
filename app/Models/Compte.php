<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

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
        //remplacer les espaces par des underscores
        $nomEntrepriseDir = str_replace(' ', '_', $nomEntreprise);
        Log::info("nomEntrepriseDir : {$nomEntrepriseDir}");
        $entreprise->nomEntreprise = $nomEntreprise;
        $entreprise->idTemplate = 1;
        $entreprise->couleur1 = "#000000";
        $entreprise->couleur2 = "#FFFFFF";
        $entreprise->lienQr = "/entreprises/{$nouvelUtilisateur->idCompte}_{$nomEntrepriseDir}/QR_Codes/QR_Code.svg";
        $entreprise->save();

        Compte::QrCode($nouvelUtilisateur->idCompte, $nomEntrepriseDir);

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

        $vCard = "BEGIN:VCARD\r\n";                 // Début de la vCard
        $vCard .= "VERSION:4.0\r\n";                // Version 4.0 compatible
        $vCard .= "FN:{$nomEntreprise}\r\n";        // Nom complet
        $vCard .= "TEL;TYPE=CELL,VOICE:{$tel}\r\n"; // Numéro de téléphone
        $vCard .= "EMAIL;TYPE=HOME,INTERNET:{$email}\r\n"; // Adresse email
        $vCard .= "END:VCARD\r\n";                  // Fin de la vCard

        // Remplacer les espaces par des underscores pour le nom du répertoire
        $nomEntrepriseDir = str_replace(' ', '_', $nomEntreprise);

        // Définir le chemin complet pour enregistrer le fichier
        $directoryPath = public_path("entreprises/{$idCompte}_{$nomEntrepriseDir}/VCF_Files");
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
        // Récupérer les couleurs à partir de la base de données
        $carte = Carte::where('idCompte', $id)->first();

        if (!$carte) {
            echo "Aucune carte trouvée pour l'idCompte : {$id}";
            return;
        }

        $color1 = $carte->couleur1; // Couleur 1 (exemple : #FF0000)
        $color2 = $carte->couleur2; // Couleur 2 (exemple : #FFFFFF)

        // Supprimer le symbole '#' pour formater les couleurs pour l'API
        $color1 = ltrim($color1, '#');
        $color2 = ltrim($color2, '#');

        //nom de l'entreprise
        $nomEntreprise = $carte->nomEntreprise;

        // Construire l'URL pour générer le QR Code depuis QuickChart.io
        $baseUrl = "https://quickchart.io/qr";
        $url = "{$baseUrl}?size=300&dark={$color1}&light={$color2}&format=svg&text=https://app.wisikard.fr/Kard/{$nomEntreprise}?idCompte={$id}";

        // Utiliser cURL pour effectuer une requête à l'API
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        // Retourner le contenu directement au lieu de l'afficher
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);        // Suivre les redirections HTTP, si nécessaire
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);       // Désactiver la vérification SSL (non recommandé pour la production)

        $content = curl_exec($ch);

        // Gestion des erreurs cURL
        if (curl_errno($ch)) {
            echo 'Erreur cURL : ' . curl_error($ch);
            curl_close($ch);
            return;
        }

        // Fermer la session cURL
        curl_close($ch);

        // Vérifier si le contenu est valide
        if (!$content) {
            echo "Aucun contenu retourné par l'API.";
            return;
        }

        // Construire le chemin d'enregistrement du fichier SVG
        $directoryPath = public_path("entreprises/{$id}_{$entreprise}/QR_Codes");
        $svgFilePath = "{$directoryPath}/QR_Code.svg";

        // Créer le répertoire s'il n'existe pas (en respectant la casse)
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true); // Création récursive avec permissions
        }

        // Enregistrer le contenu dans le fichier SVG
        if (file_put_contents($svgFilePath, $content) !== false) {
          Log::info("Fichier QR Code enregistré avec succès : {$svgFilePath}");
        } else {
            Log::error("Impossible d'enregistrer le fichier QR Code : {$svgFilePath}");
        }
    }
}