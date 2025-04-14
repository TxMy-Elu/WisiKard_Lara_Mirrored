<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use App\Models\Compte;
use App\Models\Carte;
use App\Models\Logs;

class Employer extends Model
{
    protected $table = 'employer';
    protected $primaryKey = 'idEmp';
    public $timestamps = false;
    public $incrementing = true;

    public function carte(): BelongsTo
    {
        return $this->belongsTo(Carte::class, 'idCarte');
    }

    public function vues(): HasMany
    {
        return $this->hasMany(Vue::class, 'idEmp');
    }

    public function QrCodeEmploye($id, $entreprise, $idEmp)
    {
        // Vérification des paramètres
        if (!$id || !$entreprise || !$idEmp) {
            Log::error("Paramètres invalides : id = {$id}, entreprise = {$entreprise}, idEmp = {$idEmp}");
            return false; // ou retournez une réponse adaptée
        }

        // Récupération de la carte associée au compte
        $carte = Carte::where('idCompte', $id)->first();

        // Construction de l'URL du QR code
        $url = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&format=svg";
        $params = [
            'text' => "https://app.wisikard.fr/Kard/{$entreprise}?Emp={$id}x{$idEmp}"
        ];
        $url = $url . '&' . http_build_query($params);

        // Ajout d'un log pour voir l'URL générée
        Log::info("Tentative de génération de QR Code via URL : $url");
        // Initialisation de cURL
        $ch = curl_init();
        // Configuration de cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Désactiver la vérification SSL (selon vos besoins)
        // Exécution de la requête
        $content = curl_exec($ch);
        // Gestion des erreurs
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            Log::error('Erreur cURL : ' . $error_msg. " pour l'URL : $url");
            curl_close($ch);
            return false; // Retournez une réponse adaptée à l'erreur
        } else {
            // Fermeture de la session cURL
            curl_close($ch);
            // Définir le chemin où sauvegarder le QR code
            $directoryPath = public_path("entreprises/{$id}/QR_Codes");
            $svgFilePath = "{$directoryPath}/QR_Code_{$idEmp}.svg";
            // Vérification et création du répertoire si nécessaire
            if (!file_exists($directoryPath)) {
                if (!mkdir($directoryPath, 0755, true) && !is_dir($directoryPath)) {
                    Log::error("Impossible de créer le répertoire : {$directoryPath}");
                    return false; // ou retournez une réponse adaptée
                }
            }
            // Enregistrement du QR Code dans un fichier SVG
            if (file_put_contents($svgFilePath, $content) === false) {
                Log::error("Erreur lors de l'enregistrement du QR Code à : {$svgFilePath}");
                return false; // ou retournez une réponse adaptée
            }
            // Log pour succès
            Log::info("QR Code généré et enregistré à : {$svgFilePath}");
            return $svgFilePath; // Retourne le chemin du fichier SVG
        }
    }
}
