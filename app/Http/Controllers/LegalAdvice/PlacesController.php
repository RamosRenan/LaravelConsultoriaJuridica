<?php

namespace App\Http\Controllers\LegalAdvice;

use App\Models\LegalAdvice\Place;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlacesController extends Controller
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

        $items = Place::where('name', 'ilike', '%'.$search.'%')
            ->orderby('order', 'asc')->paginate(50);

        return view('legaladvice.places.index', compact('items', 'search'));
    }

    /**
     * Update items order.
     *
     * @param  \App\Http\Requests\Order  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {
        $order = collect(explode(',', $request['q']));
        $order = $order->each(function ($id, $order) {
            $item = Place::find($id);
            $item->order = $order;
            $item->save();
        });
    }

    /**
     * Show the form for creating new Unit.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('legaladvice.places.create');
    }

    /**
     * Store a newly created Unit in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|unique:legaladvice.places|max:120',
        ], [], [
            'name' => __('legaladvice.places.fields.name'),
        ]);

        Place::create($request->all());

        return redirect()->route('legaladvice.places.index')->with('success', __('global.app_msg_store_success'));
    }

    /**
     * Show the form for editing Unit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Place::findOrFail($id);

        return view('legaladvice.places.edit', compact('id', 'item'));
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
            'name'=>'required|unique:legaladvice.places,name,'.$id.'|max:120',
        ], [], [
            'name' => __('legaladvice.places.fields.name'),
        ]);

        $item = Place::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('legaladvice.places.index')->with('success', __('global.app_msg_update_success'));
    }


    /**
     * Remove Unit from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Place::findOrFail($id);
        $item->delete();

        return redirect()->route('legaladvice.places.index')->with('success', __('global.app_msg_destroy_success'));
    }

    /**
     * Delete all selected Specialties at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Place::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('legaladvice.places.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('legaladvice.places.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
