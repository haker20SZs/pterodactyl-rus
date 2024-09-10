<?php

namespace Pterodactyl\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AddedToServer extends Notification implements ShouldQueue
{
    use Queueable;

    public object $server;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $server)
    {
        $this->server = (object) $server;
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
        return (new MailMessage())
            ->greeting('Здравствуйте ' . $this->server->user . '!')
            ->line('Вы были добавлены в качестве подпользователя для следующего сервера, что позволяет вам получить определенный контроль над сервером.')
            ->line('Имя сервера: ' . $this->server->name)
            ->action('Посетите сервер', url('/server/' . $this->server->uuidShort));
    }
}
