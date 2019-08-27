<?php

namespace App\Models\Dentist;

use Illuminate\Database\Eloquent\Model;

class DentistHasSpecialty extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['specialty_id', 'dentist_id'];

    public $timestamps = false;
}