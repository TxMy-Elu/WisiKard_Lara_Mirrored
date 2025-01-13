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


    public static function existeEmail($email) {
        $nb = self::where("email", $email)->count();

        if($nb > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function estAdmin() {
        return $this->role === 'admin';
    }

    public function desactiverCompte() {
        $this->estDesactiver = 1;
        $this->save();
    }

    public function reactiverCompte() {
        $this->estDesactiver = 0;
        $this->tentativesCo = 0;
        $this->save();
    }

    public static function inscription($email, $motDePasseHache, $role) {
        $nouvelUtilisateur = new Compte();
        $nouvelUtilisateur->email = $email;
        $nouvelUtilisateur->password = $motDePasseHache;
        $nouvelUtilisateur->role = $role;

        $nouvelUtilisateur->save();

        return $nouvelUtilisateur->idCompte;
    }

}