<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Compte;

class Logs extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'idLog';
    public $timestamps = false;

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }

     public static function ecrireLog($emailUtilisateur, $typeAction)
       {
           // Récupérer l'adresse IP
           $adresseIP = $_SERVER['REMOTE_ADDR'];
           if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
               $adresseIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
           }

           // Vérifier si un compte existe pour l'adresse email fournie
           $compte = Compte::where("email", $emailUtilisateur)->first();
           if ($compte) {
               $log = new Logs();
               $log->typeAction = $typeAction;
               $log->adresseIPLog = $adresseIP;
               $log->dateHeureLog = date("Y-m-d H:i:s");
               $log->idCompte = $compte->idCompte;
               $log->save();
           } else {
               // Gérer le cas où aucun compte n'est trouvé
               \Log::error("Aucun compte trouvé pour l'adresse email : " . $emailUtilisateur);
           }
       }
}