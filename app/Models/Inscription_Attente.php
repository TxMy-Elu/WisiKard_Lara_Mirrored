<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use App\Models\Compte;
use App\Models\Carte;
use App\Models\Logs;

class Inscription_Attente extends Model
{
    protected $table = 'Inscription_Attente';
    protected $primaryKey = 'id_inscripAttente';
    public $timestamps = false;
    public $incrementing = true;

}