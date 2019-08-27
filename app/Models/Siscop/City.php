<?php

namespace App\Models\Siscop;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $connection = 'siscop_production';

    protected $table = 'COPEL_MUNICIPIO';
}
