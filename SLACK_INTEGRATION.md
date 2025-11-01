# Slack Integration Guide

This document provides instructions for setting up and using the Slack integration in this Laravel application.

## Overview

The Slack integration provides bidirectional communication with Slack:
- **Outgoing Notifications**: Send automated notifications to Slack channels for custom business events
- **Incoming Webhooks**: Handle Slack slash commands, interactive components, and events

## Setup Instructions

### 1. Create a Slack App

1. Go to https://api.slack.com/apps and click "Create New App"
2. Choose "From scratch"
3. Give your app a name (e.g., "MyProject Notifications") and select your workspace
4. Click "Create App"

### 2. Configure Incoming Webhooks

1. In your Slack App settings, go to **Incoming Webhooks**
2. Activate Incoming Webhooks
3. Click "Add New Webhook to Workspace"
4. Select the channel where you want to receive notifications (e.g., #general)
5. Copy the **Webhook URL** (you'll need this for configuration)

### 3. Configure Slash Commands (Optional)

To enable slash commands like `/myproject-stats`:

1. Go to **Slash Commands** in your app settings
2. Click "Create New Command"
3. Configure the command:
   - **Command**: `/myproject-stats`
   - **Request URL**: `https://yourdomain.com/api/slack/webhook`
   - **Short Description**: "Display application statistics"
   - **Usage Hint**: (leave empty)
4. Click "Save"

Repeat for other commands:
- `/myproject-users` - Show latest users
- `/myproject-help` - Show help message

### 4. Enable Interactive Components (Optional)

1. Go to **Interactive Components** in your app settings
2. Turn on the toggle
3. Enter the **Request URL**: `https://yourdomain.com/api/slack/webhook`
4. Click "Save Changes"

### 5. Generate Signing Secret

1. Go to **Basic Information** in your app settings
2. Under **App Credentials**, find **Signing Secret**
3. Click "Show" and copy the signing secret (you'll need this for security)

### 6. Configure Bot Token (Optional, for advanced features)

1. Go to **OAuth & Permissions**
2. Under **Scopes**, add the following Bot Token Scopes:
   - `chat:write` - Send messages
   - `commands` - Slash commands
   - `users:read` - Read user information
3. Click "Install to Workspace"
4. Copy the **Bot User OAuth Token** (starts with `xoxb-`)

### 7. Application Configuration

Add the following environment variables to your `.env` file:

```env
# Slack Integration
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
SLACK_BOT_TOKEN=xoxb-your-bot-token
SLACK_SIGNING_SECRET=your-signing-secret
```

**Note**: At minimum, you need either `SLACK_WEBHOOK_URL` or `SLACK_BOT_TOKEN`. The webhook URL is simpler to set up and recommended for basic notifications.

### 8. Test the Integration

Run the test command to verify your configuration:

```bash
php artisan slack:test
```

You can also test with a specific channel:

```bash
php artisan slack:test --channel=#alerts
```

Check your Slack workspace to confirm the test message was received.

## Usage

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

#### Method 2: Direct Service Call (Recommended for Testing)

Use the `SlackService` directly for synchronous sending:

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

Users can use slash commands in Slack:

- `/myproject-help` - Show available commands
- `/myproject-stats` - Display application statistics
- `/myproject-users` - Show latest users

#### 2. Interactive Components

Buttons, select menus, and other interactive components trigger `block_actions` events that can be handled in the `SlackWebhookController`.

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

## Available Commands

### Artisan Commands

- `php artisan slack:test` - Test the Slack integration
- `php artisan slack:test --channel=#alerts` - Test with a specific channel

### Slash Commands (in Slack)

- `/myproject-help` - Show help for available commands
- `/myproject-stats` - Display application statistics
- `/myproject-users` - Show latest users

## Architecture

### Components

- **SlackService** (`app/Services/SlackService.php`) - Core service for Slack API interactions
- **SlackNotificationEvent** (`app/Events/SlackNotificationEvent.php`) - Event for queued notifications
- **SendSlackNotification** (`app/Listeners/SendSlackNotification.php`) - Listener that sends notifications
- **SlackWebhookController** (`app/Http/Controllers/SlackWebhookController.php`) - Handles incoming webhooks

### Security

- **Request Verification**: All incoming Slack webhooks are verified using the signing secret to prevent unauthorized requests
- **Replay Attack Prevention**: Timestamp validation ensures requests are not replay attacks
- **CSRF Protection**: The webhook endpoint is excluded from CSRF protection

### Queue Support

Notifications are sent asynchronously via Laravel queues, ensuring they don't slow down your application responses. Make sure your queue worker is running:

```bash
php artisan queue:work
```

## Troubleshooting

### Notifications Not Being Sent

1. **Check configuration**: Verify environment variables are set correctly:
   ```bash
   php artisan tinker
   >>> config('services.slack')
   ```

2. **Check logs**: Look for Slack-related errors in `storage/logs/laravel.log`

3. **Test connection**: Run `php artisan slack:test`

4. **Verify queue**: Ensure queue worker is running if using event-based notifications

### Webhook Not Responding

1. **Verify URL**: Ensure your webhook URL is publicly accessible and returns 200 OK
2. **Check signature verification**: Verify your `SLACK_SIGNING_SECRET` is correct
3. **Check logs**: Look for errors in `storage/logs/laravel.log`
4. **Test manually**: Use a tool like Postman to send a test request

### Slash Commands Not Working

1. **Verify command configuration**: Check slash commands are properly configured in Slack App settings
2. **Check Request URL**: Ensure it points to `https://yourdomain.com/api/slack/webhook`
3. **Verify webhook endpoint**: Run `php artisan route:list` to confirm the route exists
4. **Check signature**: Verify `SLACK_SIGNING_SECRET` is set correctly

### Error: "Slack integration not configured"

This warning appears when neither `SLACK_WEBHOOK_URL` nor `SLACK_BOT_TOKEN` is configured. Add at least one of these to your `.env` file.

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
case '/myproject-new-command':
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

## Reference

- [Slack API Documentation](https://api.slack.com/)
- [Slack Block Kit](https://api.slack.com/block-kit)
- [Slack Events API](https://api.slack.com/events-api)
- [Slack Slash Commands](https://api.slack.com/interactivity/slash-commands)

## Support

For issues or questions, check the application logs or consult the Laravel and Slack documentation.

