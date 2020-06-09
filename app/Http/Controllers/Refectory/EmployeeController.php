<?php

namespace App\Http\Controllers\Refectory;

use App\Models\Admin\Unit;
use App\Models\Refectory\Specialty;
use App\Models\Refectory\Employee;
use App\Models\Refectory\EmployeeHasSpecialty;
use App\Models\Refectory\EmployeeHasUnit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    public function __construct() {
        $this->middleware('check.permissions');
    }

    /**
     * Display a listing of Employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $items = Employee::select('*')
            ->where('name', 'ilike', '%'.$search.'%')
            ->orWhere('rg', 'ilike', '%'.$search.'%')
            ->orWhere('cpf', 'ilike', '%'.$search.'%')
            ->orderby('name', 'asc')->paginate(50);

        return view('refectory.employees.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new Employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $units = Unit::get()->pluck('name', 'id');
        
        $specialties = Specialty::get()->pluck('name', 'id');

        return view('refectory.employees.create', compact( 'units', 'specialties' ));
    }

    /**
     * Store a newly created Employee in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|max:120',
            'rg'=>'required|unique:refectory.employees|max:120',
            'cpf'=>'required|unique:refectory.employees|max:120',
        ], [], [
            'name' => __('refectory.employees.fields.name'),
            'rg' => __('refectory.employees.fields.rg'),
            'cpf' => __('refectory.employees.fields.cpf'),
        ]);

        $employees = Employee::create($request->except('specialties'));

        $this->syncUnits($request, $employees->id);
        $this->syncSpecialties($request, $employees->id);

        return redirect()->route('refectory.employees.index')->with('success', __('global.app_msg_store_success'));
    }

    /**
     * Show the form for editing Employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Employee::findOrFail($id);

        $units = Unit::get()->pluck('name', 'id');

        $myUnits = EmployeeHasUnit::where('employee_id', $id)
            ->get()
            ->pluck('unit_id');

        $specialties = Specialty::get()->pluck('name', 'id');

        $mySpecialties = EmployeeHasSpecialty::where('employee_id', $id)
            ->get()
            ->pluck('specialty_id');
    
        return view('refectory.employees.edit', compact('item', 'specialties', 'mySpecialties', 'units', 'myUnits'));
    }

    /**
     * Update Employee in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required|unique:refectory.employees,name,'.$id.'|max:120',
            'rg'=>'required|unique:refectory.employees,rg,'.$id.'|max:120',
            'cpf'=>'required|unique:refectory.employees,cpf,'.$id.'|max:120',
        ], [], [
            'name' => __('refectory.employees.fields.name'),
            'rg' => __('refectory.employees.fields.rg'),
            'cpf' => __('refectory.employees.fields.cpf'),
        ]);

        $item = Employee::findOrFail($id);
        if ( $item->update($request->except('specialties')) )
        {
            $this->syncUnits($request, $id);
            $this->syncSpecialties($request, $id);
            return redirect()->route('refectory.employees.index')->with('success', __('global.app_msg_update_success'));
        }
        else
        {
            return redirect()->route('refectory.employees.index')->with('error', __('global.app_msg_update_error'));
        }
    }

    /**
     * Sync Specialties from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function syncSpecialties($request, $id) {
        $this->id = $id;
            
        $specialties = $request->input('specialties') ? $request->input('specialties') : [];

        $entries = EmployeeHasSpecialty::where('employee_id', $id)
            ->get();

        $staying = EmployeeHasSpecialty::where('employee_id', $id)
            ->whereIn('specialty_id', $specialties)
            ->get()->pluck('specialty_id');

        $result = collect( $specialties )->diff($staying);

        $specialtyInsert = $result->map( function($item) {
            return [
                'employee_id' => $this->id,
                'specialty_id' => $item,
            ];
        });

        $insert = EmployeeHasSpecialty::insert($specialtyInsert->toArray());

        $delete = EmployeeHasSpecialty::where('employee_id', $id)
            ->whereNotIn('specialty_id', $specialties)
            ->delete();
    }

    /**
     * Sync syncUnits from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function syncUnits($request, $id) {
        $this->id = $id;
            
        $units = $request->input('units') ? $request->input('units') : [];

        $entries = EmployeeHasUnit::where('employee_id', $id)
            ->get();

        $staying = EmployeeHasUnit::where('employee_id', $id)
            ->whereIn('unit_id', $units)
            ->get()->pluck('unit_id');

        $result = collect( $units )->diff($staying);

        $unitInsert = $result->map( function($item) {
            return [
                'employee_id' => $this->id,
                'unit_id' => $item,
            ];
        });

        $insert = EmployeeHasUnit::insert($unitInsert->toArray());

        $delete = EmployeeHasUnit::where('employee_id', $id)
            ->whereNotIn('unit_id', $units)
            ->delete();
    }

    /**
     * Remove Specialty from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Employee::findOrFail($id);
        $item->delete();

        return redirect()->route('refectory.employees.index')->with('success', __('global.app_msg_destroy_success'));
    }

    /**
     * Delete all selected Employees at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Employee::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('refectory.employees.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('refectory.employees.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
