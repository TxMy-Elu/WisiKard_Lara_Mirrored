<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carte extends Model
{
    protected $table = 'carte';
    protected $primaryKey = 'idCarte';
    public $timestamps = false;
    public $incrementing = true;

    use HasFactory;

    protected $fillable = [
        'nomEntreprise',
        'titre',
        'tel',
        'ville',
        'idCompte',
        'idTemplate',
        'afficher_email',
    ];
    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class, 'idCompte');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function employeurs(): HasMany
    {
        return $this->hasMany(Employer::class);
    }

    public function vues(): HasMany
    {
        return $this->hasMany(Vue::class);
    }

    public function redirigers(): HasMany
    {
        return $this->hasMany(Rediriger::class);
    }
    
    public function horaires()
    {
        return $this->hasMany(Horaires::class, 'idCarte');
    }
    
    public static function existeNomEntreprise($nomEntreprise)
    {
        return self::where('nomEntreprise', $nomEntreprise)->exists();
    }
}
