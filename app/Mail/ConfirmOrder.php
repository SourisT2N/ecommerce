<?php

namespace App\Mail;

use App\Models\Billing;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmOrder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $order;
    private $user;

    public function __construct(Billing $order,User $user)
    {
        //
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Xác Nhận Đơn Hàng')
        ->markdown('mail.order',[
            'user' => $this->user,
            'order' => $this->order
        ]);
    }
}
