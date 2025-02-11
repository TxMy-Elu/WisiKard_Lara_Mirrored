<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Img extends Model
{
    use HasFactory;

    protected $table = 'img';
    protected $primaryKey = 'id_img';
    protected $fillable = ['num_img', 'categorie', 'id_guide'];

    public function guide()
    {
        return $this->belongsTo(Guide::class, 'id_guide');
    }
}
