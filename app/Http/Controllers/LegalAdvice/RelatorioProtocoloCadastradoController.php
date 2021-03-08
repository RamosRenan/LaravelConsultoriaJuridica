<?php

namespace App\Http\Controllers\LegalAdvice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;





class RelatorioProtocoloCadastradoController extends Controller
{
    //

    /**
     * @return Response
     */
    public function index(){
        return view('legaladvice.relatorio.index');
    }
}
