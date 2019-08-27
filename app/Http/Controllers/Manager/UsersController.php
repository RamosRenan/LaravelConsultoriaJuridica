<?php

namespace App\Http\Controllers\Manager;

use App\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function __construct() {
        $this->middleware('check.permissions');
    }

    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $myId = \Auth::user()->id;
        $me = User::findOrFail($myId);
        $myRoles = $me->getRoleNames();

        $this->myRoles = $myRoles->filter(function ($value, $key) {
            $value = ($value != '@@ superadmin @@' && $value != '@@ admin @@') ? $value : null;
            return $value != null;
        });

        $search = $request->query('search');

        $superAdminUsers = User::role('@@ superadmin @@')->get()->pluck('id'); 

        $items = User::select('users.*')
            ->where('users.name', 'ilike', '%'.$search.'%')
            ->whereNotIn('id', $superAdminUsers)
            ->orderby('users.name', 'asc')
            ->paginate(50);

        $items->each( function($item) {
            $item->roles = $this->myRoles->intersect($item->roles()->pluck('name'));
        });
            
        return view('admin.users.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('manager.users.index')->with('warning', __('users.msg.no_new_users'));

        $roles = Role::get()->pluck('name', 'name');
        $route = 'manager.users.store';

        return view('admin.users.create', compact('roles', 'route'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect()->route('manager.users.index')->with('warning', __('users.msg.no_new_users'));

        $this->validate($request, [
            'username'=>'required|unique:users|max:120',
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8|confirmed'
        ], [], [
            'username' => __('users.fields.username'),
            'name' => __('users.fields.name'),
            'email' => __('users.fields.email'),
            'password' => __('users.fields.password')
        ]
        );

        $item = User::create($request->except(['roles']));

        $roles = $request->input('roles') ? $request->input('roles') : [];
        $item->assignRole($roles);

        return redirect()->route('manager.users.index')->with('success', __('users.msg.store'));
    }


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $superadmin = ['readonly' => ''];
        $route = 'manager.users.update';

        $myId = \Auth::user()->id;
        $me = User::findOrFail($myId);
        $myRoles = $me->getRoleNames();
        
        $myRoles = $myRoles->filter(function ($value, $key) {
            $value = ($value != '@@ superadmin @@' && $value != '@@ admin @@') ? $value : null;
            return $value != null;
        });

        $roles = Role::whereIn('name', $myRoles)
            ->get()
            ->pluck('name', 'name');

        $item = User::findOrFail($id);
        $userRoles = $item->getRoleNames();

        $userHasRoles = $myRoles->intersect($userRoles);

        if ($myId != $id && ($userRoles->search('@@ superadmin @@') !== false)) {
            return redirect()->route('manager.users.index')->with('warning', __('manager.msg.cannot_edit_user'));
        }

        return view('admin.users.edit', compact('item', 'route', 'roles', 'superadmin'));
    }
    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $myId = \Auth::user()->id;
        $me = User::findOrFail($myId);
        $myRoles = $me->getRoleNames();

        $myRoles = $myRoles->filter(function ($value, $key) {
            $value = ($value != '@@ superadmin @@' && $value != '@@ admin @@') ? $value : null;
            return $value != null;
        });

        $item = User::findOrFail($id);
        $userRoles = $item->getRoleNames();
        $userHasRoles = $myRoles->intersect($userRoles);
        $userFinalRoles = $userRoles->diff($myRoles);

        if ($myId != $id && ($userRoles->search('@@ superadmin @@') !== false)) {
            return redirect()->route('manager.users.index')->with('warning', __('manager.msg.cannot_edit_user'));
        }

        $roles = $request->input('roles') ? $request->input('roles') : [];
        $roles = array_merge($userFinalRoles->toArray(), $roles);
        
        $item->syncRoles($roles);

        return redirect()->route('manager.users.index')->with('success', __('users.msg.update'));
    }
}
