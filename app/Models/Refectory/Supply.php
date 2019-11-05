<?php

namespace App\Models\Refectory;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $connection = 'refectory';

    protected $fillable = ['name'];

    public function getPriceAttribute($value)
    {
        return $value ? number_format($value, 2, ',', ' ') : '---';
    }

    public function getDateAttribute($value)
    {
        return $value ? date("d/m/Y", strtotime($value)) : '---';
    }

    public function getQuantityAttribute($value)
    {
        return $value ? $value : '---';
    }
}
