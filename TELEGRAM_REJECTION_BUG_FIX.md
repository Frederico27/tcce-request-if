# Telegram Rejection Bug Fix

## Problem

The rejection status was not changing after replying with a rejection reason in Telegram.

## Root Cause

The issue was in the **cache key mismatch**:

### What Was Happening:

1. User clicks "❌ Reject" button on transaction message (Message ID: **184**)
2. System stores cache with key: `telegram_reject_907796296_184` (using transaction message ID)
3. System sends prompt message "Please provide a reason..." (New Message ID: **185**)
4. User replies to the prompt message (Reply to Message ID: **185**)
5. System looks for cache key: `telegram_reject_907796296_185` (using prompt message ID)
6. **Cache not found!** Because it was stored with ID 184, not 185
7. Reply is ignored, transaction status not updated

### Visual Representation:

```
Before Fix:
┌──────────────────────────────────┐
│ Transaction Message (ID: 184)    │  ← Cache stored with this ID
│ [Approve] [Reject]               │
└──────────────────────────────────┘
           ↓ Click Reject
┌──────────────────────────────────┐
│ Prompt Message (ID: 185)         │  ← User replies to this ID
│ "Please provide reason..."       │
└──────────────────────────────────┘
           ↓ Reply
❌ Cache lookup fails (looking for ID 185, but stored with ID 184)
```

## Solution

Modified the code to:
1. **Send the prompt message first**
2. **Get the prompt message ID from Telegram's response**
3. **Store the cache using the prompt message ID** (not the transaction message ID)
4. When user replies, the cache lookup succeeds because it matches the prompt message ID

### After Fix:

```
After Fix:
┌──────────────────────────────────┐
│ Transaction Message (ID: 184)    │
│ [Approve] [Reject]               │
└──────────────────────────────────┘
           ↓ Click Reject
┌──────────────────────────────────┐
│ Prompt Message (ID: 185)         │  ← Cache stored with this ID
│ "Please provide reason..."       │  ← User replies to this ID
└──────────────────────────────────┘
           ↓ Reply
✅ Cache lookup succeeds (both using ID 185)
✅ Transaction rejected with reason
```

## Code Changes

### 1. TelegramService.php

**Changed return type** from `bool` to `?int` to return the message ID:

```php
// Before:
public function sendMessageWithReply(...): bool
{
    Http::post(...);
    return true;
}

// After:
public function sendMessageWithReply(...): ?int
{
    $response = Http::post(...);
    $responseData = $response->json();
    
    if (isset($responseData['result']['message_id'])) {
        return $responseData['result']['message_id'];  // Return message ID
    }
    return null;
}
```

### 2. TelegramWebhookController.php - promptRejectReason()

**Reordered operations** to send message first, then store cache with prompt message ID:

```php
// Before:
protected function promptRejectReason(...)
{
    // 1. Store cache with transaction message ID
    $cacheKey = "telegram_reject_{$chatId}_{$messageId}";
    Cache::put($cacheKey, [...]);
    
    // 2. Send prompt message (gets new message ID)
    $this->telegramService->sendMessageWithReply($chatId, $promptMessage, $messageId);
}

// After:
protected function promptRejectReason(...)
{
    // 1. Send prompt message FIRST and get its message ID
    $promptMessageId = $this->telegramService->sendMessageWithReply($chatId, $promptMessage, $messageId);
    
    // 2. Store cache using the PROMPT message ID
    if ($promptMessageId) {
        $cacheKey = "telegram_reject_{$chatId}_{$promptMessageId}";  // ← Use prompt message ID
        Cache::put($cacheKey, [
            'transaction_id' => $transaction->id_transactions,
            'user_name' => $userName,
            'chat_id' => $chatId,
            'message_id' => $messageId,  // Original message ID for editing later
        ], now()->addMinutes(10));
    }
}
```

### 3. Added Debug Logging

Added comprehensive logging to track the flow:

- Log when prompt message is sent with both message IDs
- Log when cache is stored with the key
- Log when reply is received
- Log cache lookup results
- Log transaction processing steps

## Testing

### Before Testing:
1. Clear cache: `php artisan cache:clear`
2. Ensure database is connected
3. Check logs are writable

### Test Steps:

1. **Create a transaction** and submit it
2. **Check Telegram** - notification should appear
3. **Click Reject button**
4. **Check logs** - should see:
   ```
   Prompt message sent
   chat_id: 907796296
   original_message_id: 184
   prompt_message_id: 185
   
   Cache stored
   cache_key: telegram_reject_907796296_185
   ```
5. **Reply with reason**: "Testing the fix"
6. **Check logs** - should see:
   ```
   Processing reply message
   reply_to_message_id: 185
   reason: Testing the fix
   
   Cache lookup
   cache_key: telegram_reject_907796296_185
   found: true
   
   Processing transaction rejection
   current_status: pending
   
   Transaction rejection completed successfully
   ```
7. **Verify in Telegram**:
   - Original message should update to show rejection
   - Rejection reason should be displayed
   - Confirmation message should appear
8. **Verify in database**:
   ```bash
   php artisan tinker
   >>> $t = \App\Models\Transactions::latest()->first();
   >>> echo $t->status;  // Should be 'rejected'
   >>> echo $t->rejection_reason;  // Should show your reason
   ```

## Monitoring

To monitor in real-time:
```bash
tail -f storage/logs/laravel.log | grep -E "Prompt message sent|Cache stored|Processing reply|Cache lookup|Transaction rejection"
```

## Success Indicators

✅ Prompt message sent with message ID logged
✅ Cache stored with correct key
✅ Reply message processed
✅ Cache found successfully
✅ Transaction status changes to 'rejected'
✅ Rejection reason stored in database
✅ Original message updated in Telegram
✅ Confirmation message sent

## Rollback Plan

If issues occur, revert these commits:
- TelegramService.php: sendMessageWithReply() method
- TelegramWebhookController.php: promptRejectReason() method

## Related Files

- `app/Services/TelegramService.php`
- `app/Http/Controllers/TelegramWebhookController.php`
- `TELEGRAM_REJECTION_REASON.md` (main feature documentation)
- `TELEGRAM_REJECTION_TESTING_GUIDE.md` (testing guide)
