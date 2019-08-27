<?php

namespace App\Models\Dentist;

use Illuminate\Database\Eloquent\Model;

class DentistHasUnit extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['unit_id', 'dentist_id'];

    public $timestamps = false;
}
