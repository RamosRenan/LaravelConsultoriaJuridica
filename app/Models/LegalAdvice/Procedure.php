<?php

namespace App\Models\LegalAdvice;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $connection = 'legaladvice';

    protected $fillable = ['registry_id', 'document_type', 'document_number', 'source', 'date', 'subject', 'files'];
}
