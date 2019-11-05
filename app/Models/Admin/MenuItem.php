<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['parent_id', 'controller', 'order', 'menu_id', 'title', 'permission', 'url', 'route', 'icon', 'color'];
}
