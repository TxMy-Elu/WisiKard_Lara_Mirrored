<?php
// app/Models/Template.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'template';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'idTemplate';

    // Specify the attributes that are mass assignable
    protected $fillable = ['nom'];

    // Disable timestamps if your table doesn't have 'created_at' and 'updated_at' columns
    public $timestamps = false;
}