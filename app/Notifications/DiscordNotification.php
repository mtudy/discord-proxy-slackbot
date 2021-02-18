<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class DiscordNotification extends Notification
{
    /**
     * App link, Discord Not Supported...
     */
    const APP_LINK_REGEX_PATTERN = '/<zpl:\/\/(.*) •/';

    private Collection $message;

    public function __construct(Collection $message)
    {
        $this->message = $message;
    }

    public function via(): array
    {
        return ['slack'];
    }

    public function toSlack(): SlackMessage
    {
        $slackMessage = new SlackMessage;

        $slackMessage->content($this->message->get('text', ''));

        array_map(
            fn(array $attachment): SlackMessage => $slackMessage->attachment(
                fn(SlackAttachment $slackAttachment) => $this->attachment(
                    $slackMessage,
                    $slackAttachment,
                    $attachment
                )
            ),
            $this->message->get('attachments', [])
        );

        return $slackMessage;
    }

    private function attachment(SlackMessage $message, SlackAttachment $slackAttachment, array $attachment): void
    {
        $message->content(
            preg_replace(
                self::APP_LINK_REGEX_PATTERN,
                '',
                $attachment['pretext'] ?? ''
            )
        );

        $slackAttachment->image($attachment['image_url'] ?? null);
        $slackAttachment->pretext($attachment['text'] ?? '');
        $slackAttachment->color(isset($attachment['color']) ? "#{$attachment['color']}" : null);
        $slackAttachment->fallback($attachment['fallback'] ?? '');
    }
}
