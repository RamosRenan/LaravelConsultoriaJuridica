<?php

namespace App\Http\Controllers\Refectory;

use App\Models\Refectory\Unit;
use App\Models\Refectory\Supply;
use App\Models\Refectory\SupplyItem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
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

        $items = SupplyItem::select('units.id AS unit_id', 'units.name AS unit', 'supplies.id', 'supplies.name')
            ->selectRaw('MAX(date) AS date')
            ->selectRaw('SUM(quantity) AS quantity')
            ->join('units', 'supply_items.unit_id', '=', 'units.id')
            ->join('supplies', 'supply_items.supply_id', '=', 'supplies.id')
            ->where('supplies.name', 'ilike', '%'.$search.'%')
            ->orderby('supplies.name', 'asc')
            ->groupby('units.id', 'units.name', 'supplies.id', 'supplies.name')
            ->paginate(50);

        $items->each( function($item) {
            $item->date = date("d/m/Y", strtotime($item->date));
        });

        return view('refectory.stock.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new Supply.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $units = Unit::orderby('name', 'asc')->get()->pluck('name', 'id');

        $supplies = Supply::orderby('name', 'asc')->get()->pluck('name', 'id');

        return view('refectory.stock.create', compact('units', 'supplies'));
    }

    /**
     * Store a newly created Supply in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'supply_id'=>'required',
            'date'=>'required|date',
            'lot'=>'required',
            'quantity'=>'required|numeric',
            'price'=>'required|numeric',
        ], [], [
            'supply_id' => __('refectory.supplies.fields.name'),
            'date' => __('refectory.supplies.fields.date'),
            'lot' => __('refectory.supplies.fields.lot'),
            'quantity' => __('refectory.supplies.fields.quantity'),
            'price' => __('refectory.supplies.fields.price'),
        ]);

        SupplyItem::create($request->all());

        return redirect()->route('refectory.stock.index')->with('success', __('global.app_msg_store_success'));
    }

    /**
     * Show the form for editing Supply.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $nId = explode(',', $id);

        $units = Unit::orderby('name', 'asc')->get();

        $supply = Supply::findOrFail($nId[1]);
        
        $stock = SupplyItem::where('unit_id', $nId[0])
        ->where('supply_id', $nId[1])
        ->orderby('date', 'asc')->get();
        
        return view('refectory.stock.edit', compact('id', 'units', 'supply', 'stock'));
    }

    /**
     * Update Supply in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $itemId = $request['item_id'];

        $this->validate($request, [
            'date'=>'required|date',
            'lot'=>'required',
            'quantity'=>'required|numeric',
            'price'=>'required|numeric',
        ], [], [
            'date' => __('refectory.supplies.fields.date'),
            'lot' => __('refectory.supplies.fields.lot'),
            'quantity' => __('refectory.supplies.fields.quantity'),
            'price' => __('refectory.supplies.fields.price'),
        ]);

        $item = SupplyItem::findOrFail($itemId);
        $item->update($request->all());

        return redirect()->route('refectory.stock.edit', $id)->with('success', __('global.app_msg_update_success'));
    }

    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $itemId = $request['item_id'];

        $item = SupplyItem::findOrFail($itemId);
        $item->delete();

        return redirect()->route('refectory.stock.edit', $id)->with('success', __('global.app_msg_destroy_success'));
    }

    /**
     * Delete all selected supplies at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = SupplyItem::whereIn('supply_id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('refectory.stock.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('refectory.stock.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
