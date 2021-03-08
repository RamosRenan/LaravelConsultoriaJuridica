<?php

namespace App\Models\LegalAdvice;

use Illuminate\Database\Eloquent\Model;

class Registry extends Model
{
    protected $connection = 'legaladvice';

    protected $fillable = ['protocol', 'document_type', 'document_number', 'email', 'source', 'status', 'priority', 'place', 'interested', 'date_in', 'deadline', 'date_out', 'date_return', 'subject', 'urgent', 'key_words'];

    public static function note(){
        return $this->hasMany('App\Models\LegalAdvice', 'note');
    }

}
