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

    public static function ecrireLog($emailUtilisateur, $typeAction) {
        // A FAIRE (fiche 2, partie 1, question 6) : écriture dans les logs

        // CORRIGÉ
        $adresseIP = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $adresseIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        $log = new Logs();
        $log->typeAction = $typeAction;
        $log->adresseIPLog = $adresseIP;
        $log->dateHeureLog = date("Y-m-d H:i:s");
        $log->idCompte = Compte::where("email", $emailUtilisateur)->first()->idCompte;
        $log->save();
    }
}