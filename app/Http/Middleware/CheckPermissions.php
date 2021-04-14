<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
//use Illuminate\Support\Facades\Route;

class CheckPermissions {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $action = app('request')->route()->getAction();
        // $controller = class_basename($action['controller']);
        //$permission = str_replace($action['namespace'].'\\', '', $action['controller']);
        $permission = $action['as'];
         
        // No if é para validar os administradores 
        if ( Gate::allows('@@ admin @@') || Gate::allows('@@ superadmin @@')) {
            return $next($request);
        } else {
            if (! Gate::allows($permission)) {
                return abort(401);
            }
        }
        return $next($request);
    }
}
