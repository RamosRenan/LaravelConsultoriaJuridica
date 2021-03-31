<?php

namespace App\Models\LegalAdvice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class Priority extends Model
{
    protected $connection = 'legaladvice';

    protected $fillable = ['name', 'order'];

        public static function relatorioPriority($date_i, $date_f){
            $relatorioPriority = DB::select('SELECT name, 
            COALESCE(COUNT(registries.priority), 0) as "total"
            FROM priorities
            LEFT JOIN registries ON registries.priority = CAST(priorities.order AS INTEGER)
            WHERE registries.created_at BETWEEN \''.$date_i.'\' AND \''.$date_f.
            '\' GROUP BY public.priorities.name');

            return $relatorioPriority;
        }
}
