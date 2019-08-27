<?php

namespace App\Models\Dentist;

use Illuminate\Database\Eloquent\Model;

class SupplyItem extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['unit_id', 'supply_id', 'lot', 'quantity', 'price', 'date'];
}
