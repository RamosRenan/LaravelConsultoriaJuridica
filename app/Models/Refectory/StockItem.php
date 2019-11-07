<?php

namespace App\Models\Refectory;

use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $connection = 'refectory';

    protected $fillable = ['contract_id', 'supply_id', 'lot', 'expiration', 'quantity', 'price'];
}
