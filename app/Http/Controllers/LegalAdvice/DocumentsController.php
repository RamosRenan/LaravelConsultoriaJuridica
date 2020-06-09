<?php

namespace App\Http\Controllers\LegalAdvice;

use App\Models\LegalAdvice\Doctype;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocumentsController extends Controller
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

        $items = Doctype::where('name', 'ilike', '%'.$search.'%')
            ->orderby('order', 'asc')->paginate(50);

        return view('legaladvice.doctypes.index', compact('items', 'search'));
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
            $item = Doctype::find($id);
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
        return view('legaladvice.doctypes.create');
    }

    /**
     * Store a newly created Unit in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|unique:legaladvice.doctypes|max:120',
        ], [], [
            'name' => __('legaladvice.doctypes.fields.name'),
        ]);

        $order = Doctype::orderBy('order', 'desc')->first()->order + 1;
        Doctype::create($request->all() + ['order' => $order]);

        return redirect()->route('legaladvice.doctypes.index')->with('success', __('global.app_msg_store_success'));
    }

    /**
     * Show the form for editing Unit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Doctype::findOrFail($id);

        return view('legaladvice.doctypes.edit', compact('id', 'item'));
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
            'name'=>'required|unique:legaladvice.doctypes,name,'.$id.'|max:120',
        ], [], [
            'name' => __('legaladvice.doctypes.fields.name'),
        ]);

        $item = Doctype::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('legaladvice.doctypes.index')->with('success', __('global.app_msg_update_success'));
    }


    /**
     * Remove Unit from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Doctype::findOrFail($id);
        $item->delete();

        return redirect()->route('legaladvice.doctypes.index')->with('success', __('global.app_msg_destroy_success'));
    }

    /**
     * Delete all selected Specialties at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Doctype::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('legaladvice.doctypes.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('legaladvice.doctypes.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
