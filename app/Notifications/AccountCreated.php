<?php

namespace Pterodactyl\Notifications;

use Pterodactyl\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public ?string $token = null)
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
    public function toMail(): MailMessage
    {
        $message = (new MailMessage())
            ->greeting('Здравствуйте ' . $this->user->name . '!')
            ->line('Вы получаете это письмо, потому что для вас была создана учетная запись на сайте ' . config('app.name') . '.')
            ->line('Имя пользователя: ' . $this->user->username)
            ->line('Электронная почта: ' . $this->user->email);

        if (!is_null($this->token)) {
            return $message->action('Настройка учетной записи', url('/auth/password/reset/' . $this->token . '?email=' . urlencode($this->user->email)));
        }

        return $message;
    }
}
