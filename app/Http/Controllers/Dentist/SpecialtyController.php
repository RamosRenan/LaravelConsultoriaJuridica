<?php

namespace App\Http\Controllers\Dentist;

use App\Models\Dentist\Specialty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialtyController extends Controller
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

        $items = Specialty::where('name', 'ilike', '%'.$search.'%')
            ->orWhere('description', 'ilike', '%'.$search.'%')
            ->orderby('name', 'asc')->paginate(50);

        return view('dentist.specialties.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new Specialty.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dentist.specialties.create');
    }

    /**
     * Store a newly created Specialty in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|unique:dentist.specialties|max:120',
        ], [], [
            'name' => __('dentist.specialties.fields.name'),
        ]);

        Specialty::create($request->all());

        return redirect()->route('dentist.specialties.index')->with('success', __('global.app_msg_store_success'));
    }

    /**
     * Show the form for editing Specialty.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Specialty::findOrFail($id);

        return view('dentist.specialties.edit', compact('item'));
    }

    /**
     * Update Specialty in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required|unique:dentist.specialties,name,'.$id.'|max:120',
        ], [], [
            'name' => __('dentist.specialties.fields.name'),
        ]);

        $item = Specialty::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('dentist.specialties.index')->with('success', __('global.app_msg_update_success'));
    }


    /**
     * Remove Specialty from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Specialty::findOrFail($id);
        $item->delete();

        return redirect()->route('dentist.specialties.index')->with('success', __('global.app_msg_destroy_success'));
    }

    /**
     * Delete all selected Specialties at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Specialty::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('dentist.specialties.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('dentist.specialties.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
