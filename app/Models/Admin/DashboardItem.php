<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class DashboardItem extends Model
{
    protected $fillable = ['user_id', 'dashboard_id', 'order'];
}
