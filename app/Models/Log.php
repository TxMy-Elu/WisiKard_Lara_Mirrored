<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Compte;

class Log extends Model
{
    protected $table = 'log';
    protected $primaryKey = 'idLog';
    public $timestamps = false;

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }

    public static function ecrireLog($emailUtilisateur, $typeAction) {
        // A FAIRE (fiche 2, partie 1, question 6) : écriture dans les logs

        /*
        $adresseIP = "a completer";
        $log = new Log();
        $log->typeActionLog = "a completer";
        $log->adresseIPLog = $adresseIP;
        $log->idUtilisateur = "a completer";
        $log->save();
        */

        // CORRIGÉ
        $adresseIP = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $adresseIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        $log = new Log();
        $log->typeActionLog = $typeAction;
        $log->adresseIPLog = $adresseIP;
        $log->idUtilisateur = Compte::where("email", $emailUtilisateur)->first()->idUtilisateur;
        $log->save();
    }
}