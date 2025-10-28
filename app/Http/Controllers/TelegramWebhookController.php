<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function handleWebhook(Request $request)
    {
        try {
            $update = $request->all();
            Log::info('Telegram Webhook Received:', $update);

            // Handle callback query (button presses)
            if (isset($update['callback_query'])) {
                return $this->handleCallbackQuery($update['callback_query']);
            }

            // Handle text messages (for rejection reasons)
            if (isset($update['message']['text']) && isset($update['message']['reply_to_message'])) {
                return $this->handleReplyMessage($update['message']);
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Telegram Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    protected function handleCallbackQuery(array $callbackQuery)
    {
        $chatId = $callbackQuery['message']['chat']['id'];
        $messageId = $callbackQuery['message']['message_id'];
        $callbackData = $callbackQuery['data'] ?? $callbackQuery['callback_data'] ?? '';
        $callbackId = $callbackQuery['id'];
        $userName = $callbackQuery['from']['first_name'] ?? 'User';

        // Parse callback data: format is "action:transactionId"
        $parts = explode(':', $callbackData, 2);
        if (count($parts) !== 2 || empty($parts[1])) {
            Log::error('Invalid callback data format: ' . $callbackData);
            $this->answerCallbackQuery($callbackId, '❌ Invalid callback data', true);
            return response()->json(['status' => 'error', 'message' => 'Invalid callback data']);
        }

        [$action, $transactionId] = $parts;

        try {
            $transaction = Transactions::findOrFail($transactionId);

            switch ($action) {
                case 'approve':
                    $this->approveTransaction($transaction, $userName, $chatId, $messageId, $callbackId);
                    break;
                case 'reject':
                    $this->promptRejectReason($transaction, $userName, $chatId, $messageId, $callbackId);
                    break;
                default:
                    $this->answerCallbackQuery($callbackId, '❌ Unknown action', true);
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Error processing callback: ' . $e->getMessage());
            $this->answerCallbackQuery($callbackId, '❌ Error: ' . $e->getMessage(), true);
            return response()->json(['status' => 'error'], 500);
        }
    }

    protected function approveTransaction(Transactions $transaction, string $userName, string $chatId, int $messageId, string $callbackId)
    {
        // Check if already processed
        if ($transaction->status !== 'pending') {
            $this->answerCallbackQuery($callbackId, "⚠️ Transaction already {$transaction->status}", true);
            return;
        }

        // Update transaction status - using 'admin_approved' as the approved status
        $transaction->status = 'admin_approved';

        // approved_by is a JSON field, so we append to the array
        $approvedByData = $transaction->approved_by ?? [];
        $approvedByData[] = $userName;
        $transaction->approved_by = $approvedByData;
        $transaction->approved_at = now();
        $transaction->save();

        // Update the message with new status
        $updatedMessage = "✅ *Transaction Approved*\n\n"
            . "📋 *Transaction ID:* {$transaction->id_transactions}\n"
            . "📦 *Action:* " . ucfirst($transaction->action) . "\n"
            . "🎯 *Activity:* " . $transaction->activity . "\n"
            . "💰 *Amount:* $" . number_format($transaction->amount, 2) . "\n"
            . "📝 *Description:* " . $transaction->description . "\n"
            . "📅 *Date:* " . $transaction->created_at->format('d/m/Y H:i') . "\n"
            . "✅ *Status:* Admin Approved\n"
            . "👤 *Approved by:* {$userName}\n"
            . "🕐 *Approved at:* " . now()->format('d/m/Y H:i');

        $this->editMessage($chatId, $messageId, $updatedMessage);
        $this->answerCallbackQuery($callbackId, '✅ Transaction approved successfully!');
    }
    protected function rejectTransaction(Transactions $transaction, string $userName, string $chatId, int $messageId, string $callbackId, string $reason)
    {
        // Check if already processed
        if ($transaction->status !== 'pending') {
            $this->answerCallbackQuery($callbackId, "⚠️ Transaction already {$transaction->status}", true);
            return;
        }

        // Update transaction status
        $transaction->status = 'rejected';
        $transaction->rejected_by = $userName;
        $transaction->rejected_at = now();
        $transaction->rejection_reason = $reason;
        $transaction->save();

        // Update the message with new status
        $updatedMessage = "❌ *Transaction Rejected*\n\n"
            . "📋 *Transaction ID:* {$transaction->id_transactions}\n"
            . "📦 *Action:* " . ucfirst($transaction->action) . "\n"
            . "🎯 *Activity:* " . $transaction->activity . "\n"
            . "💰 *Amount:* $" . number_format($transaction->amount, 2) . "\n"
            . "📝 *Description:* " . $transaction->description . "\n"
            . "📅 *Date:* " . $transaction->created_at->format('d/m/Y H:i') . "\n"
            . "❌ *Status:* Rejected\n"
            . "👤 *Rejected by:* {$userName}\n"
            . "🕐 *Rejected at:* " . now()->format('d/m/Y H:i') . "\n"
            . "📄 *Rejection Reason:* {$reason}";

        $this->editMessage($chatId, $messageId, $updatedMessage);
        $this->answerCallbackQuery($callbackId, '❌ Transaction rejected');
    }

    protected function promptRejectReason(Transactions $transaction, string $userName, string $chatId, int $messageId, string $callbackId)
    {
        // Check if already processed
        if ($transaction->status !== 'pending') {
            $this->answerCallbackQuery($callbackId, "⚠️ Transaction already {$transaction->status}", true);
            return;
        }

        // Send a prompt message asking for rejection reason
        $promptMessage = "⚠️ *Please provide a reason for rejecting this transaction*\n\n"
            . "📋 *Transaction ID:* {$transaction->id_transactions}\n\n"
            . "Reply to this message with your rejection reason.";

        $promptMessageId = $this->telegramService->sendMessageWithReply($chatId, $promptMessage, $messageId);

        Log::info("Prompt message sent", [
            'transaction_id' => $transaction->id_transactions,
            'chat_id' => $chatId,
            'original_message_id' => $messageId,
            'prompt_message_id' => $promptMessageId
        ]);

        if ($promptMessageId) {
            // Store rejection context in cache for 10 minutes using the PROMPT message ID
            $cacheKey = "telegram_reject_{$chatId}_{$promptMessageId}";
            Cache::put($cacheKey, [
                'transaction_id' => $transaction->id_transactions,
                'user_name' => $userName,
                'chat_id' => $chatId,
                'message_id' => $messageId, // Original transaction message ID for editing later
            ], now()->addMinutes(10));

            Log::info("Cache stored", [
                'cache_key' => $cacheKey,
                'transaction_id' => $transaction->id_transactions
            ]);
        } else {
            Log::error("Failed to send prompt message, no message ID returned");
        }

        $this->answerCallbackQuery($callbackId, '✍️ Please reply with rejection reason');
    }

    protected function handleReplyMessage(array $message)
    {
        try {
            $chatId = $message['chat']['id'];
            $replyToMessageId = $message['reply_to_message']['message_id'];
            $userName = $message['from']['first_name'] ?? 'User';
            $rejectionReason = trim($message['text']);

            Log::info("Processing reply message", [
                'chat_id' => $chatId,
                'reply_to_message_id' => $replyToMessageId,
                'user_name' => $userName,
                'reason' => $rejectionReason
            ]);

            // Check if this is a rejection reason reply
            $cacheKey = "telegram_reject_{$chatId}_{$replyToMessageId}";
            $rejectionContext = Cache::get($cacheKey);

            Log::info("Cache lookup", [
                'cache_key' => $cacheKey,
                'found' => $rejectionContext !== null
            ]);

            if (!$rejectionContext) {
                // Not a rejection reply we're tracking
                Log::info("No rejection context found, ignoring reply");
                return response()->json(['status' => 'ok']);
            }

            // Clear the cache
            Cache::forget($cacheKey);

            // Get the transaction
            $transaction = Transactions::findOrFail($rejectionContext['transaction_id']);

            Log::info("Processing transaction rejection", [
                'transaction_id' => $transaction->id_transactions,
                'current_status' => $transaction->status
            ]);

            // Process the rejection with the reason
            $this->rejectTransactionWithReason(
                $transaction,
                $rejectionContext['user_name'],
                $rejectionContext['chat_id'],
                $rejectionContext['message_id'],
                $rejectionReason
            );

            // Send confirmation
            $this->telegramService->sendMessage(
                "✅ Transaction rejected successfully with reason: {$rejectionReason}",
                $chatId
            );

            Log::info("Transaction rejection completed successfully");

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Error handling reply message: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['status' => 'error'], 500);
        }
    }

    protected function rejectTransactionWithReason(Transactions $transaction, string $userName, string $chatId, int $messageId, string $reason)
    {
        // Check if already processed
        if ($transaction->status !== 'pending') {
            return;
        }

        // Update transaction status
        $transaction->status = 'rejected';
        $transaction->rejected_by = $userName;
        $transaction->rejected_at = now();
        $transaction->rejection_reason = $reason;
        $transaction->save();

        // Update the original message with new status
        $updatedMessage = "❌ *Transaction Rejected*\n\n"
            . "📋 *Transaction ID:* {$transaction->id_transactions}\n"
            . "📦 *Action:* " . ucfirst($transaction->action) . "\n"
            . "🎯 *Activity:* " . $transaction->activity . "\n"
            . "💰 *Amount:* $" . number_format($transaction->amount, 2) . "\n"
            . "📝 *Description:* " . $transaction->description . "\n"
            . "📅 *Date:* " . $transaction->created_at->format('d/m/Y H:i') . "\n"
            . "❌ *Status:* Rejected\n"
            . "👤 *Rejected by:* {$userName}\n"
            . "🕐 *Rejected at:* " . now()->format('d/m/Y H:i') . "\n"
            . "📄 *Rejection Reason:* {$reason}";

        $this->editMessage($chatId, $messageId, $updatedMessage);
    }

    protected function editMessage(string $chatId, int $messageId, string $text)
    {
        $this->telegramService->editMessage($chatId, $messageId, $text);
    }

    protected function answerCallbackQuery(string $callbackId, string $text, bool $showAlert = false)
    {
        $this->telegramService->answerCallbackQuery($callbackId, $text, $showAlert);
    }
}
