<?php

namespace App\Models\Meta4;

use Illuminate\Database\Eloquent\Model;

class HRPrivateFunction extends Model
{
    protected $connection = 'oracle';

    protected $table = 'V_M4CBR_VW_RH_PM_FUNC_PRIV';
}
