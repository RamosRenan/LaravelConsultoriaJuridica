<?php

namespace App\Models\LegalAdvice;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;




class Place extends Model
{
    protected $connection = 'legaladvice';

    protected $fillable = ['name', 'order'];


    public static function relatorioPlace($date_i, $date_f){
        $relatorioPlace = DB::select('SELECT name, 
        COALESCE(COUNT(registries.place), 0) as "total"
        FROM places
        LEFT JOIN registries ON registries.place = CAST(places.order AS INTEGER)
        WHERE registries.created_at BETWEEN \''.$date_i.'\' AND \''.$date_f.
        '\' GROUP BY public.places.name');


        return $relatorioPlace;
    }
}
