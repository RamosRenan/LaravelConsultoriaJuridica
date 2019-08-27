<?php

namespace App\Http\Controllers\Admin;

use App\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function __construct() {
        $this->middleware(['check.permissions', 'superadmin']);
    }

    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $search = $request->query('search');

        $items = User::where('name', 'ilike', '%'.$search.'%')
            ->orderby('name', 'asc')
            ->paginate(50);

        $items->each( function($item) {
            $item->roles = $item->roles()->pluck('name');
        });

        return view('admin.users.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return redirect()->route('admin.users.index')->with('warning', __('users.msg.no_new_users'));
        
        $roles = Role::get()->pluck('name', 'name');
        $route = 'admin.users.store';

        return view('admin.users.create', compact('roles', 'route'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        return redirect()->route('admin.users.index')->with('warning', __('users.msg.no_new_users'));

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

        return redirect()->route('admin.users.index')->with('success', __('users.msg.store'));
    }

    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $route = 'admin.users.update';
        $roles = Role::get()->pluck('name', 'name');
        $item = User::findOrFail($id);

        return view('admin.users.edit', compact('route', 'roles', 'item'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $item = User::findOrFail($id);

        if ( $request['password'] ) {
            $this->validate($request, [
                'username'=>'required|unique:users|max:120',
                'name'=>'required|max:120',
                'email'=>'required|email|unique:users,email,'.$id,
                'password'=>'required|min:8|confirmed'
            ], [], [
                'username' => __('users.fields.username'),
                'name' => __('users.fields.name'),
                'email' => __('users.fields.email'),
                'password' => __('users.fields.password')
            ]
            );

            $input = $request->except(['roles']);
        } else {
            $this->validate($request, [
                'username'=>'required|unique:users,username,'.$id.'|max:120',
                'name'=>'required|max:120',
                'email'=>'required|email|unique:users,email,'.$id,
            ], [], [
                'username' => __('users.fields.username'),
                'name' => __('users.fields.name'),
                'email' => __('users.fields.email'),
            ]
            );

            $input = $request->only(['username', 'name', 'email']);
        }

        $item->update($input);
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $item->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('success', __('users.msg.update'));
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $item = User::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.users.index')->with('success', __('users.msg.destroy'));
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request) {
        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }

            return redirect()->route('admin.users.index')->with('success', __('users.msg.mass_destroy'));
        } else {
            return redirect()->route('admin.users.index')->with('error', __('users.msg.select_to_mass_destroy'));
        }
    }
}