<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Txt extends Model
{
    use HasFactory;

    protected $table = 'txt';
    protected $primaryKey = 'id_txt';
    protected $fillable = ['num_txt', 'categorie', 'id_guide', 'txt'];

    public function guide()
    {
        return $this->belongsTo(Guide::class, 'id_guide');
    }
}
