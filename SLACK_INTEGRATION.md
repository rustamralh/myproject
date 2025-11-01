# Slack Integration Implementation Guide for Laravel

This is a complete step-by-step guide to implement bidirectional Slack integration in any Laravel application. It provides both outgoing notifications and incoming webhook handling capabilities.

## Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Slack App Setup](#slack-app-setup)
4. [Laravel Implementation](#laravel-implementation)
5. [Configuration](#configuration)
6. [Usage Examples](#usage-examples)
7. [Testing](#testing)
8. [Troubleshooting](#troubleshooting)

## Overview

The Slack integration provides:

- **Outgoing Notifications**: Send automated notifications to Slack channels for custom business events
- **Incoming Webhooks**: Handle Slack slash commands, interactive components, and events
- **Event-Driven Architecture**: Queued notifications via Laravel events
- **Security**: Request signature verification for incoming webhooks
- **Flexibility**: Support for both webhook URLs and Bot Token API

## Prerequisites

- Laravel 10.x or higher
- PHP 8.1 or higher
- Guzzle HTTP client (included in Laravel by default)
- Composer

## Slack App Setup

### Step 1: Create a Slack App

1. Go to https://api.slack.com/apps and click "Create New App"
2. Choose "From scratch"
3. Give your app a name (e.g., "MyApp Notifications") and select your workspace
4. Click "Create App"

### Step 2: Configure Incoming Webhooks

1. In your Slack App settings, go to **Incoming Webhooks**
2. Activate Incoming Webhooks
3. Click "Add New Webhook to Workspace"
4. Select the channel where you want to receive notifications (e.g., #general)
5. Copy the **Webhook URL** (you'll need this for configuration)

### Step 3: Configure Slash Commands (Optional)

To enable slash commands like `/app-stats`:

1. Go to **Slash Commands** in your app settings
2. Click "Create New Command"
3. Configure the command:
   - **Command**: `/app-stats` (replace `app` with your app name)
   - **Request URL**: `https://yourdomain.com/api/slack/webhook`
   - **Short Description**: "Display application statistics"
   - **Usage Hint**: (leave empty)
4. Click "Save"

### Step 4: Enable Interactive Components (Optional)

1. Go to **Interactive Components** in your app settings
2. Turn on the toggle
3. Enter the **Request URL**: `https://yourdomain.com/api/slack/webhook`
4. Click "Save Changes"

### Step 5: Generate Signing Secret

1. Go to **Basic Information** in your app settings
2. Under **App Credentials**, find **Signing Secret**
3. Click "Show" and copy the signing secret (you'll need this for security)

### Step 6: Configure Bot Token (Optional, for advanced features)

1. Go to **OAuth & Permissions**
2. Under **Scopes**, add the following Bot Token Scopes:
   - `chat:write` - Send messages
   - `commands` - Slash commands
   - `users:read` - Read user information
3. Click "Install to Workspace"
4. Copy the **Bot User OAuth Token** (starts with `xoxb-`)

## Laravel Implementation

### Step 1: Add Configuration

**File: `config/services.php`**

Add the Slack configuration block to the existing `services.php` file:

```php
<?php

return [
    // ... existing configurations ...

    'slack' => [
        'webhook_url' => env('SLACK_WEBHOOK_URL'),
        'bot_token' => env('SLACK_BOT_TOKEN'),
        'signing_secret' => env('SLACK_SIGNING_SECRET'),
        'default_channel' => env('SLACK_DEFAULT_CHANNEL', '#general'),
    ],
];
```

### Step 2: Add Environment Variables

**File: `.env`**

Add the following variables to your `.env` file:

```env
# Slack Integration
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
SLACK_BOT_TOKEN=xoxb-your-bot-token
SLACK_SIGNING_SECRET=your-signing-secret
SLACK_DEFAULT_CHANNEL=#general
```

**Note**: At minimum, you need either `SLACK_WEBHOOK_URL` or `SLACK_BOT_TOKEN`. The webhook URL is simpler to set up and recommended for basic notifications.

### Step 3: Create SlackService

**File: `app/Services/SlackService.php`**

Create the service class that handles all Slack API interactions:

```php
<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SlackService
{
    private Client $client;
    private ?string $webhookUrl;
    private ?string $botToken;
    private ?string $signingSecret;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->webhookUrl = config('services.slack.webhook_url');
        $this->botToken = config('services.slack.bot_token');
        $this->signingSecret = config('services.slack.signing_secret');
    }

    /**
     * Send a simple notification to a Slack channel
     *
     * @param string $channel Channel name (e.g., '#general') or ID
     * @param string $message Message text
     * @param array $attachments Optional attachments array
     * @return bool
     */
    public function sendNotification(string $channel, string $message, array $attachments = []): bool
    {
        if (empty($this->webhookUrl) && empty($this->botToken)) {
            Log::warning('Slack integration not configured. Webhook URL and Bot Token are missing.');
            return false;
        }

        $payload = [
            'channel' => $channel,
            'text' => $message,
        ];

        if (!empty($attachments)) {
            $payload['attachments'] = $attachments;
        }

        return $this->sendRequest($payload);
    }

    /**
     * Send a rich block-based message to Slack
     *
     * @param string $channel Channel name (e.g., '#general') or ID
     * @param array $blocks Slack Block Kit blocks
     * @return bool
     */
    public function sendBlockMessage(string $channel, array $blocks): bool
    {
        if (empty($this->webhookUrl) && empty($this->botToken)) {
            Log::warning('Slack integration not configured. Webhook URL and Bot Token are missing.');
            return false;
        }

        $payload = [
            'channel' => $channel,
            'blocks' => $blocks,
        ];

        return $this->sendRequest($payload);
    }

    /**
     * Verify Slack request signature for incoming webhooks
     *
     * @param Request $request
     * @return bool
     */
    public function verifySlackRequest(Request $request): bool
    {
        if (empty($this->signingSecret)) {
            Log::warning('Slack signing secret not configured. Request verification skipped.');
            return false;
        }

        $signature = $request->header('X-Slack-Signature');
        $timestamp = $request->header('X-Slack-Request-Timestamp');
        $body = $request->getContent();

        // Prevent replay attacks
        $currentTime = time();
        if (abs($currentTime - $timestamp) > 300) {
            return false;
        }

        // Create the signature base string
        $sigBaseString = "v0:{$timestamp}:{$body}";

        // Create the signature
        $computedSignature = 'v0=' . hash_hmac('sha256', $sigBaseString, $this->signingSecret);

        // Compare signatures
        return hash_equals($computedSignature, $signature);
    }

    /**
     * Send HTTP request to Slack
     *
     * @param array $payload
     * @return bool
     */
    private function sendRequest(array $payload): bool
    {
        try {
            $url = $this->webhookUrl;
            $headers = [];

            // Use Bot API if webhook URL is not available
            if (empty($url) && !empty($this->botToken)) {
                $url = 'https://slack.com/api/chat.postMessage';
                $headers['Authorization'] = 'Bearer ' . $this->botToken;
            }

            if (empty($url)) {
                Log::error('Cannot send Slack message: no webhook URL or bot token configured.');
                return false;
            }

            $response = $this->client->post($url, [
                'json' => $payload,
                'headers' => $headers,
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();

            if ($statusCode === 200) {
                // Check if using Bot API (used URL instead of webhook)
                if ($url === 'https://slack.com/api/chat.postMessage') {
                    $data = json_decode($responseBody, true);
                    if (isset($data['ok']) && $data['ok'] === true) {
                        return true;
                    }
                    Log::error('Slack Bot API error: ' . json_encode($data));
                    return false;
                }
                // Webhook URL returns "ok" as plain text
                return true;
            }

            Log::error("Slack request failed with status code: {$statusCode}");
            return false;

        } catch (GuzzleException $e) {
            Log::error('Slack request exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a simple text message (convenience method)
     *
     * @param string $message
     * @param string|null $channel
     * @return bool
     */
    public function sendMessage(string $message, ?string $channel = null): bool
    {
        $channel = $channel ?? config('services.slack.default_channel', '#general');
        return $this->sendNotification($channel, $message);
    }
}
```

### Step 4: Create SlackNotificationEvent

**File: `app/Events/SlackNotificationEvent.php`**

Create the event class for queued notifications:

```php
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
```

### Step 5: Create SendSlackNotification Listener

**File: `app/Listeners/SendSlackNotification.php`**

Create the listener that processes the event:

```php
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
```

### Step 6: Register Event and Listener

**File: `app/Providers/EventServiceProvider.php`**

Register the event-listener mapping:

```php
<?php

namespace App\Providers;

use App\Events\SlackNotificationEvent;
use App\Listeners\SendSlackNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // ... existing listeners ...

        SlackNotificationEvent::class => [
            SendSlackNotification::class,
        ],
    ];

    // ... rest of the file ...
}
```

### Step 7: Create SlackWebhookController

**File: `app/Http/Controllers/SlackWebhookController.php`**

Create the controller to handle incoming Slack webhooks:

```php
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
                $this->handleMessageEvent($event);
                break;

            case 'app_mention':
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

        Log::info('Slack slash command received', [
            'command' => $command,
            'text' => $text,
            'user' => $userId,
        ]);

        // Route to specific command handler
        switch ($command) {
            case '/app-stats':  // Replace 'app' with your app name
                return $this->handleStatsCommand($request);

            case '/app-users':
                return $this->handleUsersCommand($request);

            case '/app-help':
                return $this->handleHelpCommand();

            default:
                return response()->json([
                    'response_type' => 'ephemeral',
                    'text' => "Unknown command: {$command}. Use /app-help to see available commands.",
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
     * Handle /app-stats command
     * Customize this based on your application's statistics
     */
    private function handleStatsCommand(Request $request)
    {
        // Get application statistics
        // Example: Replace with your actual models
        $userCount = \App\Models\User::count();
        // $orderCount = \App\Models\Order::count();

        return response()->json([
            'response_type' => 'in_channel',
            'text' => 'Application Statistics',
            'blocks' => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => "*Application Statistics*\n\n*Total Users:* {$userCount}",
                    ],
                ],
            ],
        ]);
    }

    /**
     * Handle /app-users command
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
     * Handle /app-help command
     */
    private function handleHelpCommand()
    {
        $commands = [
            '`/app-help` - Show this help message',
            '`/app-stats` - Display application statistics',
            '`/app-users` - Show latest users',
        ];

        return response()->json([
            'response_type' => 'ephemeral',
            'text' => "*Available Commands:*\n\n" . implode("\n", $commands),
        ]);
    }
}
```

### Step 8: Add Routes

**File: `routes/api.php`**

Add the Slack webhook route:

```php
<?php

use App\Http\Controllers\SlackWebhookController;
use Illuminate\Support\Facades\Route;

// ... existing routes ...

// Slack webhook endpoint
Route::post('/slack/webhook', [SlackWebhookController::class, 'handle'])->name('slack.webhook');
```

### Step 9: Exclude Webhook from CSRF Protection

**File: `app/Http/Middleware/VerifyCsrfToken.php`**

Add the webhook route to the CSRF exception list:

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'slack/webhook',
    ];
}
```

### Step 10: Create Test Command (Optional but Recommended)

**File: `app/Console/Commands/TestSlackIntegration.php`**

Create a command to test the integration:

```php
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
```

### Step 11: Create Jobs Table (If Using Database Queues)

If you're using the `database` queue driver for queued notifications, create the jobs table:

```bash
php artisan queue:table
php artisan migrate
```

### Step 12: Clear Configuration Cache

After setup, clear the configuration cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# Slack Integration
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
SLACK_BOT_TOKEN=xoxb-your-bot-token
SLACK_SIGNING_SECRET=your-signing-secret
SLACK_DEFAULT_CHANNEL=#general
```

**Important Notes:**
- At minimum, you need either `SLACK_WEBHOOK_URL` or `SLACK_BOT_TOKEN`
- The webhook URL is simpler and recommended for basic notifications
- The bot token is required for advanced features like posting to any channel
- The signing secret is required for incoming webhook security

## Usage Examples

### Sending Notifications

#### Method 1: Using the Event System (Recommended for Production)

Fire a `SlackNotificationEvent` to send notifications asynchronously via queue:

```php
use App\Events\SlackNotificationEvent;

// Simple text message
event(new SlackNotificationEvent(
    '#general',
    'User registered successfully'
));

// Rich message with Block Kit
event(new SlackNotificationEvent(
    '#alerts',
    'Important update',
    [],
    [
        [
            'type' => 'section',
            'text' => [
                'type' => 'mrkdwn',
                'text' => '*Alert*\nSomething important happened!'
            ]
        ]
    ]
));
```

**Note**: The listener is queued, so if you're using the `database` queue driver, you need to run the queue worker:
```bash
php artisan queue:work
```

With `sync` queue driver (default), notifications are sent immediately without a queue worker.

#### Method 2: Direct Service Call (Recommended for Testing or Immediate Notifications)

Use the `SlackService` directly for synchronous sending (useful when queues might be paused):

```php
use App\Services\SlackService;

$slackService = app(SlackService::class);

// Send simple message
$slackService->sendMessage('Hello from Laravel!');

// Send to specific channel
$slackService->sendNotification('#alerts', 'Alert message');

// Send block message
$slackService->sendBlockMessage('#general', [
    [
        'type' => 'section',
        'text' => [
            'type' => 'mrkdwn',
            'text' => '*Rich Message*\nWith formatting!'
        ]
    ]
]);
```

### Handling Incoming Slack Requests

The application handles three types of incoming Slack requests:

#### 1. Slash Commands

Users can use slash commands in Slack. Customize commands in `SlackWebhookController::handleSlashCommand()`.

Example commands:
- `/app-help` - Show available commands
- `/app-stats` - Display application statistics
- `/app-users` - Show latest users

#### 2. Interactive Components

Buttons, select menus, and other interactive components trigger `block_actions` events handled in `SlackWebhookController::handleInteractiveAction()`.

#### 3. Events

The app can listen to Slack events such as:
- `message` - Messages in channels the bot is in
- `app_mention` - When the bot is mentioned

### Adding Custom Business Events

To add Slack notifications for your custom events:

1. Create or find the appropriate event in your application
2. Add the listener mapping in `app/Providers/EventServiceProvider.php`:

```php
protected $listen = [
    UserRegistered::class => [
        SendSlackNotification::class,
    ],
    // Add more events as needed
];
```

3. Fire the event when the action occurs:

```php
event(new UserRegistered($user));
```

4. In your controller or wherever you trigger the event, you can optionally fire a Slack-specific notification:

```php
event(new SlackNotificationEvent(
    '#user-activity',
    "New user registered: {$user->email}"
));
```

## Testing

### Test Command

Run the test command to verify your configuration:

```bash
php artisan slack:test
```

You can also test with a specific channel:

```bash
php artisan slack:test --channel=#alerts
```

Check your Slack workspace to confirm the test message was received.

### Test Incoming Webhooks

1. **Test URL Verification**: Slack will send a challenge during initial setup
2. **Test Slash Commands**: Use your configured commands in Slack
3. **Check Logs**: Monitor `storage/logs/laravel.log` for incoming requests

## Troubleshooting

### Notifications Not Being Sent

1. **Check configuration**: Verify environment variables are set correctly:
   ```bash
   php artisan tinker
   >>> config('services.slack')
   ```

2. **Check logs**: Look for Slack-related errors in `storage/logs/laravel.log`

3. **Test connection**: Run `php artisan slack:test`

4. **Verify queue**: Ensure queue worker is running if using event-based notifications:
   ```bash
   php artisan queue:work
   ```

### Webhook Not Responding

1. **Verify URL**: Ensure your webhook URL is publicly accessible and returns 200 OK
2. **Check signature verification**: Verify your `SLACK_SIGNING_SECRET` is correct
3. **Check logs**: Look for errors in `storage/logs/laravel.log`
4. **Test manually**: Use a tool like Postman to send a test request
5. **Verify route**: Run `php artisan route:list | grep slack` to confirm the route exists

### Slash Commands Not Working

1. **Verify command configuration**: Check slash commands are properly configured in Slack App settings
2. **Check Request URL**: Ensure it points to `https://yourdomain.com/api/slack/webhook`
3. **Verify webhook endpoint**: Run `php artisan route:list` to confirm the route exists
4. **Check signature**: Verify `SLACK_SIGNING_SECRET` is set correctly
5. **Check logs**: Look for errors when the command is triggered

### Error: "Slack integration not configured"

This warning appears when neither `SLACK_WEBHOOK_URL` nor `SLACK_BOT_TOKEN` is configured. Add at least one of these to your `.env` file.

### Error: "Invalid signature"

This means the Slack signing secret doesn't match. Verify:
1. `SLACK_SIGNING_SECRET` in `.env` matches the value in Slack App settings
2. Clear config cache: `php artisan config:clear`
3. Ensure the secret hasn't been regenerated in Slack

## Advanced Configuration

### Customizing Notifications

You can customize notification behavior by modifying the `SlackService` class. For example, add methods for specific notification types:

```php
public function sendAlert(string $message, string $severity = 'info'): bool
{
    $blocks = [
        [
            'type' => 'section',
            'text' => [
                'type' => 'mrkdwn',
                'text' => "*Alert [{$severity}]*\n{$message}"
            ]
        ]
    ];
    
    return $this->sendBlockMessage('#alerts', $blocks);
}
```

### Adding New Slash Commands

1. Add command configuration in your Slack App settings
2. Add a case in `SlackWebhookController::handleSlashCommand()`:

```php
case '/app-new-command':
    return $this->handleNewCommand($request);
```

3. Implement the handler method:

```php
private function handleNewCommand(Request $request)
{
    // Your logic here
    return response()->json([
        'response_type' => 'ephemeral',
        'text' => 'Response message'
    ]);
}
```

### Using Immediate Notifications (Not Queued)

For scenarios where you need immediate notifications (e.g., during maintenance when queues are paused), use `SlackService` directly instead of events:

```php
use App\Services\SlackService;

$slackService = app(SlackService::class);
$slackService->sendMessage('Immediate notification', '#alerts');
```

This bypasses the queue system and sends synchronously.

## Architecture

### Components

- **SlackService** (`app/Services/SlackService.php`) - Core service for Slack API interactions
- **SlackNotificationEvent** (`app/Events/SlackNotificationEvent.php`) - Event for queued notifications
- **SendSlackNotification** (`app/Listeners/SendSlackNotification.php`) - Listener that sends notifications
- **SlackWebhookController** (`app/Http/Controllers/SlackWebhookController.php`) - Handles incoming webhooks
- **TestSlackIntegration** (`app/Console/Commands/TestSlackIntegration.php`) - Test command

### Security

- **Request Verification**: All incoming Slack webhooks are verified using the signing secret to prevent unauthorized requests
- **Replay Attack Prevention**: Timestamp validation ensures requests are not replay attacks
- **CSRF Protection**: The webhook endpoint is excluded from CSRF protection

### Queue Support

Notifications are sent asynchronously via Laravel queues, ensuring they don't slow down your application responses. Make sure your queue worker is running:

```bash
php artisan queue:work
```

Or use Laravel Horizon (if installed):
```bash
php artisan horizon
```

## Files Created/Modified

### New Files
- `app/Services/SlackService.php`
- `app/Events/SlackNotificationEvent.php`
- `app/Listeners/SendSlackNotification.php`
- `app/Http/Controllers/SlackWebhookController.php`
- `app/Console/Commands/TestSlackIntegration.php` (optional)

### Modified Files
- `config/services.php` - Add Slack configuration
- `app/Providers/EventServiceProvider.php` - Register event-listener mapping
- `routes/api.php` - Add webhook route
- `app/Http/Middleware/VerifyCsrfToken.php` - Exclude webhook from CSRF
- `.env` - Add Slack environment variables

## Reference

- [Slack API Documentation](https://api.slack.com/)
- [Slack Block Kit](https://api.slack.com/block-kit)
- [Slack Events API](https://api.slack.com/events-api)
- [Slack Slash Commands](https://api.slack.com/interactivity/slash-commands)
- [Laravel Events](https://laravel.com/docs/events)
- [Laravel Queues](https://laravel.com/docs/queues)

## Quick Start Checklist

- [ ] Create Slack App and get webhook URL
- [ ] Add Slack configuration to `config/services.php`
- [ ] Add environment variables to `.env`
- [ ] Create `SlackService` class
- [ ] Create `SlackNotificationEvent` class
- [ ] Create `SendSlackNotification` listener
- [ ] Register event in `EventServiceProvider`
- [ ] Create `SlackWebhookController`
- [ ] Add webhook route to `routes/api.php`
- [ ] Exclude webhook from CSRF in `VerifyCsrfToken`
- [ ] Create test command (optional)
- [ ] Run `php artisan config:clear`
- [ ] Test with `php artisan slack:test`

## Support

For issues or questions:
1. Check the application logs in `storage/logs/laravel.log`
2. Verify configuration with `php artisan tinker` and `config('services.slack')`
3. Test connection with `php artisan slack:test`
4. Consult the Laravel and Slack documentation
