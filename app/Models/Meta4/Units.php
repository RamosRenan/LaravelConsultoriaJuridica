<?php

namespace App\Models\Meta4;

use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    protected $connection = 'oracle';

    protected $table = 'V_M4CBR_VW_UNIDADE_ORGANOGRAMA';
}
