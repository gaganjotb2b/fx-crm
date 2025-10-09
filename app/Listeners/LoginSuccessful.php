<?php

namespace App\Listeners;
use App\Events\Login;
use App\Models\User;
use Illuminate\Auth\Events\Login as EventsLogin;
use IlluminateAuthEventsLogin;
// use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Models\Activity;

class LoginSuccessful
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(EventsLogin $event)
    {
        $event->subject = 'login';
        $ip_address = request()->ip();
        $event->description = "The IP address $ip_address has been login";
        Session::flash('login-success', 'Hello' . $event->user->name . ', welcome back!');
        $log_id = activity($event->subject)
            ->causedBy($event->user)
            ->withProperties($_SERVER['HTTP_USER_AGENT'])
            ->event('login')
            ->performedOn($event->user)
            ->log($event->description)->id;
        // end activity log-----------------
        
        session(['log_id' => $log_id]);
    }
}
