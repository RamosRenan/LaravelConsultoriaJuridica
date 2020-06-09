<?php

namespace App\Models\Dentist;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['name'];
}
