<?php

namespace App\Console\Commands;

use App\Services\SlackService;
use Illuminate\Console\Command;

class TestSlackIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slack:test {--channel= : Specific Slack channel to send test message to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Slack integration by sending a test notification';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Sending test notification to Slack...');

        $channel = $this->option('channel') ?? '#general';

        // Send test notification directly via SlackService (synchronous)
        $slackService = app(SlackService::class);

        $blocks = [
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => '*Slack Integration Test*\n\nIf you see this message, your Slack integration is working correctly! ✅',
                ],
            ],
            [
                'type' => 'section',
                'fields' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Test Time:*\n{$this->formatTimestamp()}",
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Environment:*\n".config('app.env'),
                    ],
                ],
            ],
        ];

        $success = $slackService->sendBlockMessage($channel, $blocks);

        if ($success) {
            $this->info("✅ Test notification sent successfully to channel: {$channel}");
            $this->info('Check your Slack workspace to confirm the message was delivered.');
        } else {
            $this->error('❌ Failed to send test notification. Check your Slack configuration in .env');
            $this->warn('Make sure SLACK_WEBHOOK_URL or SLACK_BOT_TOKEN is configured.');
        }

        return $success ? Command::SUCCESS : Command::FAILURE;
    }

    /**
     * Format timestamp for Slack message
     */
    private function formatTimestamp(): string
    {
        return now()->format('Y-m-d H:i:s T');
    }
}
