<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory;

    protected $table = 'guide';
    protected $primaryKey = 'id_guide';
    protected $fillable = ['titre'];

    public function txts()
    {
        return $this->hasMany(Txt::class, 'id_guide');
    }
}
