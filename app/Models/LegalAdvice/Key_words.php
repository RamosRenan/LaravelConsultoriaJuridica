<?php

namespace App\Models\LegalAdvice;

use Illuminate\Database\Eloquent\Model;

class Key_words extends Model
{
    // code ...
    protected $connection = "legaladvice";
    protected $fillable   = ["id", "name", "created_at", "updated_at"];
}
