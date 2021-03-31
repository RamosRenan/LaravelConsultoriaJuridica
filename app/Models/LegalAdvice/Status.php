<?php

namespace App\Models\LegalAdvice;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $connection = 'legaladvice';

    protected $fillable = ['name', 'order'];

    public static function relatorioStatus($date_i, $date_f){
        $relatorioStatus = DB::select('SELECT name, 
        COALESCE(COUNT(registries.status), 0) as "total"
        FROM statuses
        LEFT JOIN registries ON registries.status = CAST(statuses.order AS INTEGER)
        WHERE registries.created_at BETWEEN \''.$date_i.'\' AND \''.$date_f.
        '\' GROUP BY public.statuses.name');


        return $relatorioStatus;
    }
}
