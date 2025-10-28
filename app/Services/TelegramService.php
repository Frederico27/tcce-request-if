<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;
    protected string $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.chat_id');
    }

    public function sendMessage(string $message, ?string $chatId = null, array $keyboard = null): bool
    {
        if (!$this->botToken || (!$this->chatId && !$chatId)) {
            return false;
        }

        try {
            $payload = [
                'chat_id' => $chatId ?? $this->chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ];

            if ($keyboard) {
                $payload['reply_markup'] = json_encode($keyboard);
            }

            Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", $payload);

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram notification failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendMessageWithReply(string $chatId, string $message, int $replyToMessageId): ?int
    {
        if (!$this->botToken) {
            return null;
        }

        try {
            $payload = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'reply_to_message_id' => $replyToMessageId,
                'reply_markup' => json_encode([
                    'force_reply' => true,
                    'selective' => true
                ])
            ];

            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", $payload);
            $responseData = $response->json();

            if (isset($responseData['result']['message_id'])) {
                return $responseData['result']['message_id'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Telegram send message with reply failed: ' . $e->getMessage());
            return null;
        }
    }

    public function editMessage(string $chatId, int $messageId, string $text): bool
    {
        if (!$this->botToken) {
            return false;
        }

        try {
            $payload = [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ];

            Http::post("https://api.telegram.org/bot{$this->botToken}/editMessageText", $payload);

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram edit message failed: ' . $e->getMessage());
            return false;
        }
    }

    public function answerCallbackQuery(string $callbackId, string $text, bool $showAlert = false): bool
    {
        if (!$this->botToken) {
            return false;
        }

        try {
            $payload = [
                'callback_query_id' => $callbackId,
                'text' => $text,
                'show_alert' => $showAlert,
            ];

            Http::post("https://api.telegram.org/bot{$this->botToken}/answerCallbackQuery", $payload);

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram answer callback failed: ' . $e->getMessage());
            return false;
        }
    }

    public function setWebhook(string $url): bool
    {
        if (!$this->botToken) {
            return false;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/setWebhook", [
                'url' => $url,
            ]);

            Log::info('Telegram webhook set: ' . $response->body());
            return true;
        } catch (\Exception $e) {
            Log::error('Telegram set webhook failed: ' . $e->getMessage());
            return false;
        }
    }
}
