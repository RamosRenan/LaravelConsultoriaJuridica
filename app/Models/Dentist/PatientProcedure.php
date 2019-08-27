<?php

namespace App\Models\Dentist;

use Illuminate\Database\Eloquent\Model;

class PatientProcedure extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['pacient_id', 'procedure_id', 'teeth', 'description', 'price'];
}
