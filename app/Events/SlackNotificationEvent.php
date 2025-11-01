<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SlackNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The channel to send the notification to
     */
    public string $channel;

    /**
     * The message to send
     */
    public string $message;

    /**
     * Optional attachments
     */
    public array $attachments;

    /**
     * Optional blocks for rich formatting
     */
    public array $blocks;

    /**
     * Create a new event instance.
     *
     * @param string $channel The Slack channel
     * @param string $message The message text
     * @param array $attachments Optional attachments
     * @param array $blocks Optional Block Kit blocks
     */
    public function __construct(
        string $channel,
        string $message,
        array $attachments = [],
        array $blocks = []
    ) {
        $this->channel = $channel;
        $this->message = $message;
        $this->attachments = $attachments;
        $this->blocks = $blocks;
    }
}

