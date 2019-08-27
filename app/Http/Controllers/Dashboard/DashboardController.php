<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Admin\Dashboard;
use App\Models\Admin\DashboardItem;

use App\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
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

        if (Gate::allows('@@ superadmin @@')) {
            $myRoles = Role::all()->pluck('name');
        }

        $dashboard = Dashboard::select('dashboards.*')
            ->whereIn('dashboards.role', $myRoles)
            ->get();
            
        $authorizedWidgets = DashboardItem::select('dashboard_items.id', 'dashboards.name AS name', 'dashboards.title', 'dashboards.grid')
            ->join('dashboards', 'dashboards.id', '=', 'dashboard_items.dashboard_id')
            ->whereIn('dashboards.role', $myRoles)
            ->where('user_id', $myId)
            ->orderBy('order')
            ->get();

        $unauthorizedWidgets = DashboardItem::select('dashboard_items.id', 'dashboards.name AS name', 'dashboards.title')
            ->join('dashboards', 'dashboards.id', '=', 'dashboard_items.dashboard_id')
            ->whereNotIn('dashboards.role', $myRoles)
            ->where('user_id', $myId)
            ->orderBy('order')
            ->get();

        $items = $dashboard->pluck('name', 'id')->diff($authorizedWidgets->pluck('name', 'id'));

        return view('admin.dashboard.config', compact('items', 'dashboard', 'authorizedWidgets', 'unauthorizedWidgets'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $myId = \Auth::user()->id;

        DashboardItem::create(['user_id'=>$myId, 'dashboard_id'=>$request['dashboard_id'], 'order'=>0]);

        return redirect()->route('home.dashboard.index')->with('success', __('dashboard.msg.store'));
    }

    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $nobreadcrumbs = true;

        $myId = \Auth::user()->id;
        $me = User::findOrFail($myId);
        $myRoles = $me->getRoleNames();

        if (Gate::allows('@@ superadmin @@')) {
            $myRoles = Role::all()->pluck('name');
        }

        $items = DashboardItem::select('dashboards.name AS name')
            ->join('dashboards', 'dashboards.id', '=', 'dashboard_items.dashboard_id')
            ->whereIn('dashboards.role', $myRoles)
            ->where('user_id', $myId)
            ->orderBy('order')
            ->get();

        return view('admin.dashboard.show', compact('items', 'nobreadcrumbs'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateListAjax(Request $request)
    {
        $order = collect(explode(',', $request['q']));
        $order = $order->each(function ($id, $order) {
            $item = DashboardItem::find($id);
            $item->order = $order;
            $item->save();
        });

        dd($order);
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = DashboardItem::findOrFail($id);
        $item->delete();

        return redirect()->route('home.dashboard.index')->with('success', __('dashboard.msg.destroy'));
    }
}
