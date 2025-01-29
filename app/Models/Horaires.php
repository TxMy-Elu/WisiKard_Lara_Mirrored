<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horaires extends Model
{
    protected $table = 'horaires';
    protected $fillable = [
        'idCarte',
        'jour',
        'ouverture_matin',
        'fermeture_matin',
        'ouverture_aprmidi',
        'fermeture_aprmidi'
    ];

    public $timestamps = false;

    public function carte()
    {
        return $this->belongsTo(Carte::class, 'idCarte');
    }
}
