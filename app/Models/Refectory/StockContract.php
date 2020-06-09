<?php

namespace App\Models\Refectory;

use Illuminate\Database\Eloquent\Model;

class StockContract extends Model
{
    protected $connection = 'refectory';

    protected $fillable = ['unit_id', 'date', 'contract'];
}
