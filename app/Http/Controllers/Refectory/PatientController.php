<?php

namespace App\Http\Controllers\Dentist;

use App\Models\Dentist\Patient;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    public function __construct() {
        $this->middleware('check.permissions');
    }

    /**
     * Display a listing of Patients.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $items = Patient::where('name', 'ilike', '%'.$search.'%')
            ->orWhere('rg', 'ilike', '%'.$search.'%')
            ->orWhere('cpf', 'ilike', '%'.$search.'%')
            ->orderby('name', 'asc')->paginate(50);

        return view('dentist.patients.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new Patient.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dentist.patients.create');
    }

    /**
     * Store a newly created Patient in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|unique:dentist.patients|max:120',
            'rg'=>'required|unique:dentist.patients',
            'cpf'=>'required|unique:dentist.patients',
        ], [], [
            'name' => __('dentist.patients.fields.name'),
            'rg' => __('dentist.patients.fields.rg'),
            'cpf' => __('dentist.patients.fields.cpf'),
        ]);

        Patient::create($request->all());

        return redirect()->route('dentist.patients.index')->with('success', __('global.app_msg_store_success'));
    }

    /**
     * Show the form for editing Patient.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Patient::findOrFail($id);

        return view('dentist.patients.edit', compact('item'));
    }

    /**
     * Update Patient in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required|unique:dentist.patients,name,'.$id.'|max:120',
            'rg'=>'required|unique:dentist.patients,rg,'.$id,
            'cpf'=>'required|unique:dentist.patients,cpf,'.$id,
        ], [], [
            'name' => __('dentist.patients.fields.name'),
            'rg' => __('dentist.patients.fields.rg'),
            'cpf' => __('dentist.patients.fields.cpf'),
        ]);

        $item = Patient::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('dentist.patients.index')->with('success', __('global.app_msg_update_success'));
    }


    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Patient::findOrFail($id);
        $item->delete();

        return redirect()->route('dentist.patients.index')->with('success', __('global.app_msg_destroy_success'));
    }

    /**
     * Delete all selected Patients at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Patient::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('dentist.patients.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('dentist.patients.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
