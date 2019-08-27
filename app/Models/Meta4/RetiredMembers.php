<?php

namespace App\Models\Meta4;

use Illuminate\Database\Eloquent\Model;

class RetiredMembers extends Model
{
    protected $connection = 'oracle';
    protected $table = 'V_M4CBR_VW_CAD_PM_INAT';
}
