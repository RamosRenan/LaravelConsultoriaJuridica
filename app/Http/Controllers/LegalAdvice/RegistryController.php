<?php
namespace App\Http\Controllers\LegalAdvice;

use App\Models\LegalAdvice\Doctype;
use App\Models\LegalAdvice\Protocol_kw;
use App\Models\LegalAdvice\Procedure;
use App\Models\LegalAdvice\Status;
use App\Models\LegalAdvice\Priority;
use App\Models\LegalAdvice\Place;
use App\Models\LegalAdvice\Registry;
use App\Models\LegalAdvice\note;
use App\Models\Admin\FileManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Validate\pregmatch;
use App\Http\Controllers\Validate\boolean;
use Illuminate\Support\Facades\Validator;
use App\Models\LegalAdvice\Key_words;




class RegistryController extends Controller {
    
    public function __construct() {
        $this->middleware('check.permissions');
    }




    /**
     * Display a listing of Procedures.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
         
        $search = @$_GET['search'];

        $res = isset($_GET['see'], $_GET['priority']) ? 'true' || true : false; if($res) return  $this->findPriority($_GET['priority']);
        
        $this->files = FileManager::getFiles()->pluck('route_id', 'id')->countBy();

        /*
         **********************************************
         * @ Código comentado abaixo - Modo 1
         * ********************************************
         * ° Versão que busca os protocolos.
         * ° Versão criada por 1° Ten Budal.
         * ° Comentado por Sd. Renan, DDTQ 23/06/2020.
         * --------------------------------------------
         */ 
        // $query = Registry::query();
        // $query->select('registries.*', 'priorities.name AS priority', 'priorities.id AS priority_id');
        // $query->join('priorities', 'priorities.id', '=', 'registries.priority');
        // $query->leftJoin('procedures', 'registries.id', '=', 'procedures.registry_id');
        // $query->selectRaw('(SELECT COUNT(id) FROM procedures WHERE registry_id = registries.id) AS procedures');
                
        // return $query->paginate(50);

        // $query->when(@$_GET['priority'], function ($q) {
        //     return $q->where('priority', $_GET['priority']);
        // });
        // $query->when(@$_GET['see'] == 'uptodate', function ($q) {
        //     return $q->whereRaw('EXTRACT( EPOCH FROM (deadline - now()) ) / 60 / 60 / 24 > 3');
        // });
        // $query->when(@$_GET['see'] == 'deadline', function ($q) {
        //     return $q->whereRaw('EXTRACT( EPOCH FROM (deadline - now()) ) / 60 / 60 / 24 <= 3 AND EXTRACT( EPOCH FROM (deadline - now()) ) / 60 / 60 / 24 >= 0');
        // });
        // $query->when(@$_GET['see'] == 'late', function ($q) {
        //     return $q->whereRaw('EXTRACT( EPOCH FROM (deadline - now()) ) < 0');
        // });
        // $query->where(function ($q){
        //     $search = @$_GET['search'];
        //     $q->orwhere('registries.protocol', 'ilike', '%'.$search.'%');
        //     $q->orWhere('registries.document_number', 'ilike', '%'.$search.'%');
        //     $q->orWhere('registries.source', 'ilike', '%'.$search.'%');
        //     $q->orWhere('registries.interested', 'ilike', '%'.$search.'%');
        //     $q->orWhere('registries.subject', 'ilike', '%'.$search.'%');
        //     $q->orWhere('procedures.document_number', 'ilike', '%'.$search.'%');
        //     $q->orWhere('procedures.source', 'ilike', '%'.$search.'%');
        //     $q->orWhere('procedures.subject', 'ilike', '%'.$search.'%');
        //     $q->orderBy('registries.deadline', 'ASC');
        // });

        // $query->distinct();

        // $this->maxSize = 200;

        // $items->each(function($item){
        //     $item->subject = substr($item->subject, 0, $this->maxSize) . (strlen($item->subject) > $this->maxSize ? '...' : '');
        //     $item->date_in = date("d/m/Y", strtotime($item->date_in));
        //     $item->deadline = date("d/m/Y", strtotime($item->deadline));
        //     $item->files = isset($this->files[$item->id]) ? $this->files[$item->id] : 0;
        //     $item->date_out = date("d/m/Y", strtotime($item->date_out));
        //     $item->date_return = date("d/m/Y", strtotime($item->date_return));
        // });


