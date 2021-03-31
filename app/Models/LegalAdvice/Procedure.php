<?php

namespace App\Models\LegalAdvice;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $connection = 'legaladvice';

    protected $fillable = ['registry_id', 'document_type', 'document_number', 'source', 'date', 'subject', 'files'];

    public static function relatorioProcedure($date_i, $date_f){
        $relatorioProcedure = DB::select('SELECT name, 
        COALESCE(COUNT(registries.document_type), 0) as "total"
        FROM doctypes
        LEFT JOIN registries ON registries.document_type = CAST(doctypes.order AS INTEGER)
        WHERE registries.created_at BETWEEN \''.$date_i.'\' AND \''.$date_f.
        '\' GROUP BY public.doctypes.name');

        return $relatorioProcedure;
    }
}
