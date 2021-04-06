<?php

namespace App\Models\LegalAdvice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;




class Protocol_kw extends Model
{
    // filds

    protected $table = 'protocolo_kw';

    protected $fillable = ['id', 'id_protocolo', 'id_keyword', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';
}
