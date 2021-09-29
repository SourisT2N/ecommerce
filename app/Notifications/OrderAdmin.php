<?php

namespace App\Notifications;

use App\Models\Billing;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $user;
    private $order;

    public function __construct(User $user,Billing $order)
    {
        //
        $this->user  = $user;
        $this->order  = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
            'message' => $this->user->name . ((int) $this->order->id_status === 9? ' Đã Huỷ 1 Đơn Hàng' : ' Vừa Đặt 1 Đơn Hàng.'),
            'url' => route('admin.orders.edit',['order' => $this->order->id])
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->user->name . ((int) $this->order->id_status === 9? ' Đã Huỷ 1 Đơn Hàng' : ' Vừa Đặt 1 Đơn Hàng.'),
            'url' => route('admin.orders.edit',['order' => $this->order->id]),
            'created_at' => ((int) $this->order->id_status === 9? $this->order->created_at : $this->order->updated_at),
        ]);
    }
}
