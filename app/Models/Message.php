<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'message',
        'afficher'
    ];

    public static function recupererMessages()
    {
        return self::all();
    }

    public static function ajouterMessage($contenuMessage)
    {
        $message = new self();
        $message->message = $contenuMessage;
        $message->afficher = true;
        $message->save();
    }

    public function desactiverDernierMessage()
    {
        $dernierMessage = self::orderBy('id', 'desc')->first();
        $dernierMessage->afficher = false;
        $dernierMessage->save();
    }

}