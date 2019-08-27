<?php

namespace App\Models\Dentist;

use Illuminate\Database\Eloquent\Model;

class ProcedureItem extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['procedure_id', 'price', 'date'];
}
