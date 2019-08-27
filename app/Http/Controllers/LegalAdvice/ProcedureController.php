<?php

namespace App\Http\Controllers\LegalAdvice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\LegalAdvice\Doctype;
use App\Models\LegalAdvice\Procedure;
use App\Models\LegalAdvice\Status;
use App\Models\LegalAdvice\Priority;
use App\Models\LegalAdvice\Registry;
use App\Models\Admin\FileManager;
use Validator;

class ProcedureController extends Controller
{
    /**
     * Display a listing of Procedures.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $doctypes = Doctype::orderBy('name')->get()->pluck('name', 'id');
        
        $procedures = Procedure::where('registry_id', $request->id)->orderBy('date', 'desc')->get();
        $procedures->each( function($item) {
            $item->dateBR = date("d/m/Y", strtotime($item->date));
            $filesID = json_decode($item->files);
            
            if ($filesID) {
                $item->files = FileManager::whereIn('id', $filesID)->get();
            }
        });
    
        return view('legaladvice.procedures.index', compact('procedures', 'doctypes'));
    }

    /**
     * Show the form for creating new Procedure.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $doctypes = Doctype::orderBy('name')->get()->pluck('name', 'id');
        $files = FileManager::getFiles($request->id, 'LegalAdvice\RegistryController')->pluck('title', 'id');

        $selectedItems = Procedure::select('files')->where('registry_id', $request->id)->get()->pluck('files')->filter();

        $selectedItems = $selectedItems->map(function($item) {
            return json_decode($item);
        });

        $selectedItems = $selectedItems->collapse();
        $files = $files->diffKeys($selectedItems->flip());

        return view('legaladvice.procedures.create', compact('doctypes', 'files'));
    }

    /**
     * Store a newly created Procedure in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null) {
        $rules = [
            'document_type' => 'numeric',
            'document_number' => 'required',
            'source' => 'required',
            'date' => 'date',
            'files' => 'required',
            'subject' => 'required',
        ];

        $attributes = [
            'document_type' => __('legaladvice.registries.fields.document_type'),
            'document_number' => __('legaladvice.registries.fields.document_number'),
            'source' => __('legaladvice.registries.fields.source'),
            'date' => __('legaladvice.registries.fields.date'),
            'files' => __('legaladvice.registries.fields.files'),
            'subject' => __('legaladvice.registries.fields.subject'),
        ];

        $errors = Validator::make($request->all(), $rules, [], $attributes);

        if($errors->fails()) {
            return response()->json(['code' => 'error', 'messages' => $errors->errors()->all()]);
        }

        $form = $request->all();
        $form['files'] = collect($form['files'])->toJson();

        Procedure::create( $form );

        return response()->json(['code' => 'success', 'messages' => __('global.app_msg_store_success')]);
    }

    /**
     * Show the form for editing Procedure.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $item = Procedure::where('id', $id)->first();
        $doctypes = Doctype::orderBy('name')->get()->pluck('name', 'id');
        $files = FileManager::getFiles($item->registry_id, 'LegalAdvice\RegistryController')->pluck('title', 'id');

        $item->files = json_decode($item->files);

        $selectedItems = Procedure::select('files')
            ->where('id', '<>', $id)
            ->where('registry_id', $item->registry_id)
            ->get()
            ->pluck('files')->filter();

        $selectedItems = $selectedItems->map(function($item) {
            return json_decode($item);
        });

        $selectedItems = $selectedItems->collapse();
        $files = $files->diffKeys($selectedItems->flip());

        return view('legaladvice.procedures.edit', compact('item', 'doctypes', 'files'));
    }

    /**
     * Update Procedure in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $rules = [
            'document_type' => 'numeric',
            'document_number' => 'required',
            'source' => 'required',
            'date' => 'date',
            'files' => 'required',
            'subject' => 'required',
        ];

        $attributes = [
            'document_type' => __('legaladvice.registries.fields.document_type'),
            'document_number' => __('legaladvice.registries.fields.document_number'),
            'source' => __('legaladvice.registries.fields.source'),
            'date' => __('legaladvice.registries.fields.date'),
            'files' => __('legaladvice.registries.fields.files'),
            'subject' => __('legaladvice.registries.fields.subject'),
        ];

        $errors = Validator::make($request->all(), $rules, [], $attributes);

        if($errors->fails()) {
            return response()->json(['code' => 'error', 'messages' => $errors->errors()->all()]);
        }

        $item = Procedure::findOrFail($id);

        $form = $request->all();
        $form['files'] = collect($form['files'])->toJson();

        $item->update( $form );

        return response()->json(['code' => 'success', 'messages' => __('global.app_msg_update_success')]);
    }

    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        $item = Procedure::findOrFail($id);
        if ($item->delete()) {
            return response()->json(['code' => 'success', 'messages' => __('global.app_msg_destroy_success'), 'callback' => 'loadCalls()']);
        } else {
            return response()->json(['code' => 'error', 'messages' => __('global.app_msg_destroy_error')]);
        }
    }
}
