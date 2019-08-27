<?php

namespace App\Models\Meta4;

use Illuminate\Database\Eloquent\Model;

class ActiveMembers extends Model
{
    protected $connection = 'oracle';

    protected $table = 'V_M4CBR_VW_EXT_PM_CADAST';
}
