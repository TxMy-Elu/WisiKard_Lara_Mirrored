<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carte extends Model
{

    protected $table = 'carte';
    protected $primaryKey = 'idCarte';
    public $timestamps = false;
    public $incrementing = true;

    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class, 'idCompte', 'idCompte');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'idTemplate', 'idTemplate');
    }

    public function employeurs(): HasMany
    {
        return $this->hasMany(Employer::class, 'idCarte', 'idCarte');
    }

    public function vues(): HasMany
    {
        return $this->hasMany(Vue::class, 'idCarte', 'idCarte');
    }

    public function redirigers(): HasMany
    {
        return $this->hasMany(Rediriger::class, 'idCarte', 'idCarte');
    }

}
