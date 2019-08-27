<?php

namespace App\Models\Dentist;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['name', 'description'];
}
