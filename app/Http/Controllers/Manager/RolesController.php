<?php

namespace App\Http\Controllers\Manager;

use App\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;



class RolesController extends Controller
{
    public function __construct() {
        $this->middleware('check.permissions');
    }

    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $myId = \Auth::user()->id;
        $me = User::findOrFail($myId);
        $myRoles = $me->getRoleNames();

        if (Gate::allows('@@ superadmin @@')) {
            $myRoles = Role::all()->pluck('name');
        }

        $items = Role::where('name', 'ilike', '%'.$search.'%')
            ->where('roles.name', '<>', '@@ superadmin @@')
            ->where('roles.name', '<>', '@@ admin @@')
            ->whereIn('roles.name', $myRoles)
            ->orderby('name', 'asc')
            ->paginate(50);

        return view('admin.roles.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $routeURL = 'manager.roles.store';

        $myId = \Auth::user()->id;
        $me = User::findOrFail($myId);
        $permissions = $me->getAllPermissions()->pluck('name', 'name');

        $permissions = $permissions->filter(function ($value, $key) {
            return $value != '@@ admin @@';
        });

        $routeCollection = Route::getRoutes();
        $routes = [];

        foreach ($routeCollection as $route) {
            $action = $route->getAction();

            if (array_key_exists('controller', $action) && in_array('auth', $action['middleware']) && !strstr($action['controller'], 'HomeController') && !strstr($action['controller'], 'Admin') && !strstr($action['controller'], 'Manager')) {
                $explodedAction = explode('@', $action['controller']);
                
                $controllerArray = explode('\\', $explodedAction[0]);
                $controllerName = end($controllerArray);
                $moduleName =  prev($controllerArray);
                                

                if (in_array($route->getName(), $permissions->toArray())) {
                    if (!isset($routes[$moduleName][$controllerName])) {
                        $routes[$moduleName][$controllerName] = [];
                    }
                    
                    $modules[$moduleName] = $moduleName;
                    $routes[$moduleName][$controllerName][$explodedAction[1]] = $route->getName();
                }
            }
        }

        return view('admin.roles.create', compact('routeURL', 'permissions', 'routes', 'modules'));
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param  \App\Http\Requests\StoreRolesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        function recursive_array_search($needle,$haystack) {
            foreach($haystack as $key=>$value) {
                $current_key=$key;
                if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
                    return $current_key;
                }
            }
            return false;
        }

        $this->permissionsList = $request['permission'];

        $this->validate($request, [
            'name'=>'required|unique:roles|max:120',
            ], [], [
            'name' => __('roles.fields.name'),
            ]
        );

