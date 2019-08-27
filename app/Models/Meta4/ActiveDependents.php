<?php

namespace App\Models\Meta4;

use Illuminate\Database\Eloquent\Model;

class ActiveDependents extends Model
{
    protected $connection = 'oracle';

    protected $table = 'V_M4CBR_VW_DEP_CAD_PM_AT';
}
