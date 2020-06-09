<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Dashboard;
use App\Models\Admin\DashboardItem;

use App\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
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

        $items = Dashboard::where('name', 'ilike', '%'.$search.'%')
            ->orderby('name', 'asc')
            ->paginate(50);

        return view('admin.dashboard.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $dashboard = Dashboard::get()->pluck('name', 'name');
        
        if ($handle = opendir(__DIR__.'/../../../Widgets')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $file = str_replace('.php', '',  $entry);
                    $dirs[$file] = $file;
                }
            }
            closedir($handle);
        }

        $dirs = collect($dirs)->diff($dashboard);
        $roles = Role::get()->pluck('name', 'name')->prepend('');
        $grids = ['', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        
        return view('admin.dashboard.create', compact('roles', 'dirs', 'grids'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'title'=>'required|unique:dashboards|max:120',
            'name'=>'required|unique:dashboards|max:120',
            'role'=>'required',
            ], [], [
            'title' => __('dashboard.fields.title'),
            'name' => __('dashboard.fields.name'),
            'role' => __('dashboard.fields.role'),
            ]
        );

        Dashboard::create($request->except('_token'));

        return redirect()->route('admin.dashboard.index')->with('success', __('dashboard.msg.store'));
    }

    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $dashboard = Dashboard::get()->pluck('name', 'name');
        
        if ($handle = opendir(__DIR__.'/../../../Widgets')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $file = str_replace('.php', '',  $entry);
                    $dirs[$file] = $file;
                }
            }
        
            closedir($handle);
        }

        $item = Dashboard::findOrFail($id);
        $dirs = collect($dirs);
        $roles = Role::get()->pluck('name', 'name')->prepend('');
        $grids = ['', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        return view('admin.dashboard.edit', compact('item', 'dirs', 'roles', 'grids'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $item = Dashboard::findOrFail($id);

        $this->validate($request, [
            'title'=>'required|max:120',
            'name'=>'required|unique:dashboards,name,'.$id.'|max:120',
            'role'=>'required',
            ], [], [
            'title' => __('dashboard.fields.title'),
            'name' => __('dashboard.fields.name'),
            'role' => __('dashboard.fields.role'),
        ]);

        $input = $request->except(['roles']);

        $item->update($input);

        return redirect()->route('admin.dashboard.index')->with('success', __('dashboard.msg.update'));
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = Dashboard::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dashboard.index')->with('success', __('dashboard.msg.destroy'));
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request) {
        if ($request->input('ids')) {
            $entries = Dashboard::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }

            return redirect()->route('admin.dashboard.index')->with('success', __('dashboard.msg.mass_destroy'));
        } else {
            return redirect()->route('admin.dashboard.index')->with('error', __('dashboard.msg.select_to_mass_destroy'));
        }

    }
}
