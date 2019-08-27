<?php

namespace App\Http\Controllers\Siscop;

use App\Models\Siscop\City;

use App\Models\Siscop\Time;
use App\Models\Siscop\Suspect;
use App\Models\Siscop\Risks;
use App\Models\Siscop\Info;

use App\Models\Dentist\Unit;
use App\Models\Dentist\Specialty;
use App\Models\Dentist\Dentist;
use App\Models\Dentist\DentistHasSpecialty;
use App\Models\Dentist\DentistHasUnit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SolicitationController extends Controller
{
    public function __construct() {
        $this->middleware('check.permissions');
    }

    /**
     * Display a listing of Dentists.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $items = Dentist::select('*')
            ->where('name', 'ilike', '%'.$search.'%')
            ->orWhere('rg', 'ilike', '%'.$search.'%')
            ->orWhere('cpf', 'ilike', '%'.$search.'%')
            ->orderby('name', 'asc')->paginate(50);

        return view('siscop.solicitations.index', compact('items', 'search'));
    }

    /**
     * Show the list of existing Supplies.
     *
     * @return \Illuminate\Http\Response
     */
    public function citiesAjax(Request $request)
    {
        $search = $request->query('q');

        if ( empty($search) ) die;

        $items = City::select('CDMUNICIPIO AS value', 'NOME AS text')
            ->where('NOME', 'like', '"%'.$search.'%"')
            ->orderby('NOME', 'asc')
            ->get();

        return response() ->json( $items );
    }

    /**
     * Show the form for creating new Dentist.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $time = Time::orderBy('order')->get();
        $suspects = Suspect::orderBy('order')->get();
        $risks = Risks::orderBy('order')->get();
        $infos = Info::orderBy('order')->get();

        //dd(\Str::uuid());

        //$cities = City::get();

        return view('siscop.solicitations.create', compact( 'time', 'suspects', 'risks', 'infos' ));
    }

    /**
     * Store a newly created Dentist in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'phone'=>'required|max:120',
        ], [], [
            'phone' => __('siscop.solicitations.fields.phone'),
        ]);

        $dentist = Dentist::create($request->except('specialties'));

        $this->syncUnits($request, $dentist->id);
        $this->syncSpecialties($request, $dentist->id);

        return redirect()->route('dentist.dentists.index')->with('success', __('global.app_msg_store_success'));
    }

    /**
     * Show the form for editing Dentist.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Dentist::findOrFail($id);

        $units = Unit::get()->pluck('name', 'id');

        $myUnits = DentistHasUnit::where('dentist_id', $id)
            ->get()
            ->pluck('unit_id');

        $specialties = Specialty::get()->pluck('name', 'id');

        $mySpecialties = DentistHasSpecialty::where('dentist_id', $id)
            ->get()
            ->pluck('specialty_id');
    
        return view('dentist.dentists.edit', compact('item', 'specialties', 'mySpecialties', 'units', 'myUnits'));
    }

    /**
     * Update Dentist in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required|unique:dentist.dentists,name,'.$id.'|max:120',
            'rg'=>'required|unique:dentist.dentists,rg,'.$id.'|max:120',
            'cpf'=>'required|unique:dentist.dentists,cpf,'.$id.'|max:120',
        ], [], [
            'name' => __('global.dentist.dentists.fields.name'),
            'rg' => __('global.dentist.dentists.fields.rg'),
            'cpf' => __('global.dentist.dentists.fields.cpf'),
        ]);

        $item = Dentist::findOrFail($id);
        if ( $item->update($request->except('specialties')) )
        {
            $this->syncUnits($request, $id);
            $this->syncSpecialties($request, $id);
            return redirect()->route('dentist.dentists.index')->with('success', __('global.app_msg_update_success'));
        }
        else
        {
            return redirect()->route('dentist.dentists.index')->with('error', __('global.app_msg_update_error'));
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

        $entries = DentistHasSpecialty::where('dentist_id', $id)
            ->get();

        $staying = DentistHasSpecialty::where('dentist_id', $id)
            ->whereIn('specialty_id', $specialties)
            ->get()->pluck('specialty_id');

        $result = collect( $specialties )->diff($staying);

        $specialtyInsert = $result->map( function($item) {
            return [
                'dentist_id' => $this->id,
                'specialty_id' => $item,
            ];
        });

        $insert = DentistHasSpecialty::insert($specialtyInsert->toArray());

        $delete = DentistHasSpecialty::where('dentist_id', $id)
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

        $entries = DentistHasUnit::where('dentist_id', $id)
            ->get();

        $staying = DentistHasUnit::where('dentist_id', $id)
            ->whereIn('unit_id', $units)
            ->get()->pluck('unit_id');

        $result = collect( $units )->diff($staying);

        $unitInsert = $result->map( function($item) {
            return [
                'dentist_id' => $this->id,
                'unit_id' => $item,
            ];
        });

        $insert = DentistHasUnit::insert($unitInsert->toArray());

        $delete = DentistHasUnit::where('dentist_id', $id)
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
        $item = Dentist::findOrFail($id);
        $item->delete();

        return redirect()->route('dentist.dentists.index')->with('success', __('global.app_msg_destroy_success'));
    }

    /**
     * Delete all selected Dentists at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Dentist::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('dentist.dentists.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('dentist.dentists.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
