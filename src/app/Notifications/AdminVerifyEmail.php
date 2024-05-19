<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminVerifyEmail extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('メールアドレスを確認してください。')
                    ->action('確認', url('/admin'))
                    ->line('ご利用いただきありがとうございます！');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}