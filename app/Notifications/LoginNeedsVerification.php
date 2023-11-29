<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class LoginNeedsVerification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['vonage'];
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param object $notifiable
     * @return VonageMessage
     */
    public function toVonage(object $notifiable): VonageMessage
    {
        $loginCode = rand(111111, 999999);

        $notifiable->update([
            'login_code' => $loginCode,
        ]);

        return (new VonageMessage)
            ->content("Your login code is {$loginCode}. Please do not share this code with anyone.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
