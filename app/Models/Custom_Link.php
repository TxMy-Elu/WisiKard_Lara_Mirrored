<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Custom_Link extends Model
{
    protected $table = 'custom_link'; // Vérifiez cette définition !
    protected $primaryKey = 'id_link'; // Assurez-vous que cette colonne est correcte dans la table
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'lien',
        'activer',
        'idCarte'
    ];

    public function carte()
    {
        return $this->belongsTo(Carte::class, 'idCarte', 'idCarte');
    }
}