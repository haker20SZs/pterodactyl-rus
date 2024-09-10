<?php

namespace Pterodactyl\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendPasswordReset extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $token)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Сброс пароля')
            ->line('Вы получаете это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.')
            ->action('Сброс пароля', url('/auth/password/reset/' . $this->token . '?email=' . urlencode($notifiable->email)))
            ->line('Если вы не запрашивали сброс пароля, дальнейшие действия не требуются.');
    }
}
