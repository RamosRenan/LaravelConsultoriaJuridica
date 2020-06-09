<?php

namespace App\Models\Refectory;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $connection = 'refectory';

    protected $fillable = ['name', 'description'];
}
