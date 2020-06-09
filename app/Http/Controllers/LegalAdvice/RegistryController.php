<?php
namespace App\Http\Controllers\LegalAdvice;

use App\Models\LegalAdvice\Doctype;
use App\Models\LegalAdvice\Procedure;
use App\Models\LegalAdvice\Status;
use App\Models\LegalAdvice\Priority;
use App\Models\LegalAdvice\Place;
use App\Models\LegalAdvice\Registry;
use App\Models\Admin\FileManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

class RegistryController extends Controller {
    public function __construct() {
        $this->middleware('check.permissions');
    }

    /**
     * Display a listing of Procedures.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $search = @$_GET['search'];

	$this->files = FileManager::getFiles()->pluck('route_id', 'id')->countBy();

	$query = Registry::query();
		
	    $query->select('registries.*', 'priorities.name AS priority', 'priorities.id AS priority_id');
            $query->join('priorities', 'priorities.id', '=', 'registries.priority');
            $query->leftJoin('procedures', 'registries.id', '=', 'procedures.registry_id');
            $query->selectRaw('(SELECT COUNT(id) FROM procedures WHERE registry_id = registries.id) AS procedures');
	    $query->when(@$_GET['priority'], function ($q) {
		return $q->where('priority', $_GET['priority']);
            });
	    $query->when(@$_GET['see'] == 'uptodate', function ($q) {
		return $q->whereRaw('EXTRACT( EPOCH FROM (deadline - now()) ) / 60 / 60 / 24 > 3');
            });
	    $query->when(@$_GET['see'] == 'deadline', function ($q) {
		return $q->whereRaw('EXTRACT( EPOCH FROM (deadline - now()) ) / 60 / 60 / 24 <= 3 AND EXTRACT( EPOCH FROM (deadline - now()) ) / 60 / 60 / 24 >= 0');
            });
	    $query->when(@$_GET['see'] == 'late', function ($q) {
		return $q->whereRaw('EXTRACT( EPOCH FROM (deadline - now()) ) < 0');
            });
            $query->where(function ($q) {
                $search = @$_GET['search'];

		$q->orwhere('registries.protocol', 'ilike', '%'.$search.'%');
                $q->orWhere('registries.document_number', 'ilike', '%'.$search.'%');
                $q->orWhere('registries.source', 'ilike', '%'.$search.'%');
                $q->orWhere('registries.interested', 'ilike', '%'.$search.'%');
                $q->orWhere('registries.subject', 'ilike', '%'.$search.'%');
                $q->orWhere('procedures.document_number', 'ilike', '%'.$search.'%');
                $q->orWhere('procedures.source', 'ilike', '%'.$search.'%');
                $q->orWhere('procedures.subject', 'ilike', '%'.$search.'%');
                $q->orderBy('registries.deadline', 'ASC');
            });
            $query->distinct();
	    $items = $query->paginate(50);

	$this->maxSize = 200;

	$items->each( function($item) {
	    $item->subject = substr($item->subject, 0, $this->maxSize) . (strlen($item->subject) > $this->maxSize ? '...' : '');
            $item->date_in = date("d/m/Y", strtotime($item->date_in));
            $item->deadline = date("d/m/Y", strtotime($item->deadline));
            $item->files = isset($this->files[$item->id]) ? $this->files[$item->id] : 0;
            $item->date_out = date("d/m/Y", strtotime($item->date_out));
            $item->date_return = date("d/m/Y", strtotime($item->date_return));
        });
    
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

        if ($request['dosearch'] == true) {
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
        } else {
            $items = [];
        }

        $doctypes = Doctype::orderBy('order')->get()->pluck('name', 'id');
        $statuses = Status::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $priorities = Priority::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $places = Place::orderBy('order', 'asc')->get()->pluck('name', 'id');

        return view('legaladvice.registries.search', compact('form', 'items', 'conditions', 'doctypes', 'statuses', 'priorities', 'places'));
    }

    /**
     * Show the form for creating new Procedure.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $doctypes = Doctype::orderBy('order')->get()->pluck('name', 'id');
        $statuses = Status::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $priorities = Priority::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $places = Place::orderBy('order', 'asc')->get()->pluck('name', 'id');

        return view('legaladvice.registries.create', compact('doctypes', 'statuses', 'priorities', 'places'));
    }

    /**
     * Store a newly created Procedure in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null) {
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

        $form = $request->except('source_file', 'date_in', 'deadline', 'date_out', 'date_return');
        $form += [ 
                'date_in' => $date_in,
                'deadline' => $deadline,
                'date_out' => $date_out,
                'date_return' => $date_return,
        ];

        //dd($request->date_in, $form);
	
        $id = Registry::create($form);

        return redirect()->route('legaladvice.registries.edit', $id->id)->with('success', __('global.app_msg_store_success'));
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

        $item->date_in = date("d/m/Y", strtotime($item->date_in));
        $item->deadline = date("d/m/Y", strtotime($item->deadline));

        $doctypes = Doctype::orderBy('name')->get()->pluck('name', 'id');

        $procedures = Procedure::where('registry_id', $id)->orderBy('date', 'desc')->get();

        $procedures->each( function($item) {
            $item->dateBR = date("d/m/Y", strtotime($item->date));
        });

        return view('legaladvice.registries.show', compact('nobreadcrumbs', 'id', 'item', 'procedures', 'doctypes'));
    }

    /**
     * Show the form for editing Procedure.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $registry = Registry::findOrFail($id);
        $procedures = Procedure::where('registry_id', $id)->orderBy('date', 'desc')->get();
        $doctypes = Doctype::orderBy('order')->get()->pluck('name', 'id');
        $statuses = Status::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $priorities = Priority::orderBy('order', 'asc')->get()->pluck('name', 'id');
        $places = Place::orderBy('order', 'asc')->get()->pluck('name', 'id');

        $registry->date_in = $registry->date_in ? date("d/m/Y", strtotime($registry->date_in)) : '';
        $registry->deadline = $registry->deadline ? date("d/m/Y", strtotime($registry->deadline)) : '';
        $registry->date_out = $registry->date_out ? date("d/m/Y", strtotime($registry->date_out)) : '';
        $registry->date_return = $registry->date_return ? date("d/m/Y", strtotime($registry->date_return)) : '';
    
	$procedures->each( function($item) {
            $item->dateBR = date("d/m/Y", strtotime($item->date));
        });

        $userId = \Auth::user()->id;
        $registryRoute = 'legaladvice.registries.edit';

        return view('legaladvice.registries.edit', compact('id', 'registry', 'procedures', 'doctypes', 'statuses', 'priorities', 'places', 'userId', 'registryRoute'));
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
}
