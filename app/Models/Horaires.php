<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horaires extends Model
{
    use HasFactory;

    protected $table = 'horaires';
    protected $fillable = [
        'idCarte',
        'jour',
        'ouverture_matin',
        'fermeture_matin',
        'ouverture_aprmidi',
        'fermeture_aprmidi'
    ];

    public function carte()
    {
        return $this->belongsTo(Carte::class, 'idCarte');
    }
}
