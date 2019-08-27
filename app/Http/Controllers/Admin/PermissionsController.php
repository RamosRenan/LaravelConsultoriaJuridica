<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    public function __construct() {
        $this->middleware(['check.permissions', 'superadmin']);
    }

    /**
     * Display a listing of Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $search = $request->query('search');

        $items = Permission::where('name', 'ilike', '%'.$search.'%')
            ->orderby('module', 'asc')
            ->orderby('name', 'asc')
            ->paginate(50);

        return view('admin.permissions.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param  \App\Http\Requests\StorePermissionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name'=>'required|unique:permissions|max:120',
            ], [], [
            'name' => __('permissions.fields.name'),
        ]);

        Permission::create($request->all());

        return redirect()->route('admin.permissions.index')->with('success', __('permissions.msg.store'));
    }


    /**
     * Show the form for editing Permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $permission = Permission::findOrFail($id);

        return view('admin.permissions.edit', compact('permission'));
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
            'name'=>'required|unique:permissions,name,'.$id.'|max:120',
            ], [], [
            'name' => __('permissions.fields.name'),
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update($request->all());

        return redirect()->route('admin.permissions.index')->with('success', __('permissions.msg.update'));
    }

    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', __('permissions.msg.destroy'));
    }

    /**
     * Delete all selected Permission at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request) {
        if ($request->input('ids')) {
            $entries = Permission::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('admin.permissions.index')->with('success', __('permissions.msg.mass_destroy'));
        } else {
            return redirect()->route('admin.permissions.index')->with('error', __('permissions.msg.select_to_mass_destroy'));
        }
    }
}
