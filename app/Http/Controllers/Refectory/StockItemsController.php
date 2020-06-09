<?php

namespace App\Http\Controllers\Refectory;

use App\Models\Admin\Unit;
use App\Models\Refectory\Supply;
use App\Models\Refectory\StockContract;
use App\Models\Refectory\StockItem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockItemsController extends Controller
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

        $items2 = StockItem::select('supplies.name', 'stock_items.*')
            ->join('supplies', 'stock_items.supply_id', '=', 'supplies.id')
            ->where('supplies.name', 'ilike', '%'.$search.'%')
            ->orderby('supplies.name', 'asc')
            ->paginate(50);

        $items->each( function($item) {
            $item->unit = $this->units[$item->unit_id];
            $item->date = date("d/m/Y", strtotime($item->date));
            //$item->quantity = number_format($item->quantity, 2, ',', '.');
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
        $supplies = Supply::orderby('name', 'asc')->get()->pluck('name', 'id');

        return view('refectory.stockitems.create', compact('supplies'));
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
            'lot'=>'required',
            'expiration'=>'required|date',
            'quantity'=>'required',
            'price'=>'required',
        ], [], [
            'supply_id'=>'refectory.stockitems.supply',
            'lot'=>'refectory.stockitems.lot',
            'expiration'=>'refectory.stockitems.expiration',
            'quantity'=>'refectory.stockitems.quantity',
            'price'=>'refectory.stockitems.price',
        ]);

        $id = StockContract::create($request->all())->id;

        return redirect()->route('refectory.stock.edit', $id)->with('success', __('global.app_msg_store_success'));
    }

    /**
     * Show the form for editing Supply.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $units = Unit::orderby('name', 'asc')->get()->pluck('name', 'id');

        $supplies = Supply::orderby('name', 'asc')->get()->pluck('name', 'id');

        $item = StockContract::select('*')
            //->join('supplies', 'stock_items.supply_id', '=', 'supplies.id')
            ->findOrFail($id);

        return view('refectory.stock.edit', compact('id', 'units', 'supplies', 'item'));
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

        $item = StockItem::findOrFail($id);
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

        $item = StockItem::findOrFail($itemId);
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
            $entries = StockItem::whereIn('supply_id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('refectory.stock.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('refectory.stock.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
