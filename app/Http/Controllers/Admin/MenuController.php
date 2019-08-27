<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Menu;
use App\Models\Admin\MenuItem;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class MenuController extends Controller
{
    public function __construct() {
        $this->middleware(['check.permissions', 'superadmin']);
    }

    /**
     * Display the list of Menus.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $search = $request->query('search');

        $items = Menu::where('name', 'ilike', '%'.$search.'%')
            ->orderby('name', 'asc')
            ->paginate(50);

        return view('admin.menu.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new Menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $colors = collect([ 'primary', 'info', 'success', 'warning', 'danger', 'gray', 'navy', 'teal', 'purple', 'orange', 'maroon', 'black' ]);

        $parents = Menu::get()->pluck('name', 'id');
        $permissions = Permission::get()->pluck('name', 'name');
        $colors = $colors->combine($colors);

        return view('admin.menu.create', compact('parents', 'permissions', 'colors'));
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param  \App\Http\Requests\StorePermissionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name'=>'required|unique:menus|max:120',
            'call'=>'required|unique:menus|max:120',
        ], [], [
            'name' => __('menu.fields.name'),
            'call' => __('menu.fields.call'),
        ]);

        Menu::create($request->all());

        return redirect()->route('admin.menu.index')->with('success', __('menu.msg.store'));
    }

    /**
     * Show the form for editing Permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $menu = Menu::findOrFail($id);

        return view('admin.menu.edit', compact('id', 'menu'));
    }

    /**
     * Update Permission in storage.
     *
     * @param  \App\Http\Requests\UpdatePermissionsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'name'=>'required|unique:menus,name,'.$id.'|max:120',
            'call'=>'required|unique:menus,call,'.$id.'|max:120',
        ], [], [
            'name' => __('menu.fields.name'),
            'call' => __('menu.fields.call'),
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update($request->all());

        return redirect()->route('admin.menu.index')->with('success', __('menu.msg.update'));
    }

    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('admin.menu.index')->with('success', __('menu.msg.destroy'));
    }

    /**
     * Delete all selected Permission at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request) {
        if ($request->input('ids')) {
            $entries = Menu::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('admin.menu.index')->with('success', __('menu.msg.mass_destroy'));
        } else {
            return redirect()->route('admin.menu.index')->with('error', __('menu.msg.select_to_mass_destroy'));
        }
    }

    /**
     * Update resursively the called menu when it is ordered.
     *
     * @return \Illuminate\Http\Response
     */
    public function menuUpdateRecursive( $source, $parent_id = 0 ) {
        foreach( $source as $order => $items ) 
        {
            $menu = MenuItem::find($items->id);
            $menu->parent_id = $parent_id;
            $menu->order = $order;
            $menu->save();

            echo 'id=' . $items->id .' - pid='. $parent_id .' - order='. $order . '<br>';

            if ( is_array(@$items->children)) {
                $this->menuUpdateRecursive( $items->children, $items->id );
            }
        }
    }

    /**
     * Call the tree menu and builds it.
     *
     * @return \Illuminate\Http\Response
     */
    public function menuUpdateAjax(Request $request) {
        $source = collect( json_decode( $request->query( 'out' ) ) );
        $this->menuUpdateRecursive($source);
    }

    /**
     * Make the array that create the menu tree.
     *
     * @return \Illuminate\Http\Response
     */
    public function mkTree($source, $parent_id = 0) {
        $menu = [];

        foreach($source as $id => $row) {
            if ($row->parent_id == $parent_id) {
                $menu[$id]['id'] = $row->id;
                $menu[$id]['menu_id'] = $row->menu_id;
                $menu[$id]['parent_id'] = $row->parent_id;
                $menu[$id]['order'] = $row->order;
                $menu[$id]['title'] = $row->title;
                $menu[$id]['icon'] = $row->icon;
                $menu[$id]['permission'] = $row->permission;
                $menu[$id]['route'] = $row->route;
                $menu[$id]['url'] = $row->url;
                $menu[$id]['color'] = $row->color;

                $menu[$id]['submenu'] = $this->mkTree($source, $row->id);
            }
        }

        return $menu;
    }
}
