<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Utilisateur;

class Reactivation extends Model
{
    protected $table = 'reactivation';
    protected $primaryKey = 'idReactivation';
    public $timestamps = false;

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }

    public function supprimerReactivation() {
        $this->destroy();
    }

    public static function existeCode($code) {
        $nb = self::where("codeReactivation", $code)->count();

        if($nb > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function estValide($code) {
        if(self::existeCode($code) === true) {
            $ligne = self::where("codeReactivation", $code)->first();
            $dateLimite = new \DateTime($ligne->dateHeureExpirationReactivation);
            $dateActuelle = new \DateTime("now");

            if($dateActuelle > $dateLimite) {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return false;
        }
    }

    public static function getUtilisateurConcerne($code) {
        return Compte::find(self::where("codeReactivation", $code)->first()->idCompte);
    }

    public static function creerCodeReactivation($compteUtilisateur) {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = substr(str_shuffle($caracteres), 0, 32);

        $nouvelleReactivation = new Reactivation();
        $nouvelleReactivation->codeReactivation = $code;
        $nouvelleReactivation->idCompte = $compteUtilisateur->idCompte;
        $nouvelleReactivation->save();

        return $code;
    }
}
