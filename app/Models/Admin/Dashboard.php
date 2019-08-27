<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $fillable = ['title', 'name', 'role', 'grid'];
}
