<?php

namespace App\Http\Controllers\LegalAdvice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Models\LegalAdvice\Registry;
use App\Models\LegalAdvice\Place;     // lugares
use App\Models\LegalAdvice\Status;    // Respectivo a situações
use App\Models\LegalAdvice\Procedure; // Tipos de docs
use App\Models\LegalAdvice\Priority;  // prioridades




class RelatorioProtocoloCadastradoController extends Controller
{
    //

    /**
     * @return Response
     */
    public function index(){
        $idForaCj       = Place::where('name', 'like', 'Remetido a local fora da CJ%')->get();
        $idArquivado    = Place::where('name', 'like', 'Arquivado%')->get();
        $total          = Registry::all()->count();
        $foraCJ         = Registry::where('place', '=', $idForaCj[0]->id)->count();
        $arquivados     = Registry::where('place', '=', $idArquivado[0]->id)->count();

        return view('legaladvice.relatorio.index', compact('total', 'foraCJ', 'arquivados'));
    }



    /**
     * @return Response
     */
    public function show(Request $request){
        if($request != null && isset($request)){
            $request->validate([
                'date_i'=>'required|date|max:35',
                'date_f'=>'required|date|max:35',
                'time_i'=>'required|regex:/([0-9])([0-9]):([0-9])([0-9])/i|max:7',
                'time_f'=>'required|regex:/([0-9])([0-9]):([0-9])([0-9])/i|max:7',
            ]);
        }

        $date_i = str_replace('/', '-', $request->input('date_i'))." ".$request->input('time_i').':00';
        $date_f = str_replace('/', '-', $request->input('date_f'))." ".$request->input('time_f').':00';

        $place      = Place::relatorioPlace($date_i, $date_f);
        $status     = Status::relatorioStatus($date_i, $date_f);
        $procedure  = Procedure::relatorioProcedure($date_i, $date_f);
        $priority   = Priority::relatorioPriority($date_i, $date_f);

        //return $request;
    return view('legaladvice.relatorio.index', compact('priority', 'procedure', 'status', 'place'));
    }
}
