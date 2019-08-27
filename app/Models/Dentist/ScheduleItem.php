<?php

namespace App\Models\Dentist;

use Illuminate\Database\Eloquent\Model;

class ScheduleItem extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['name', 'description'];
}
