<?php

namespace App\Models\LegalAdvice;

use Illuminate\Database\Eloquent\Model;

class note extends Model
{
    //code ...
    protected $connection = 'legaladvice';
    protected $fillable = ['id', 'document_type', 'registries_id', 'date_in', 'inserted_by', 'contain'];

}
