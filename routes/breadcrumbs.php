<?php
use App\Models\Admin\MenuItem;

Breadcrumbs::register('breadcrumb', function ($breadcrumbs) {
    $action = app('request')->route()->getAction();
    $controller = class_basename($action['controller']);
    list($controller, $action) = explode('@', $controller);

    $routeName = \Request::route()->getName();

    if($routeName) {;
        $breadcrumbs->push('Home', route('home.dashboard'));

        $routeName = explode( '.', $routeName );
        
        $routeName = $routeName[0].'.'.$routeName[1].'.'.'index';

        $item = MenuItem::where('route', $routeName)
        ->select('title')
        ->first();

        if ($item) { 
            $breadcrumbs->push( $item->title, route($routeName) );
        }

        if ($action == 'create') { 
            $breadcrumbs->push( __('global.app_create') );
        }
        if ($action == 'edit') { 
            $breadcrumbs->push( __('global.app_edit') );
        }
    }
});

