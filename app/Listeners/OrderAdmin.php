<?php

namespace App\Listeners;

use App\Events\OrderShopping;
use App\Models\User;
use App\Notifications\OrderAdmin as NotificationsOrderAdmin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class OrderAdmin
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
     * @param  object  $event
     * @return void
     */
    public function handle(OrderShopping $event)
    {
        $users = User::whereHas('roles',function($q){ $q->whereIn('name',['super-admin','order']);})->get();
        Notification::send($users,new NotificationsOrderAdmin($event->user,$event->order));
    }
}
