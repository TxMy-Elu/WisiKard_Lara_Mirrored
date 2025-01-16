<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Employer extends Model
{
    protected $table = 'employer';
    protected $primaryKey = 'idEmp';
    public $timestamps = false;
    public $incrementing = true;

    public function carte(): BelongsTo
    {
        return $this->belongsTo(Carte::class, 'idCarte');
    }

    public function vues(): HasMany
    {
        return $this->hasMany(Vue::class, 'idEmp');
    }


}
