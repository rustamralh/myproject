<?php

namespace App\Listeners;

use App\Events\SlackNotificationEvent;
use App\Services\SlackService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendSlackNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The SlackService instance
     */
    private SlackService $slackService;

    /**
     * Create the event listener.
     */
    public function __construct(SlackService $slackService)
    {
        $this->slackService = $slackService;
    }

    /**
     * Handle the event.
     *
     * @param SlackNotificationEvent $event
     * @return void
     */
    public function handle(SlackNotificationEvent $event): void
    {
        try {
            // Use blocks if provided, otherwise use message with attachments
            if (!empty($event->blocks)) {
                $success = $this->slackService->sendBlockMessage(
                    $event->channel,
                    $event->blocks
                );
            } else {
                $success = $this->slackService->sendNotification(
                    $event->channel,
                    $event->message,
                    $event->attachments
                );
            }

            if (!$success) {
                Log::warning('Failed to send Slack notification', [
                    'channel' => $event->channel,
                    'message' => $event->message,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception while sending Slack notification: ' . $e->getMessage(), [
                'channel' => $event->channel,
                'message' => $event->message,
                'exception' => $e,
            ]);
        }
    }
}

