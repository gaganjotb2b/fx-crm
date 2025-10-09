<?php

namespace App\Listeners;

use App\Events\Logout;
use Illuminate\Auth\Events\Logout as EventsLogout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Activitylog\Models\Activity;

class LogSuccessfulLogout
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
     * @param  \App\Events\Logout  $event
     * @return void
     */
    public function handle(EventsLogout $event)
    {
        $event->subject = 'logout';
        $ip_address = request()->ip();
        $event->description = "The IP address $ip_address has been logout";
        
        $activity_id = activity($event->subject)
            ->causedBy($event->user)
            ->withProperties($_SERVER['HTTP_USER_AGENT'])
            ->event('logout')
            // ->performedOn(auth()->user())
            ->log($event->description)->id;
        $activity = Activity::find($activity_id);
        $activity->batch_uuid = session('log_id');
        $activity->save();
    }
}
