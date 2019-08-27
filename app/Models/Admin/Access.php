<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['user_id', 'datetime', 'command'];
}
