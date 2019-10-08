<?php

namespace App\Http\Controllers\LegalAdvice;

use App\Models\LegalAdvice\Priority;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrioritiesController extends Controller
{
    public function __construct() {
        $this->middleware('check.permissions');
    }

    /**
     * Display a listing of Specialties.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $items = Priority::where('name', 'ilike', '%'.$search.'%')
            ->orderby('order', 'asc')->paginate(50);

        return view('legaladvice.priorities.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new Unit.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('legaladvice.priorities.create');
    }

    /**
     * Store a newly created Unit in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|unique:legaladvice.priorities|max:120',
        ], [], [
            'name' => __('legaladvice.priorities.fields.name'),
        ]);

        Priority::create($request->all());

        return redirect()->route('legaladvice.priorities.index')->with('success', __('global.app_msg_store_success'));
    }

    /**
     * Show the form for editing Unit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Priority::findOrFail($id);

        return view('legaladvice.priorities.edit', compact('id', 'item'));
    }

    /**
     * Update Unit in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required|unique:legaladvice.priorities,name,'.$id.'|max:120',
        ], [], [
            'name' => __('legaladvice.priorities.fields.name'),
        ]);

        $item = Priority::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('legaladvice.priorities.index')->with('success', __('global.app_msg_update_success'));
    }


    /**
     * Remove Unit from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Priority::findOrFail($id);
        $item->delete();

        return redirect()->route('legaladvice.priorities.index')->with('success', __('global.app_msg_destroy_success'));
    }

    /**
     * Delete all selected Specialties at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Priority::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('legaladvice.priorities.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('legaladvice.priorities.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
