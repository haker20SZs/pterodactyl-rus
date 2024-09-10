<?php

namespace Pterodactyl\Notifications;

use Pterodactyl\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MailTested extends Notification
{
    public function __construct(private User $user)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject('Сообщение о тестировании WestalHost')
            ->greeting('Здравствуйте ' . $this->user->name . '!')
            ->line('Это тест почтовой системы WestalHost. Вы готовы!');
    }
}
