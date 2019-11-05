<?php

namespace App\Models\Refectory;

use Illuminate\Database\Eloquent\Model;

class SupplyItem extends Model
{
    protected $connection = 'refectory';

    protected $fillable = ['unit_id', 'supply_id', 'lot', 'quantity', 'price', 'date'];
}
