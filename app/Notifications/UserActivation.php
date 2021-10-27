<?php

namespace App\Notifications;

use App\Models\UserActivation as ModelsUserActivation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserActivation extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $user;
    private $type;
    
    public function __construct($user,$type)
    {
        //
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $token = (new ModelsUserActivation())->createActivation($this->user,$this->type);
        $user = $this->user;
        return (new MailMessage)
        ->subject($this->type == 'email'?'Xác Thực Đăng Ký':'Lấy Lại Mật Khẩu')
        ->markdown($this->type == 'email'?'mail.register':'mail.forgot',compact('token','user'));
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
        ];
    }
}
