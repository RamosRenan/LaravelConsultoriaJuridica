<?php

namespace App\Models\LegalAdvice;

use Illuminate\Database\Eloquent\Model;

class Key_words extends Model
{
    // code ...
    protected $connection = "legaladvice";
    protected $fillable   = ["name", "created_at", "updated_at"];
    public $timestamps = true; 
}
