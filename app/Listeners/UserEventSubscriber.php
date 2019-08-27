<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function onUserLogin($event)
    {
        $tokenAccess = bcrypt(date('YmdHms'));
 
        if ($user = auth()->user()) {
            $user->token_access = $tokenAccess;
            $user->save();
     
            session()->put('access_token', $tokenAccess);
            
            // Registra o acesso do usuário logado
            auth()->user()->log("login");
        }
    }
 
    /**
     * Handle user logout events.
     */
    public function onUserLogout($event)
    {
        //dd($event);
        auth()->user()->log("logout");
    }
    
    
    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );
  
        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@onUserLogout'
        );
    }
}
