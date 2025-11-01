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

