<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CheckIfIsSuperadmin
{
    /**
     * Check if the user has "@@ superadmin @@" permission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( Gate::allows('@@ superadmin @@') ) {
            return $next($request);
        } else {
            return abort(401);
        }

        return $next($request);
    }
}
