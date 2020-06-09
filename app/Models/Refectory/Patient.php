<?php

namespace App\Models\Dentist;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['name', 'rg', 'cpf'];
}
