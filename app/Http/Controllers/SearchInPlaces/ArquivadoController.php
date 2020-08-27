<?php

namespace App\Http\Controllers\SearchInPlaces;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class ArquivadoController extends Controller
{
    //code ...

    public $refererNumPlace;
    const refererNum = 4;

    function __construct(){
        $this->refererNumPlace = ArquivadoController::refererNum;
    }//__construct

    //
    public function index(Request $request){
        $call = new ArquivadoController;
        $search = @$_GET['search'];
        $items = $call->show($this->refererNumPlace);
        $arquivado = true;
        return view('legaladvice.registries.index', compact('items', 'search', 'arquivado'));
    }//index()

    private function show($id){

        $outOfCg = 
            DB::select( DB::raw("SELECT DISTINCT ON 
            (r.protocol, n.registries_id) r.protocol, r.id,  r.urgent, r.document_type, r.document_number, r.source, r.status, /* select tuplas registries */  
            r.priority, r.place, r.interested, r.date_in as r_date_in, r.deadline, r.date_out, r.date_return, r.subject as r_subject,  /* select tuplas registries */
            n.registries_id, n.created_at, n.contain, n.inserted_by, n.date_in,                     /* select tuplas  notes */
            pi.name, pi.order, pi.id as priority_id,                                                /* select tuplas priorities */
            po.registry_id, po.document_type, po.document_number, po.source, po.date, po.subject,   /* select tuplas procedures */
                    
            count(po.files) as qtd_procedures_files,             /* conta qtd de files   */
            count(fm) as qtd_file_managers                          /* conta qtd de files       */

            FROM registries r                                   /* da tabela registries */
            join priorities pi on pi.id=r.priority              /* junta com priorities */
            left join procedures po on po.registry_id=r.id      /* junta com procedures */
            left join file_managers fm on r.id=fm.route_id      /* junta com file_managers */
            left outer join notes n on n.registries_id=r.id     /* junta com notes, busca fora relação */

            where r.place=$id /* busca somente fora do cg = 4 */
            
            group by r.protocol, n.registries_id, r.id, n.created_at, n.contain, n.inserted_by, n.date_in, 
            pi.name, pi.order, priority_id, po.registry_id, /* agurpa r e n */
            po.document_type, po.document_number,  po.source, po.date, po.subject
            order by r.protocol, n.registries_id, n.created_at desc"));
        
        return $outOfCg;

    }//show()
}
