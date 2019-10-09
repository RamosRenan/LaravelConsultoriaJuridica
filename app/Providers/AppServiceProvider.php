<?php

namespace App\Providers;

use App\Models\Admin\MenuItem;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Gate;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
         $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $lista = [];

            $isSuperAdmin = Gate::allows('@@ superadmin @@');

            $menuTree = MenuItem::where('call', 'admin')
                ->join('menus', 'menus.id', '=', 'menu_items.menu_id')
                ->select('menu_items.*', 'menus.call')
                ->orderBy('order', 'asc')
                ->get();
            
            $menuTree = collect ( $this->mkTree( $menuTree ) );

            foreach ($menuTree as $item) {
                if ($item['url']) {
                    $lista[] = [
                        'parent_id' => $item['parent_id'],
                        'text' => $item['text'],
                        'icon' => $item['icon'],
                        'url' => $item['url'],
                        'label' => '',
                        'color' => $item['color'],
                        'submenu' => @$item['submenu'],
                    ];
                } else {
                    $lista[] = [
                        'header' => $item['text'] . $item['url'],
                    ];

                    if( @$item['submenu'] )
                    foreach ($item['submenu'] as $submenu) {
    
                        $lista[] = [
                            'parent_id' => $submenu['parent_id'],
                            'text' => $submenu['text'],
                            'icon' => $submenu['icon'],
                            'url' => $submenu['url'],
                            'label' => '',
                            'color' => $submenu['color'],
                            'submenu' => @$submenu['submenu'],
                        ];
                    }
                }
            }

            if( $lista ) {
                foreach ($lista as $item) {
                    $event->menu->add($item);
                }
            }
        });
    }

    public function mkTree($source, $parent_id = 0)
    {
        $menu = [];

        $router = app('Illuminate\Routing\Router');
        $user = auth()->user();
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        foreach($source as $id => $row) {
            $routeArgs = explode(',', $row->route);

            if ( strstr($row->route, 'show') <> false ) 
            {
                $url = (!empty($routeArgs[1])) ? route($routeArgs[0], $routeArgs[1]) : '[invalidRoute]';
            }
            elseif ( ! is_null($router->getRoutes()->getByName($routeArgs[0])) )
            {
                $url = route($routeArgs[0], @$routeArgs[1]);
            } 
            else 
            {
                $url = $row->url;
            }
    
            if ($row->parent_id == $parent_id) {
                $submenu = $this->mkTree($source, $row->id);

                if (
                    Gate::allows('@@ superadmin @@') === true 
                    || in_array($row->permission, $permissions) 
                    || $row->permission === null && count($submenu) > 0
                    
                ) {
                    $menu[$id]['parent_id'] = $row->parent_id;
                    $menu[$id]['text'] = $row->title;
                    $menu[$id]['icon'] = $row->icon;
                    $menu[$id]['url'] = $url;
                    $menu[$id]['color'] = $row->color;
                    $menu[$id]['can'] = $row->permission;


                    if (count($submenu) > 0) {
                        $menu[$id]['submenu'] = $submenu;
                    }
                }

            }
        }
        return $menu;
    }
}
