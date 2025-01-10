<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rediriger extends Model
{
    use HasFactory;

    protected $table = 'rediriger';
    protected $primaryKey = ['idSocial', 'idCarte'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idSocial',
        'idCarte',
        'lien'
    ];

    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        return $this->getAttribute($keyName);
    }
}