        if ($request['mode'] == "default") {
            $item = Role::create($request->only('name'));

            if ($item == true) {
                $myId = \Auth::user()->id;
                $me = User::findOrFail($myId);
                $me->assignRole($request->only('name'));
            }

            $permissions = $request->input('permission') ? $request->input('permission') : [];
            $item->givePermissionTo($permissions);
    
            return redirect()->route('manager.roles.index')->with('success', __('roles.msg.store'));
        } 
        elseif ($request['mode'] == "advanced") {
            $permissions = collect($request['permission'])->collapse();

            $registeredPermissions = Permission::whereIn('name', $permissions)
                ->where('guard_name', 'web')
                ->get()
                ->pluck('name');
            
                
            $addPermissions = $permissions->diff($registeredPermissions);

            $addPermissions->each(function($item) {
                Permission::create([
                    'name' => $item,
                    'module' => recursive_array_search($item, $this->permissionsList)
                ]);
            });

            $item = Role::create($request->only('name'));

            if ($item == true) {
                $myId = \Auth::user()->id;
                $me = User::findOrFail($myId);
                $me->assignRole($request->only('name'));
            }

            $permissions = $request->input('permission') ? $request->input('permission') : [];
            $item->givePermissionTo($permissions);

            return redirect()->route('manager.roles.index')->with('success', __('roles.msg.store'));
        }
    }

    /**
     * Show the form for editing Role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $routeURL = 'manager.roles.update';

        $myId = \Auth::user()->id;
        $me = User::findOrFail($myId);
        $permissions = $me->getAllPermissions()->pluck('name', 'name');

        $permissions = $permissions->filter(function ($value, $key) {
            return $value != '@@ admin @@';
        });
        
        $myRoles = $me->getRoleNames();

        $item = Role::findOrFail($id);
        $myPermissions = $item->permissions()->pluck('name', 'name');

        if (!in_array($item['name'], $myRoles->toArray()) || $myPermissions->search('@@ superadmin @@') || $myPermissions->search('@@ admin @@')) {
            return redirect()->route('manager.roles.index')->with('warning', __('manager.msg.cannot_edit_role'));
        }

        $myPermissions = $myPermissions->toArray();

        $routeCollection = Route::getRoutes();
        $routes = [];

        foreach ($routeCollection as $route) {
            $action = $route->getAction();
            if (array_key_exists('controller', $action) && in_array('auth', $action['middleware']) && !strstr($action['controller'], 'HomeController') && !strstr($action['controller'], 'Admin') && !strstr($action['controller'], 'Manager')) {
                $explodedAction = explode('@', $action['controller']);
                
                $controllerArray = explode('\\', $explodedAction[0]);
                $controllerName = end($controllerArray);
                $moduleName =  prev($controllerArray);
                                
                if (in_array($route->getName(), $permissions->toArray())) {
                    if (!isset($routes[$moduleName][$controllerName])) {
                        $routes[$moduleName][$controllerName] = [];
                    }
                    
                    $modules[$moduleName] = $moduleName;
                    $routes[$moduleName][$controllerName][$explodedAction[1]] = $route->getName();

                }
            }
        }

        return view('admin.roles.edit', compact('routeURL', 'item', 'permissions', 'myPermissions', 'routes', 'modules'));
    }

    /**
     * Update Role in storage.
     *
     * @param  \App\Http\Requests\UpdateRolesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        function recursive_array_search($needle,$haystack) {
            foreach($haystack as $key=>$value) {
                $current_key=$key;
                if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
                    return $current_key;
                }
            }
            return false;
        }

        $item = Role::findOrFail($id);
        $myPermissions = $item->permissions()->pluck('name', 'name');

        $myId = \Auth::user()->id;
        $me = User::findOrFail($myId);
        $myRoles = $me->getRoleNames();
        
        if (!in_array($item['name'], $myRoles->toArray()) || $myPermissions->search('@@ superadmin @@') || $myPermissions->search('@@ admin @@')) {
            return redirect()->route('manager.roles.index')->with('warning', __('manager.msg.cannot_edit_role'));
        }

        $this->permissionsList = $request['permission'];

        $this->validate($request, [
            'name'=>'required|unique:roles,name,'.$id.'|max:120',
            ], [], [
            'name' => __('roles.fields.name'),
            ]
        );

        if ($request['mode'] == "default") {
            $item = Role::findOrFail($id);
            $item->update($request->only('name'));
    
            $permissions = $request->input('permission') ? $request->input('permission') : [];
            $item->syncPermissions($permissions);
    
            return redirect()->route('manager.roles.index')->with('success', __('roles.msg.update'));
        } 
        elseif ($request['mode'] == "advanced") {
            $permissions = collect($request['permission'])->collapse();

            $registeredPermissions = Permission::whereIn('name', $permissions)
                ->where('guard_name', 'web')
                ->get()
                ->pluck('name');
            
                
            $addPermissions = $permissions->diff($registeredPermissions);

            $addPermissions->each(function($item) {
                Permission::create([
                    'name' => $item,
                    'module' => recursive_array_search($item, $this->permissionsList)
                ]);
            });

            $item = Role::findOrFail($id);
            $item->update($request->only('name'));
    
            $permissions = $request->input('permission') ? $request->input('permission') : [];
            $item->syncPermissions($permissions);
    
            return redirect()->route('manager.roles.index')->with('success', __('roles.msg.update'));
        }
    }


    /**
     * Remove Role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Role::findOrFail($id);
        $myPermissions = $item->permissions()->pluck('name', 'name');

        if ($myPermissions->search('@@ superadmin @@') || $myPermissions->search('@@ admin @@')) {
            return redirect()->route('manager.roles.index')->with('warning', __('manager.msg.cannot_edit_role'));
        }

        $item->delete();

        return redirect()->route('manager.roles.index')->with('success', __('roles.msg.destroy'));
    }

    /**
     * Delete all selected Role at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Role::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('manager.roles.index')->with('success', __('roles.msg.mass_destroy'));
        } else {
            return redirect()->route('manager.roles.index')->with('error', __('roles.msg.select_to_mass_destroy'));
        }
    }
}
