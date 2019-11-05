<?php

namespace App\Models\Refectory;

use Illuminate\Database\Eloquent\Model;

class EmployeeHasUnit extends Model
{
    protected $connection = 'refectory';

    protected $fillable = ['unit_id', 'employee_id'];

    public $timestamps = false;
}
