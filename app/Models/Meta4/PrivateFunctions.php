<?php

namespace App\Models\Meta4;

use Illuminate\Database\Eloquent\Model;

class PrivateFunctions extends Model
{
    protected $connection = 'oracle';

    protected $table = 'V_M4CBR_VW_FUNCOES_PRIVATIVAS';
}
