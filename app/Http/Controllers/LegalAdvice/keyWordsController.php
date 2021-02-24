<?php

namespace App\Http\Controllers\LegalAdvice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LegalAdvice\Key_words;
use Illuminate\Support\Facades\DB;


class keyWordsController extends Controller
{
    /**
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $items = DB::table('key_words')->get();
        return view('/legaladvice/keywords/index', ['items'=>$items]);
    }




    /**
     * @return Illuminate\Http\Response
     */
    public function create()
    {
        return view('/legaladvice/keywords/create');
    }




    /**
     */
    public function store(Request $request)
    {
        try {
            //code...
            DB::table('key_words')->insertOrIgnore([
                'name'=>$request->input('name'),
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
        return self::index();
    }
}
