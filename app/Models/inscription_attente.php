<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription_attente extends Model
{
    protected $table = 'inscript_attente';
    protected $primaryKey = 'id_inscripAttente';
    public $timestamps = false;

    protected $fillable = [
        'nom_entre',
        'mail',
        'mdp',
        'role',
        'activer',
    ];

    public static function existeEmail($email)
    {
        $nb = self::where("email", $email)->count();

        if ($nb > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function inscription_attente($email, $motDePasseHache, $role, $nomEntreprise)
    {
        date_default_timezone_get();
        $date = date('Y/m/d');

        $nouvelInscrit_attente = new inscript_attente();
        $nouvelInscrit_attente->nom_entre = $nomEntreprise;
        $nouvelInscrit_attente->mail = $email;
        $nouvelInscrit_attente->mdp = $motDePasseHache;
        $nouvelInscrit_attente->role = $role;
        $nouvelInscrit_attente->date_inscription = $date;
        $nouvelInscrit_attente->save();

        return $nouvelInscrit_attente->id_inscripAttente;
    }
}
