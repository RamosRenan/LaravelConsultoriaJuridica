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




   /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        try {
            //code... 
            Key_words::where('id', '=', $request->id)->delete();
            return redirect()->action('LegalAdvice\keyWordsController@index')->with('status', 200);
        } catch (Exception $e) {
            return redirect()->action('LegalAdvice\keyWordsController@index')->with('status', $e->getMessage());
        }
    }




    /**
     * @param Request $request
     */
    public function show(Request $request)
    {
    }



    
    public function edit()
    {
        return "oooo";
    }




    /**
     * Delete all selected Specialties at once.
     *
     * @param Request $request
     */
        public function massDestroy(Request $request)
        {
            if ($request->input('ids')) {

                $entries = Key_words::whereIn('id', $request->input('ids'))->get();
    
                foreach ($entries as $entry) {
                    $entry->delete();
                }
                return redirect()->route('legaladvice.keywords.index')->with('success', __('global.app_msg_mass_destroy_success'));
            } else {
                return redirect()->route('legaladvice.keywords.index')->with('error', __('global.app_msg_mass_destroy_error'));
            }
        }
}//final class



