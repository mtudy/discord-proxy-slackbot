<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class DiscordNotification extends Notification
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function via(): array
    {
        return ['slack'];
    }

    public function toSlack(): SlackMessage
    {
        return (new SlackMessage)
            ->content($this->message);
    }
}
