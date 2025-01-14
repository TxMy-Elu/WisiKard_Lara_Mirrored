<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory;

    // Définir la table associée au modèle
    protected $table = 'social';

    // Définir les champs qui peuvent être remplis massivement
    protected $fillable = [
        'nom',
        'lienLogo'
    ];

    // Définir les relations
    public function redirigers()
    {
        return $this->hasMany(Rediriger::class, 'idSocial', 'idSocial');
    }
}
