<?php

namespace App\Models\Refectory;

use Illuminate\Database\Eloquent\Model;

class ProcedureItem extends Model
{
    protected $connection = 'refectory';

    protected $fillable = ['procedure_id', 'price', 'date'];
}
