<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Vue;

class Vue extends Model
{
    protected $table = 'vue';
    protected $primaryKey = 'idVue';
    public $timestamps = false;
    public $incrementing = true;

    public function carte(): BelongsTo
    {
        return $this->belongsTo(Carte::class, 'idCarte', 'idCarte');
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class, 'idEmp', 'idEmp');
    }
}
