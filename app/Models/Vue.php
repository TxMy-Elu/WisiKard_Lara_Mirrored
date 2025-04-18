<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vue extends Model
{
    protected $table = 'vue';
    protected $primaryKey = 'idVue';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'date',
        'idCarte',
        'idEmp',
        'ip_address'
    ];

    public function carte(): BelongsTo
    {
        return $this->belongsTo(Carte::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }
}
