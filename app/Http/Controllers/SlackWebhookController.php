<?php

namespace App\Http\Controllers;

use App\Services\SlackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SlackWebhookController extends Controller
{
    /**
     * The SlackService instance
     */
    private SlackService $slackService;

    /**
     * Create a new controller instance.
     */
    public function __construct(SlackService $slackService)
    {
        $this->slackService = $slackService;
    }

    /**
     * Handle incoming Slack webhook requests
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        // Verify Slack signature
        if (!$this->slackService->verifySlackRequest($request)) {
            Log::warning('Invalid Slack request signature');
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Handle URL verification challenge (Slack requires this)
        if ($request->has('challenge')) {
            return response($request->input('challenge'), 200)
                ->header('Content-Type', 'text/plain');
        }

        $type = $request->input('type');

        // Handle different request types
        switch ($type) {
            case 'url_verification':
                return $this->handleUrlVerification($request);

            case 'event_callback':
                return $this->handleEvent($request);

            default:
                // If no type specified, check if it's a slash command or interactive component
                if ($request->has('command')) {
                    return $this->handleSlashCommand($request);
                }

                if ($request->has('payload')) {
                    return $this->handleInteractiveAction($request);
                }

                Log::warning('Unknown Slack webhook type', ['type' => $type, 'request' => $request->all()]);
                return response()->json(['error' => 'Unknown webhook type'], 400);
        }
    }

    /**
     * Handle URL verification challenge
     */
    private function handleUrlVerification(Request $request)
    {
        $challenge = $request->input('challenge');
        return response($challenge, 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Handle Slack event
     */
    private function handleEvent(Request $request)
    {
        $event = $request->input('event');
        $eventType = $event['type'] ?? null;

        Log::info('Slack event received', ['event_type' => $eventType]);

        switch ($eventType) {
            case 'message':
                // Handle message events
                $this->handleMessageEvent($event);
                break;

            case 'app_mention':
                // Handle app mentions
                $this->handleAppMention($event);
                break;

            default:
                Log::info('Unhandled Slack event type', ['type' => $eventType]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Handle slash command
     */
    private function handleSlashCommand(Request $request)
    {
        $command = $request->input('command');
        $text = $request->input('text');
        $userId = $request->input('user_id');
        $channelId = $request->input('channel_id');
        $responseUrl = $request->input('response_url');

        Log::info('Slack slash command received', [
            'command' => $command,
            'text' => $text,
            'user' => $userId,
        ]);

        // Route to specific command handler
        switch ($command) {
            case '/myproject-stats':
                return $this->handleStatsCommand($request);

            case '/myproject-users':
                return $this->handleUsersCommand($request);

            case '/myproject-help':
                return $this->handleHelpCommand();

            default:
                return response()->json([
                    'response_type' => 'ephemeral',
                    'text' => "Unknown command: {$command}. Use /myproject-help to see available commands.",
                ]);
        }
    }

    /**
     * Handle interactive actions (button clicks, etc.)
     */
    private function handleInteractiveAction(Request $request)
    {
        $payload = json_decode($request->input('payload'), true);
        $actionType = $payload['type'] ?? null;

        Log::info('Slack interactive action received', ['type' => $actionType]);

        // Handle button clicks, selects, etc.
        if ($actionType === 'block_actions') {
            $this->handleBlockActions($payload);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Handle message events
     */
    private function handleMessageEvent(array $event)
    {
        // Handle direct messages to the bot
        Log::info('Message event handled', ['text' => $event['text'] ?? '']);
    }

    /**
     * Handle app mentions
     */
    private function handleAppMention(array $event)
    {
        // Handle when bot is mentioned in a channel
        Log::info('App mention handled', ['text' => $event['text'] ?? '']);
    }

    /**
     * Handle block actions (button clicks, etc.)
     */
    private function handleBlockActions(array $payload)
    {
        $actions = $payload['actions'] ?? [];
        foreach ($actions as $action) {
            Log::info('Block action triggered', ['action_id' => $action['action_id'] ?? '']);
        }
    }

    /**
     * Handle /myproject-stats command
     */
    private function handleStatsCommand(Request $request)
    {
        // Get application statistics
        $userCount = \App\Models\User::count();
        $postCount = \App\Models\Post::count();

        return response()->json([
            'response_type' => 'in_channel',
            'text' => 'Application Statistics',
            'blocks' => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => "*Application Statistics*\n\n*Total Users:* {$userCount}\n*Total Posts:* {$postCount}",
                    ],
                ],
            ],
        ]);
    }

    /**
     * Handle /myproject-users command
     */
    private function handleUsersCommand(Request $request)
    {
        $limit = 5;
        $users = \App\Models\User::latest()->take($limit)->get();

        $text = "*Latest {$limit} Users:*\n\n";
        foreach ($users as $user) {
            $text .= "• {$user->name} ({$user->email})\n";
        }

        return response()->json([
            'response_type' => 'ephemeral',
            'text' => $text,
        ]);
    }

    /**
     * Handle /myproject-help command
     */
    private function handleHelpCommand()
    {
        $commands = [
            '`/myproject-help` - Show this help message',
            '`/myproject-stats` - Display application statistics',
            '`/myproject-users` - Show latest users',
        ];

        return response()->json([
            'response_type' => 'ephemeral',
            'text' => "*Available Commands:*\n\n" . implode("\n", $commands),
        ]);
    }
}

