# Telegram Transaction Rejection with Reason

## Overview

This feature enhances the Telegram webhook integration by requiring users to provide a rejection reason before a transaction can be rejected. This ensures proper documentation and accountability for all transaction rejections.

## How It Works

### Previous Behavior
- User clicks "❌ Reject" button
- Transaction is immediately rejected
- No reason required or recorded

### New Behavior
1. User clicks "❌ Reject" button
2. Bot sends a prompt message asking for rejection reason
3. User replies to that message with the reason
4. Transaction is rejected with the reason stored
5. Confirmation message is sent

## User Flow

### Step 1: Initial Rejection Request
When a user clicks the "Reject" button on a transaction notification:

```
✅ Transaction approved successfully!
```
**OR**
```
✍️ Please reply with rejection reason
```

A new message appears:
```
⚠️ Please provide a reason for rejecting this transaction

📋 Transaction ID: TXN-123456

Reply to this message with your rejection reason.
```

### Step 2: User Provides Reason
The user replies to the prompt message with their rejection reason, for example:
```
Budget exceeded for this quarter
```

### Step 3: Transaction Rejected
The bot:
1. Updates the original transaction message with rejection status
2. Includes the rejection reason
3. Sends a confirmation message

**Updated Original Message:**
```
❌ Transaction Rejected

📋 Transaction ID: TXN-123456
📦 Action: Request
🎯 Activity: Office Supplies
💰 Amount: $500.00
📝 Description: Purchase of office equipment
📅 Date: 28/10/2025 10:30
❌ Status: Rejected
👤 Rejected by: John
🕐 Rejected at: 28/10/2025 11:15
📄 Rejection Reason: Budget exceeded for this quarter
```

**Confirmation Message:**
```
✅ Transaction rejected successfully with reason: Budget exceeded for this quarter
```

## Technical Implementation

### Files Modified

1. **app/Http/Controllers/TelegramWebhookController.php**
   - Added `handleReplyMessage()` method to process user replies
   - Modified `handleWebhook()` to handle reply messages
   - Added `promptRejectReason()` method to prompt for rejection reason
   - Added `rejectTransactionWithReason()` method to process rejection with reason
   - Modified `rejectTransaction()` to accept reason parameter
   - Imported `Cache` facade for temporary state storage

2. **app/Services/TelegramService.php**
   - Added `sendMessageWithReply()` method to send messages that require replies
   - Implements force_reply to make it easy for users to respond

3. **app/Models/Transactions.php**
   - Added `rejection_reason` to fillable array

### Cache Management

The implementation uses Laravel's Cache to temporarily store rejection context:

- **Cache Key:** `telegram_reject_{chatId}_{messageId}`
- **Cache Duration:** 10 minutes
- **Stored Data:**
  - `transaction_id`: ID of the transaction being rejected
  - `user_name`: Name of the user rejecting
  - `chat_id`: Telegram chat ID
  - `message_id`: Original message ID

### Database Schema

The `rejection_reason` field already exists in the `transactions` table:
```php
$table->string('rejection_reason', 255)->nullable();
```

## Edge Cases Handled

1. **Transaction Already Processed**: If the transaction status is no longer "pending" when the user tries to reject, they receive a warning message.

2. **Cache Expiration**: If the user takes more than 10 minutes to provide a reason, the cache expires and the reply is ignored (no action taken).

3. **Non-Rejection Replies**: If a user replies to a message that isn't a rejection prompt, the webhook ignores it.

4. **Missing Reason**: If the user sends an empty message, it will still be stored (you may want to add validation for this).

## Testing

### Manual Testing Steps

1. **Setup**: Ensure your Telegram webhook is configured and bot is running

2. **Create a Test Transaction**:
   - Submit a transaction through the application
   - Verify notification appears in Telegram

3. **Test Rejection Flow**:
   - Click "❌ Reject" button
   - Verify prompt message appears
   - Reply with a test reason (e.g., "Testing rejection flow")
   - Verify original message updates with rejection status and reason
   - Verify confirmation message appears

4. **Test Edge Cases**:
   - Try rejecting an already rejected transaction
   - Wait 11+ minutes and try to provide a reason (should be ignored)
   - Reply to a non-rejection message (should be ignored)

### Example Test Scenarios

#### Scenario 1: Normal Rejection
```
Input: Click Reject → Reply "Insufficient budget"
Expected: Transaction rejected with reason "Insufficient budget"
```

#### Scenario 2: Already Processed
```
Input: Approve transaction → Try to reject
Expected: "⚠️ Transaction already admin_approved"
```

#### Scenario 3: Cache Expiration
```
Input: Click Reject → Wait 11 minutes → Reply with reason
Expected: No action taken (cache expired)
```

## Future Enhancements

Potential improvements for this feature:

1. **Minimum Reason Length**: Require rejection reasons to be at least X characters
2. **Reason Templates**: Provide quick-select reasons (buttons) with option for custom text
3. **Multiple Languages**: Support rejection reasons in different languages
4. **Notification**: Notify the transaction requester about the rejection reason via email/Telegram
5. **Reason History**: Track all rejection reasons for analytics
6. **Cancellation**: Allow users to cancel the rejection before providing a reason

## Troubleshooting

### Issue: Rejection reason not saving
- **Check**: Ensure `rejection_reason` is in the `fillable` array of the Transactions model
- **Check**: Verify database column exists and is nullable
- **Check**: Review Laravel logs for any errors

### Issue: No prompt message appears
- **Check**: Verify TelegramService is properly configured
- **Check**: Check if `sendMessageWithReply()` method is working
- **Check**: Review webhook logs

### Issue: Cache not working
- **Check**: Ensure cache driver is configured in `.env`
- **Check**: If using file cache, ensure `storage/framework/cache` is writable
- **Check**: Try clearing cache: `php artisan cache:clear`

## Related Documentation

- [TELEGRAM_INTEGRATION.md](TELEGRAM_INTEGRATION.md) - Main Telegram integration guide
- [Laravel Cache Documentation](https://laravel.com/docs/cache)
- [Telegram Bot API - Force Reply](https://core.telegram.org/bots/api#forcereply)