        /*
         **********************************************
         * @ Código comentado abaixo - Modo 2
         * ********************************************
         * ° Versão que busca os protocolos.
         * ° Versão criada por Sd. Renan.
         * ° Comentado por Sd. Renan, DDTQ 23/06/2020.
         * --------------------------------------------
         */ 
        $items = DB::select( DB::raw("SELECT DISTINCT ON (r.protocol, n.registries_id) r.protocol, r.id,  r.urgent, r.document_type, r.document_number, r.source, r.status, /* select tuplas registries */  
                        r.priority, r.place, r.interested, r.date_in as r_date_in, r.deadline, r.date_out, r.date_return, r.subject as r_subject,  /* select tuplas registries */
                        n.registries_id, n.created_at, n.contain, n.inserted_by, n.date_in,                     /* select tuplas  notes */
                        pi.name, pi.order, pi.id as priority_id,                                                /* select tuplas priorities */
                        po.registry_id, po.document_type, po.document_number, po.source, po.date, po.subject,   /* select tuplas procedures */
                        count(po.files) as qtd_procedures_files,             /* conta qtd de files   */
                        count(fm) as qtd_file_managers                      /* conta qtd de files   */
                        FROM registries r                                   /* da tabela registries */
                        join priorities pi on pi.id=r.priority              /* junta com priorities */
                        left join procedures po on po.registry_id=r.id      /* junta com procedures */
                        left join file_managers fm on r.id=fm.route_id      /* junta com file_managers */
                        left outer join notes n on n.registries_id=r.id     /* junta com notes, busca fora relação */
                        where r.protocol like '$search%' or r.interested like '%$search%' /* busca personalizada */
                        group by r.protocol, n.registries_id, r.id, n.created_at, n.contain, n.inserted_by, n.date_in, pi.name, pi.order, priority_id, po.registry_id, /* agrupa r e n */
                        po.document_type, po.document_number,  po.source, po.date, po.subject
                        order by r.protocol, n.registries_id, n.created_at desc") );    /* ordena e pega ultma atualizacao da tabela note */
                         
        // dd($items); 
	    
        return view('legaladvice.registries.index', compact('items', 'search'));

    }
    // index()


    public function findEprotocolEspecifc(){
        
    }


    public function findPriority($priority){

        $search = @$_GET['search'];

        $items = DB::select( DB::raw("SELECT DISTINCT ON (r.protocol, n.registries_id) r.protocol, r.id,  r.urgent, r.document_type, r.document_number, r.source, r.status, /* select tuplas registries */  
        r.priority, r.place, r.interested, r.date_in as r_date_in, r.deadline, r.date_out, r.date_return, r.subject as r_subject,  /* select tuplas registries */
        n.registries_id, n.created_at, n.contain, n.inserted_by, n.date_in,                     /* select tuplas  notes */
        pi.name, pi.order, pi.id as priority_id,                                                /* select tuplas priorities */
        po.registry_id, po.document_type, po.document_number, po.source, po.date, po.subject,   /* select tuplas procedures */

        count(po.files) as qtd_procedures_files,                /* conta qtd de files       */
        count(fm) as qtd_file_managers                          /* conta qtd de files       */
        FROM registries r                                       /* da tabela registries     */
        join priorities pi on pi.id=r.priority                  /* junta com priorities     */
        left join procedures po on po.registry_id=r.id          /* junta com procedures     */
        left join file_managers fm on r.id=fm.route_id          /* junta com file_managers  */
        left outer join notes n on n.registries_id=r.id         /* junta com notes, busca fora relação */
        where r.priority = $priority
        group by r.protocol, n.registries_id, r.id, n.created_at, n.contain, n.inserted_by, n.date_in, pi.name, pi.order, priority_id, po.registry_id, /* agrupa r e n */
        po.document_type, po.document_number,  po.source, po.date, po.subject
        order by r.protocol, n.registries_id, n.created_at desc") );    /* ordena e pega ultma atualizacao da tabela note */
         
        return view('legaladvice.registries.index', compact('items', 'search'));
    }

    /**
     * Display a listing of Procedures.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $form = $this->request = $request->all();

        $conditions = ['=' => '=', '>=' => '>', '<=' => '<'];

        if ($request['dosearch'] == true && !isset($this->request['key_words'])) {
            $items = Registry::select('*')
                ->selectRaw('(SELECT COUNT(id) FROM procedures WHERE registry_id = registries.id) AS procedures')
                ->where(function ($query) {
                    if (@$this->request['protocol'] <> null ) $query->where('protocol', 'ilike', '%'.$this->request['protocol'].'%');
                    if (@$this->request['document_type'] <> null ) $query->whereIn('document_type', $this->request['document_type']);
                    if (@$this->request['document_number'] <> null ) $query->where('document_number', 'ilike', '%'.$this->request['document_number'].'%');
                    if (@$this->request['source'] <> null ) $query->where('source', 'ilike', '%'.$this->request['source'].'%');
                    if (@$this->request['status'] <> null ) $query->whereIn('status', $this->request['status']);
                    if (@$this->request['place'] <> null ) $query->whereIn('place', $this->request['place']);
                    if (@$this->request['priority'] <> null ) $query->whereIn('priority', $this->request['priority']);
                    if (@$this->request['interested'] <> null ) $query->where('interested', 'ilike', '%'.$this->request['interested'].'%');
                    if (@$this->request['date_in'] <> null ) $query->where('date_in', $this->request['date_in_condition'], '%'.$this->request['date_in'].'%');
                    if (@$this->request['deadline'] <> null ) $query->where('deadline', $this->request['deadline_condition'], '%'.$this->request['deadline'].'%');
                    if (@$this->request['date_out'] <> null ) $query->where('date_out', $this->request['date_out_condition'], '%'.$this->request['date_out'].'%');
                    if (@$this->request['date_return'] <> null ) $query->where('date_return', $this->request['date_return_condition'], '%'.$this->request['date_return'].'%');
                    if (@$this->request['subject'] <> null ) $query->where('subject', 'ilike', '%'.$this->request['subject'].'%');
                })
                ->orderby('deadline', 'ASC')
                ->paginate(50);

            $this->files = FileManager::getFiles()->pluck('route_id', 'id')->countBy();

            $items->each( function($item) {
                $item->date_in = date("d/m/Y", strtotime($item->date_in));
                $item->deadline = date("d/m/Y", strtotime($item->deadline));
                $item->files = isset($this->files[$item->id]) ? $this->files[$item->id] : 0;
            });


        } elseif($request['key_words'] != null) {

            $items = DB::table('protocolo_kw')
            ->whereIn('id_keyword', $this->request['key_words'])
            ->join('registries', 'registries.id', '=', 'protocolo_kw.id_protocolo')
            ->get();

            $items->each( function($item) {
                $item->date_in = date("d/m/Y", strtotime($item->date_in));
                $item->deadline = date("d/m/Y", strtotime($item->deadline));
                $item->files = isset($this->files[$item->id]) ? $this->files[$item->id] : 0;
            });
            
            $items = $items->unique('protocol');
        
        }else {
            $items = false;
        }

        $doctypes = Doctype::orderBy('order')->get()->pluck('name', 'id');
        $statuses = Status::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $priorities = Priority::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $places = Place::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $Key_words      = DB::table('key_words')->select('*')->get()->pluck('name', 'id');

        return view('legaladvice.registries.search', compact('form', 'items', 'conditions', 'doctypes', 'statuses', 'priorities', 'places', 'Key_words'));
    }

    /**
     * Show the form for creating new Procedure.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $doctypes       = Doctype::orderBy('order')->get()->pluck('name', 'id');
        $statuses       = Status::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $priorities     = Priority::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $places         = Place::orderBy('order', 'asc')->get()->pluck('name', 'id'); 
        $Key_words      = DB::table('key_words')->select('*')->get()->pluck('name', 'id');

        return view('legaladvice.registries.create', compact('doctypes', 'statuses', 'priorities', 'places', 'Key_words'));
    }

    /**
     * Store a newly created Procedure in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null) {
        $existeProtocolo = Registry::where('protocol', 'like', $request->protocol.'%')->count();

        if($existeProtocolo>0)
            return back()->withErrors('eProtocolo já existe', ["full"=>true]);

            $this->validate($request, [
            'protocol' => 'required|unique:legaladvice.registries|max:120',
            'document_type' => 'numeric',
            'document_number' => 'required',
            'source' => 'required',
            'status' => 'nullable|numeric',
            'priority' => 'numeric',
            'interested' => 'required',
            'date_in' => 'required',
            'deadline' => 'required',
            'subject' => 'required',
        ], [], [
            'protocol' => __('legaladvice.registries.fields.protocol'),
            'document_type' => __('legaladvice.registries.fields.document_type'),
            'document_number' => __('legaladvice.registries.fields.document_number'),
            'source' => __('legaladvice.registries.fields.source'),
            'status' => __('legaladvice.registries.fields.status'),
            'priority' => __('legaladvice.registries.fields.priority'),
            'interested' => __('legaladvice.registries.fields.interested'),
            'date_in' => __('legaladvice.registries.fields.date_in'),
            'deadline' => __('legaladvice.registries.fields.deadline'),
            'subject' => __('legaladvice.registries.fields.subject'),
        ]);

        $date_in = $request->date_in ? $this->dateBR($request->date_in) : '';
        $deadline = $request->deadline ? $this->dateBR($request->deadline) : '';
        $date_out = $request->date_out ? $this->dateBR($request->date_out) : '';
        $date_return = $request->date_return ? $this->dateBR($request->date_return) : '';

        $form = $request->except('source_file', 'date_in', 'deadline', 'date_out', 'date_return', 'key_words');
        $form += [ 
                'date_in' => $date_in,
                'deadline' => $deadline,
                'date_out' => $date_out,
                'date_return' => $date_return,
        ];

        $kw_arr = (array)$request->input('key_words');
        
        $id = Registry::create($form);
        
        for ($i=0; $i < count($kw_arr); $i++) { 
            # code...
            Protocol_kw::create([
                'id_protocolo' => $id->id, 'id_keyword' => $kw_arr[$i],
            ]);
        }


        return redirect()->route('legaladvice.registries.edit', $id->id)->with('success', __('global.app_msg_store_success'));
    }

    public function verifyIfExistProtocolWithAjax(Request $request)
    {
        $existeProtocolo = Registry::where('protocol', 'like', $request->protocol.'%')->get();
        $lookingForProtocol = json_decode(json_encode($existeProtocolo));

        // se desejar retornar o json passe $roo
        return $arr = ["resp"=>count($lookingForProtocol)];
    }

    /**
     * Show the form for editing Procedure.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $nobreadcrumbs = true;

        $item = Registry::findOrFail($id);

        $notes = DB::select(DB::raw("select * from notes where registries_id = $id"));
        // return $notes;

        $file_managers = DB::select(DB::raw("select * from file_managers where route_id = $id"));
        // return $file_managers;

        $item->date_in = date("d/m/Y", strtotime($item->date_in));

        $item->deadline = date("d/m/Y", strtotime($item->deadline));

        $doctypes = Doctype::orderBy('name')->get()->pluck('name', 'id');

        $procedures = Procedure::where('registry_id', $id)->orderBy('date', 'desc')->get();

        $procedures->each( function($item){
            $item->dateBR = date("d/m/Y", strtotime($item->date));
        });

        // return $file_managers;

        return view('legaladvice.registries.show', compact('nobreadcrumbs', 'id', 'item', 'procedures', 'doctypes', 'notes', 'file_managers'));
    }

    /**
     * Show the form for editing Procedure.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $registry   = Registry::findOrFail($id);
        $procedures = Procedure::where('registry_id', $id)->orderBy('date', 'desc')->get();
        $doctypes   = Doctype::orderBy('order')->get()->pluck('name', 'id');
        $statuses   = Status::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $priorities = Priority::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $places     = Place::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $note_registry   = DB::select(DB::raw("SELECT * FROM public.registries join notes on notes.registries_id=$id and registries.id=$id"));
        // return $note_registry ;

        $registry->date_in = $registry->date_in ? date("d/m/Y", strtotime($registry->date_in)) : '';
        $registry->deadline = $registry->deadline ? date("d/m/Y", strtotime($registry->deadline)) : '';
        $registry->date_out = $registry->date_out ? date("d/m/Y", strtotime($registry->date_out)) : '';
        $registry->date_return = $registry->date_return ? date("d/m/Y", strtotime($registry->date_return)) : '';
    
	    $procedures->each( function($item){
            $item->dateBR = date("d/m/Y", strtotime($item->date));
        });

        $userId = \Auth::user()->id;
        $registryRoute = 'legaladvice.registries.edit';

        return view('legaladvice.registries.edit', compact('id', 'registry', 'procedures', 'doctypes', 'statuses', 'priorities', 'places', 'userId', 'registryRoute', 'note_registry'));
    }

    /**
     * Display a listing of Procedures.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadIndex(Request $request) {
        $route = 'legaladvice.registries.uploaddestroy';
        $items = FileManager::getFiles($request->id);

        $items->each( function($item) {
            $item->date = date("d/m/Y H:m:s", strtotime($item->updated_at));
        });
    
        return view('admin.fileupload.index', compact('route', 'items'));
    }

    /**
     * Show the form for creating new Procedure.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadCreate(Request $request) {
        $store = 'legaladvice.registries.uploadstore';
        $id = $_GET['id'];

        return view('admin.fileupload.create', compact('store', 'id'));
    }

    /**
     * Store a newly created Procedure in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadStore(Request $request) {
        $item = FileManager::uploadFile($request);
        return $item;
    }

    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadDestroy(Request $request) {
        $item = FileManager::deleteFile($request, 'loadCalls()');
        return $item;
    }
    
    /**
     * Update Procedure in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'protocol' => 'required|unique:legaladvice.registries,protocol,'.$id.'|max:120',
            'document_type' => 'numeric',
            'document_number' => 'required',
            'source' => 'required',
            'status' => 'nullable|numeric',
            'priority' => 'numeric',
            'interested' => 'required',
            'date_in' => 'required',
            'deadline' => 'required',
            'date_out' => 'nullable',
            'date_return' => 'nullable',
            'subject' => 'required',
        ], [], [
            'protocol' => __('legaladvice.registries.fields.protocol'),
            'document_type' => __('legaladvice.registries.fields.document_type'),
            'document_number' => __('legaladvice.registries.fields.document_number'),
            'source' => __('legaladvice.registries.fields.source'),
            'status' => __('legaladvice.registries.fields.status'),
            'priority' => __('legaladvice.registries.fields.priority'),
            'interested' => __('legaladvice.registries.fields.interested'),
            'date_in' => __('legaladvice.registries.fields.date_in'),
            'deadline' => __('legaladvice.registries.fields.deadline'),
            'date_out' => __('legaladvice.registries.fields.date_out'),
            'date_return' => __('legaladvice.registries.fields.date_return'),
            'subject' => __('legaladvice.registries.fields.subject'),
        ]);

	$item = Registry::findOrFail($id);

        $date_in = $request->date_in ? $this->dateBR($request->date_in) : '';
        $deadline = $request->deadline ? $this->dateBR($request->deadline) : '';
        $date_out = $request->date_out ? $this->dateBR($request->date_out) : '';
        $date_return = $request->date_return ? $this->dateBR($request->date_return) : '';
	
	$form = $request->except('source_file', 'date_in', 'deadline', 'date_out', 'date_return');
	$form['urgent'] = $request->urgent;
	$form += [ 
		'date_in' => $date_in, 
		'deadline' => $deadline, 
		'date_out' => $date_out, 
		'date_return' => $date_return,
	];

	//dd($request->date_in, $form);
	$item->update($form);

        return redirect()->route('legaladvice.registries.index')->with('success', __('global.app_msg_update_success'));
    }

    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        $item = Registry::findOrFail($id);
        $item->delete();

        return redirect()->route('legaladvice.registries.index')->with('success', __('global.app_msg_destroy_success'));
    }

    /**
     * Delete all selected Procedures at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request) {
        if ($request->input('ids')) {
            $entries = Registry::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('legaladvice.registries.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('legaladvice.registries.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }

    public function dateBR($input) {
        $d = explode('/', $input);
	$date = $d[2].'-'.$d[1].'-'.$d[0];

        return $date;
    }

    public function note(Request $request){
        // return $request->input('contain');
        $validator = Validator::make($request->all(), [
            'id_registries' => 'required|integer',
            'eProtocolo' => 'required|string',
            'contain' => 'required|string',
        ]);        

        if ($validator->fails()) {
            return "erro de validação";
        }

        $getPreg = pregmatch::bPregmatch($request->input('contain'));
        if($getPreg){
                try {
                    //code...
                    $newNote = new note;
                    $newNote->registries_id=$request->input('id_registries');
                    $newNote->date_in=date('Y-m-d');
                    $newNote->inserted_by=Auth::user()->name;
                    $newNote->contain=$request->input('contain');
                    $newNote->save();

                } catch (\Throwable $th) {
                    throw $th;
                }
                
                return redirect($_SERVER['HTTP_REFERER'])->with(['bnoteInsert'=>true]);
        }else{
            return "Não foi possível realizar o cadastro. nâo use caracteres especiais.";
        }
        
    }
}
