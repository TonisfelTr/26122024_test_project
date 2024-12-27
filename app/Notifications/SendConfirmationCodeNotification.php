<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SmsMessage;

class SendConfirmationCodeNotification extends Notification
{
    private $confirmationCode;

    public function __construct($confirmationCode)
    {
        $this->confirmationCode = $confirmationCode;
    }

    public function via($notifiable)
    {
        return [$notifiable->preferred_notification_method]; // Пример, можете уточнить логику.
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Код подтверждения')
            ->line("Ваш код подтверждения: {$this->confirmationCode}");
    }

    public function toSms($notifiable)
    {
        return "Ваш код подтверждения: {$this->confirmationCode}";
    }

    public function toTelegram($notifiable)
    {
        return "Ваш код подтверждения: {$this->confirmationCode}";
    }
}
