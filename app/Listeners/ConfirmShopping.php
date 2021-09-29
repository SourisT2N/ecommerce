<?php

namespace App\Listeners;

use App\Events\OrderShopping;
use App\Mail\CancelOrder;
use App\Mail\ConfirmOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ConfirmShopping implements ShouldQueue
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
        //
        if((int)$event->order->id_status !== 9)
        {
            $order = $event->order->load(['details','orderStatus','payments']);
            Mail::to($event->user->email)->send(new ConfirmOrder($order, $event->user));
        }
        else
            Mail::to($event->user->email)->send(new CancelOrder($event->order,$event->user));
    }
}
