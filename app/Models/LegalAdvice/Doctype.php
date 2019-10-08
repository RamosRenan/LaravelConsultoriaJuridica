<?php

namespace App\Models\LegalAdvice;

use Illuminate\Database\Eloquent\Model;

class Doctype extends Model
{
    protected $connection = 'legaladvice';

    protected $fillable = ['name', 'order'];
}
