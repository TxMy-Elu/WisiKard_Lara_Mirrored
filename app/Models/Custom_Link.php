<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Custom_Link extends Model
{
    protected $table = 'custom_link';
    protected $primaryKey = 'id_link';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'lien',
        'idCarte'
    ];

    public function carte()
    {
        return $this->belongsTo(Carte::class, 'idCarte');
    }
}