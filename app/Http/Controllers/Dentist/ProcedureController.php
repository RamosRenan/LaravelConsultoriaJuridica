<?php

namespace App\Http\Controllers\Dentist;

use App\Models\Dentist\Procedure;
use App\Models\Dentist\ProcedureItem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProcedureController extends Controller
{
    public function __construct() {
        $this->middleware('check.permissions');
    }

    /**
     * Display a listing of Procedures.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $items = Procedure::select('procedures.*')
            ->where('name', 'ilike', '%'.$search.'%')
            ->selectRaw('(SELECT MAX(date) FROM procedure_items WHERE procedure_id = procedures.id) AS date')
            ->selectRaw('
                (
                    SELECT price FROM procedure_items WHERE procedure_id = procedures.id AND date = 
                    (
                        SELECT MAX(date) FROM procedure_items WHERE procedure_id = procedures.id 
                    )
                ) AS price')
            ->orderby('name', 'asc')->paginate(50);

        return view('dentist.procedures.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new Procedure.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dentist.procedures.create');
    }

    /**
     * Store a newly created Procedure in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null)
    {
        if ($request['name']) {
            $this->validate($request, [
                'name' => 'required|unique:dentist.procedures|max:120',
                'price' => 'numeric',
                'date' => 'date',
            ], [], [
                'name' => __('dentist.procedures.fields.name'),
                'price' => __('dentist.procedures.fields.price'),
                'date' => __('dentist.procedures.fields.date'),
            ]);

            $procedure = Procedure::create($request->only('name'));

            $proceduteItems = $request->except('name');
            $proceduteItems['procedure_id'] = $procedure->id;

            ProcedureItem::create($proceduteItems);

            return redirect()->route('dentist.procedures.index')->with('success', __('global.app_msg_store_success'));
        }
        else
        {
            $this->validate($request, [
                'price' => 'numeric',
                'date' => 'date',
            ], [], [
                'name' => __('dentist.procedures.fields.name'),
                'price' => __('dentist.procedures.fields.price'),
            ]);

            $proceduteItems = $request->all();

            ProcedureItem::create($proceduteItems);

            return redirect()->route('dentist.procedures.edit', $request['procedure_id'])->with('success', __('global.app_msg_store_success'));
        }
    }

    /**
     * Show the form for editing Procedure.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $procedure = Procedure::findOrFail($id);

        $prices = ProcedureItem::where('procedure_id', $id)
            ->orderby('date', 'desc')->get();

        return view('dentist.procedures.edit', compact('id', 'procedure', 'prices'));
    }

    /**
     * Update Procedure in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $mode = $request['mode'];

        if ( $mode == 'edit_procedure_name' ) {
            $this->validate($request, [
                'name'=>'required|unique:dentist.procedures,name,'.$id.'|max:120',
            ], [], [
                'name' => __('dentist.procedures.fields.name'),
            ]);
    
            $item = Procedure::findOrFail($id);
            $item->update($request->all());
    
            return redirect()->route('dentist.procedures.index')->with('success', __('global.app_msg_update_success'));
        } elseif ( $mode == 'edit_procedure_price' ) {
            $itemId = $request['item_id'];

            $this->validate($request, [
                'price'=>'required|numeric',
                'date'=>'required|date',
            ], [], [
                'price' => __('dentist.procedures.fields.price'),
                'date' => __('dentist.procedures.fields.date'),
            ]);

            $procedure = ProcedureItem::findOrFail($itemId);
            $procedure->update($request->only('date', 'price'));

            return redirect()->route('dentist.procedures.edit', $id)->with('success', __('global.app_msg_update_success'));
        }
    }

    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $mode = $request['mode'];
        $itemId = $request['item_id'];

        if ( $mode == 'delete_procedure_price' ) 
        {
            $item = ProcedureItem::findOrFail($itemId);
            $item->delete();

            return redirect()->route('dentist.procedures.edit', $id)->with('success', __('global.app_msg_destroy_success'));
        }
        else
        {
            $item = Procedure::findOrFail($id);
            $item->delete();

            return redirect()->route('dentist.procedures.index')->with('success', __('global.app_msg_destroy_success'));
        }
    }

    /**
     * Delete all selected Procedures at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Procedure::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('dentist.procedures.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('dentist.procedures.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
