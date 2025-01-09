<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function cartes(): HasMany
    {
        return $this->hasMany(Carte::class );
    }

    public static function existeEmail($email) {
        $nb = self::where("email", $email)->count();

        if($nb > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function estAdmin() {
        $role = self::where("role", "admin")->count();

        if ($role > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function desactiverCompte() {
        $this->estDesactiveUtilisateur = 1;
        $this->save();
    }

    public function reactiverCompte() {
        $this->estDesactiveUtilisateur = 0;
        $this->tentativesEchoueesUtilisateur = 0;
        $this->save();
    }

    public static function inscription($email, $motDePasseHache, $role) {
        $nouvelUtilisateur = new Compte();
        $nouvelUtilisateur->email = $email;
        $nouvelUtilisateur->password = $motDePasseHache;
        $nouvelUtilisateur->role = $role;

        $nouvelUtilisateur->save();
    }

}