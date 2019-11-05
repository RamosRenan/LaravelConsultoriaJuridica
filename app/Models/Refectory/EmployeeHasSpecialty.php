<?php

namespace App\Models\Refectory;

use Illuminate\Database\Eloquent\Model;

class EmployeeHasSpecialty extends Model
{
    protected $connection = 'refectory';

    protected $fillable = ['specialty_id', 'employee_id'];

    public $timestamps = false;
}