<?php

namespace App\Models\LegalAdvice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;




class protocol_kw extends Model
{
    // filds

    protected $table = 'protocol_kw';

    protected $fillable = ['id', 'id_protocolo', 'id_keyword', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';
}
