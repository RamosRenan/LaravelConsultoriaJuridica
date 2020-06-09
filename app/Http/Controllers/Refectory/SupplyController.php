<?php

namespace App\Http\Controllers\Refectory;

use App\Models\Refectory\Supply;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplyController extends Controller
{
    public function __construct() {
        $this->middleware('check.permissions');
    }

    /**
     * Display a listing of supplies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $items = Supply::where('name', 'ilike', '%'.$search.'%')
            ->orderby('name', 'asc')->paginate(50);

        return view('refectory.supplies.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new Supply.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('refectory.supplies.create');
    }

    /**
     * Show the list of existing Supplies.
     *
     * @return \Illuminate\Http\Response
     */
    public function supplyListAjax(Request $request)
    {
        $search = $request->query('q');

        $items = Supply::select('id AS value', 'name AS text')
            ->where('name', 'ilike', '%'.$search.'%')
            ->orderby('name', 'asc')
            ->get();

        return response() ->json( $items );
    }

    /**
     * Store a newly created Supply in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|unique:refectory.supplies|max:120',
        ], [], [
            'name' => __('refectory.supplies.fields.name'),
        ]);

        Supply::create($request->all());

        return redirect()->route('refectory.supplies.index')->with('success', __('global.app_msg_store_success'));
    }

    /**
     * Show the form for editing Supply.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Supply::findOrFail($id);

        return view('refectory.supplies.edit', compact('id', 'item'));
    }

    /**
     * Update Supply in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required|unique:refectory.supplies|max:120',
        ], [], [
            'name' => __('refectory.supplies.fields.name'),
        ]);

        $item = Supply::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('refectory.supplies.index')->with('success', __('global.app_msg_update_success'));
    }

    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Supply::findOrFail($id);
        $item->delete();

        return redirect()->route('refectory.supplies.index')->with('success', __('global.app_msg_destroy_success'));
    }

    /**
     * Delete all selected supplies at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Supply::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('refectory.supplies.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('refectory.supplies.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
