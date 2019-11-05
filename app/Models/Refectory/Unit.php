<?php

namespace App\Models\Refectory;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $connection = 'refectory';

    protected $fillable = ['name', 'code'];
}
