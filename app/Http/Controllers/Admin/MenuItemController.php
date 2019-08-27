<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Menu;
use App\Models\Admin\MenuItem;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;
use Validator;

class MenuItemController extends Controller
{
    /**
     * Display a listing of Procedures.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $id = $request->id;
        
        $menuItems = MenuItem::where('menu_id', $id)
            ->orderBy('parent_id', 'asc')
            ->orderBy('order', 'asc')
            ->get();

        $menuTree = collect ( $this->mkTree( $menuItems ) );
    
        return view('admin.menuitems.index', compact('menuTree'));
    }

    /**
     * Show the form for creating new Procedure.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $menuId = $request['id'];

        $menuParent = MenuItem::where('menu_id', $menuId)
            ->orderBy('parent_id')
            ->orderBy('order')
            ->get()
            ->pluck('title', 'id');

        $menuParent = collect([0 => __('menu.principal')] + $menuParent->all());

        $routeNames = Route::getRoutes();
        $routeNames = $routeNames->getRoutesByName();

        foreach($routeNames as $name => $content) {
            if ((in_array('GET', $content->methods) || in_array('HEAD', $content->methods)) && (!strstr($name, 'edit') && !strstr($name, 'show'))) {
                $routeList[$name] = $name;
            }
        }

        $permissions = Permission::where('name','NOT ILIKE','%@edit')
            ->where('name','NOT ILIKE','%@show')
            ->where('name','NOT ILIKE','%@store')
            ->where('name','NOT ILIKE','%@update')
            ->where('name','NOT ILIKE','%@destroy')
            ->where('name','NOT ILIKE','%@massDestroy')
            ->orderBy('module', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->pluck('module', 'name');

        $permissions = $permissions->map(function($item, $key) {
            return $item . ' - ' . $key;
        });

        $colors = collect([ 'primary', 'info', 'success', 'warning', 'danger', 'gray', 'navy', 'teal', 'purple', 'orange', 'maroon', 'black' ]);
        $colors = $colors->combine($colors);

        return view('admin.menuitems.create', compact('menuId', 'menuParent', 'routeList', 'permissions', 'colors'));
    }

    /**
     * Store a newly created Procedure in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null) {
        $rules = [
            'menu_id'=>'required|integer',
            'title'=>'required|max:120',
        ];
        
        $attributes = [
            'menu_id' => __('menuitem.fields.parent'),
            'title' => __('menuitem.fields.title'),
        ];

        $errors = Validator::make($request->all(), $rules, [], $attributes);

        if($errors->fails()) {
            return response()->json(['code' => 'error', 'messages' => $errors->errors()->all()]);
        }

        $router = app('Illuminate\Routing\Router');

        if ( strstr($request['route'], 'show') <> false ) {
            $routeArgs = explode(',', $request['route']);
            $route = (!empty($routeArgs[1])) ? route($routeArgs[0], $routeArgs[1]) : '[invalidRoute]';
        } elseif ($router->getRoutes()->getByName($request['route'])) {
            $route = route($request['route']);
        }

        if ( empty($request['route']) || ! is_null($routeSys = $router->getRoutes()->getByName($request['route'])) ) {
            $menuOrder = MenuItem::where('parent_id', $request->parent_id)
                ->orderBy('order', 'DESC')
                ->first();

            $menuOrder = ($menuOrder == null) ? 0 : $menuOrder->order + 1;

            $form = array_merge($request->all(), ['order' => $menuOrder]);

            MenuItem::create($form);

            return response()->json(['code' => 'success', 'messages' => __('menu.msg.storemenuitem')]);
        } else {
            return response()->json(['code' => 'error', 'messages' => __('menu.msg.inexistent_route')]);
        }
    }

    /**
     * Show the form for editing Procedure.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $item = MenuItem::findOrFail($id);

        $menuParent = MenuItem::where('menu_id', $item->menu_id)
            ->orderBy('parent_id')
            ->orderBy('order')
            ->get()
            ->pluck('title', 'id');

        $menuParent = collect([0 => __('menu.principal')] + $menuParent->all());

        $routeNames = Route::getRoutes();
        $routeNames = $routeNames->getRoutesByName();

        foreach($routeNames as $name => $content) {
            if ((in_array('GET', $content->methods) || in_array('HEAD', $content->methods)) && (!strstr($name, 'edit') && !strstr($name, 'show'))) {
                $routeList[$name] = $name;
            }
        }

        $parents = Menu::get()->pluck('name', 'id');

        $permissions = Permission::where('name','NOT ILIKE','%@edit')
            ->where('name','NOT ILIKE','%@show')
            ->where('name','NOT ILIKE','%@store')
            ->where('name','NOT ILIKE','%@update')
            ->where('name','NOT ILIKE','%@destroy')
            ->where('name','NOT ILIKE','%@massDestroy')
            ->orderBy('module', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->pluck('module', 'name');

        $permissions = $permissions->map(function($item, $key) {
            return $item . ' - ' . $key;
        });

        $colors = collect([ 'primary', 'info', 'success', 'warning', 'danger', 'gray', 'navy', 'teal', 'purple', 'orange', 'maroon', 'black' ]);
        $colors = $colors->combine($colors);

        return view('admin.menuitems.edit', compact('id', 'item', 'menuParent', 'routeList', 'parents', 'permissions', 'colors'));
    }

    /**
     * Update Procedure in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $rules = [
            'parent_id'=>'required|integer',
            'title'=>'required|max:120',
        ];
        
        $attributes = [
            'parent_id' => __('menu.fields.parent'),
            'title' => __('menu.fields.title'),
        ];

        $errors = Validator::make($request->all(), $rules, [], $attributes);

        if($errors->fails()) {
            return response()->json(['code' => 'error', 'messages' => $errors->errors()->all()]);
        }

        $item = MenuItem::findOrFail($id);

        $router = app('Illuminate\Routing\Router');
        
        $routeArgs = explode(',', $request['route']);

        if ( empty($request['route']) || ! is_null($routeSys = $router->getRoutes()->getByName($routeArgs[0])) )
        {
            $menuItem = MenuItem::findOrFail($id);

            if ($menuItem->parent_id != $request->parent_id) {
                $menuOrder = MenuItem::where('parent_id', $request->parent_id)
                    ->orderBy('order', 'DESC')
                    ->first();

                $menuOrder = ($menuOrder == null) ? 0 : $menuOrder->order + 1;

                $form = array_merge($request->all(), ['order' => $menuOrder]);
            } else {
                $form = $request->all();
            }

            $menuItem->update($form);

            return response()->json(['code' => 'success', /*'messages' => __('menu.msg.updatemenuitem')*/]);
        } else {
            return response()->json(['code' => 'error', 'messages' => __('menu.msg.inexistent_route')]);
        }
    }

    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        $item = MenuItem::findOrFail($id);
        if ($item->delete()) {
            return response()->json(['code' => 'success', 'messages' => __('menu.msg.destroymenuitem')]);
        } else {
            return response()->json(['code' => 'error', 'messages' => __('global.app_msg_destroy_error')]);
        }
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
