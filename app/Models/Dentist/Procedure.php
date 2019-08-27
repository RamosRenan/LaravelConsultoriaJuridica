<?php

namespace App\Models\Dentist;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['name'];

    public function getPriceAttribute($value)
    {
        return $value ? number_format($value, 2, ',', ' ') : '---';
    }

    public function getDateAttribute($value)
    {
        return $value ? date("d/m/Y", strtotime($value)) : '---';
    }
}
