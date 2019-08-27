<?php

namespace App\Models\Dentist;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['name', 'code'];
}
