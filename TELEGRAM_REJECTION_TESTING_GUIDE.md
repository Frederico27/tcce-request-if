# Testing Guide: Telegram Rejection with Reason

## Prerequisites

Before testing, ensure:
- ✅ Telegram bot is configured with valid token
- ✅ Webhook is set up and accessible
- ✅ Laravel application is running
- ✅ Database has `rejection_reason` column in `transactions` table
- ✅ Cache is configured and working (`php artisan cache:clear` if needed)

## Test Scenarios

### Test 1: Normal Rejection Flow ✅

**Objective**: Verify the complete rejection workflow works correctly

**Steps**:
1. Create a test transaction in the application
2. Submit it for approval (status should be 'pending')
3. Check Telegram - you should see the notification with buttons
4. Click the "❌ Reject" button
5. Verify prompt message appears:
   ```
   ⚠️ Please provide a reason for rejecting this transaction
   📋 Transaction ID: TXN-XXXXX
   Reply to this message with your rejection reason.
   ```
6. Reply to the prompt message with: "Testing rejection reason feature"
7. Verify original message updates to show rejection with reason
8. Verify confirmation message appears: "✅ Transaction rejected successfully with reason: Testing rejection reason feature"
9. Check database - verify `rejection_reason` field contains the reason

**Expected Results**:
- ✅ Prompt message received
- ✅ Reply accepted and processed
- ✅ Original message updated with rejection details including reason
- ✅ Confirmation message sent
- ✅ Database updated with rejection reason

---

### Test 2: Transaction Already Processed ⚠️

**Objective**: Verify rejection cannot happen on already processed transactions

**Steps**:
1. Create a test transaction
2. Approve it first (via web or Telegram)
3. Try to click the "❌ Reject" button
4. Verify alert appears: "⚠️ Transaction already admin_approved"
5. Verify no prompt message is sent

**Expected Results**:
- ✅ Alert message shown
- ✅ No prompt for rejection reason
- ✅ Transaction status remains unchanged

---

### Test 3: Cache Expiration ⏱️

**Objective**: Verify expired cache is handled gracefully

**Steps**:
1. Create a test transaction
2. Click "❌ Reject" button
3. Wait for 11 minutes (cache expires after 10 minutes)
4. Reply with a rejection reason
5. Verify reply is ignored (no action taken)
6. Click "❌ Reject" button again
7. Reply immediately with rejection reason
8. Verify transaction is rejected

**Expected Results**:
- ✅ First reply (after expiration) is ignored
- ✅ Second attempt (within 10 minutes) works correctly
- ✅ No error messages or crashes

**Note**: To speed up testing, you can temporarily modify the cache expiry time:
```php
// In TelegramWebhookController.php, change:
], now()->addMinutes(10));
// To:
], now()->addSeconds(30));  // For testing only
```

---

### Test 4: Multiple Rejection Attempts 🔄

**Objective**: Verify multiple users cannot reject the same transaction

**Steps**:
1. Create a test transaction
2. User A clicks "❌ Reject" button
3. User A receives prompt
4. User B clicks "❌ Reject" button on the same transaction
5. User B receives prompt
6. User A replies with reason first
7. Transaction is rejected
8. User B replies with reason
9. Verify User B's reply is ignored (transaction already rejected)

**Expected Results**:
- ✅ First user's rejection is processed
- ✅ Second user's reply is ignored
- ✅ Transaction rejected only once

---

### Test 5: Empty Rejection Reason 📝

**Objective**: Verify behavior with empty/whitespace-only reasons

**Steps**:
1. Create a test transaction
2. Click "❌ Reject" button
3. Reply with only spaces: "   "
4. Verify transaction is rejected with whitespace reason
5. Check if validation should be added for minimum reason length

**Current Behavior**:
- ⚠️ Empty reasons are accepted (spaces are trimmed but empty string allowed)

**Recommendation**:
Consider adding validation in `handleReplyMessage()`:
```php
$rejectionReason = trim($message['text']);

if (empty($rejectionReason)) {
    $this->telegramService->sendMessage(
        "❌ Rejection reason cannot be empty. Please reply again with a valid reason.",
        $chatId
    );
    return response()->json(['status' => 'ok']);
}
```

---

### Test 6: Long Rejection Reason 📏

**Objective**: Verify handling of reasons exceeding database field length (255 chars)

**Steps**:
1. Create a test transaction
2. Click "❌ Reject" button
3. Reply with a reason longer than 255 characters (e.g., 300 characters)
4. Verify behavior (may truncate or fail)

**Current Behavior**:
- ⚠️ May cause database error or truncation depending on MySQL strict mode

**Recommendation**:
Add length validation:
```php
if (strlen($rejectionReason) > 255) {
    $this->telegramService->sendMessage(
        "❌ Rejection reason is too long (max 255 characters). Please provide a shorter reason.",
        $chatId
    );
    return response()->json(['status' => 'ok']);
}
```

---

### Test 7: Non-Rejection Replies 💬

**Objective**: Verify non-rejection replies don't interfere

**Steps**:
1. Send any message to the bot
2. Reply to any non-rejection message
3. Verify reply is ignored (no action taken)
4. Check logs to ensure no errors

**Expected Results**:
- ✅ Reply ignored gracefully
- ✅ No errors logged
- ✅ Webhook returns 'ok' status

---

### Test 8: Database Verification 🗄️

**Objective**: Verify rejection reason is properly stored in database

**Steps**:
1. Create and reject a transaction via Telegram
2. Connect to database:
   ```bash
   php artisan tinker
   ```
3. Query the transaction:
   ```php
   $transaction = \App\Models\Transactions::where('id_transactions', 'TXN-XXXXX')->first();
   echo $transaction->rejection_reason;
   echo $transaction->rejected_by;
   echo $transaction->rejected_at;
   ```
4. Verify all fields are populated correctly

**Expected Results**:
- ✅ `rejection_reason` contains the provided reason
- ✅ `rejected_by` contains the Telegram user's name
- ✅ `rejected_at` contains the rejection timestamp

---

### Test 9: Special Characters in Reason 🔣

**Objective**: Verify special characters and emojis are handled correctly

**Steps**:
1. Create a test transaction
2. Click "❌ Reject" button
3. Reply with reason containing special characters: "Budget exceeded! 💰 Can't approve ❌"
4. Verify transaction is rejected
5. Verify reason displays correctly in Telegram message
6. Verify reason is stored correctly in database

**Expected Results**:
- ✅ Special characters handled correctly
- ✅ Emojis displayed properly
- ✅ Database stores UTF-8 characters correctly

---

### Test 10: Concurrent Rejections ⚡

**Objective**: Test behavior with rapid multiple rejection attempts

**Steps**:
1. Create a test transaction
2. Quickly click "❌ Reject" button multiple times
3. Verify only one prompt is sent or multiple prompts handled correctly
4. Reply to one of the prompts
5. Verify transaction is rejected only once

**Expected Results**:
- ✅ Multiple prompts may be sent but only first reply is processed
- ✅ Transaction status changes only once
- ✅ No database errors from concurrent updates

---

## Quick Test Commands

### Check Webhook Status
```bash
curl https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getWebhookInfo
```

### Check Cache
```bash
php artisan tinker
>>> Cache::get('telegram_reject_123_456');
```

### Clear Cache
```bash
php artisan cache:clear
```

### View Recent Logs
```bash
tail -f storage/logs/laravel.log
```

### Check Transaction Status
```bash
php artisan tinker
>>> $transaction = \App\Models\Transactions::latest()->first();
>>> echo $transaction->status;
>>> echo $transaction->rejection_reason;
```

## Test Checklist

Copy this checklist for your testing session:

```
□ Test 1: Normal Rejection Flow
□ Test 2: Transaction Already Processed
□ Test 3: Cache Expiration
□ Test 4: Multiple Rejection Attempts
□ Test 5: Empty Rejection Reason
□ Test 6: Long Rejection Reason
□ Test 7: Non-Rejection Replies
□ Test 8: Database Verification
□ Test 9: Special Characters in Reason
□ Test 10: Concurrent Rejections

Additional Checks:
□ Webhook logs reviewed
□ Database fields verified
□ Cache working properly
□ No PHP errors
□ Telegram messages formatted correctly
□ Emojis displaying correctly
```

## Automated Testing (Optional)

Consider creating PHPUnit tests:

```php
// tests/Feature/TelegramRejectionTest.php

public function test_rejection_requires_reason()
{
    // Create transaction
    // Simulate reject button click
    // Assert prompt is sent
    // Simulate reply with reason
    // Assert transaction is rejected with reason
}

public function test_already_processed_transaction_cannot_be_rejected()
{
    // Create and approve transaction
    // Simulate reject button click
    // Assert warning message shown
}
```

## Troubleshooting

### Issue: Prompt message not sent
**Check**: 
- Webhook URL is accessible
- Bot token is correct
- TelegramService is working
- Check logs: `tail -f storage/logs/laravel.log`

### Issue: Reply not processed
**Check**:
- Cache is working: `php artisan cache:clear`
- Reply is to the correct message
- Cache hasn't expired (within 10 minutes)

### Issue: Database not updated
**Check**:
- `rejection_reason` is in `fillable` array
- Database column exists
- No database connection errors

### Issue: Special characters broken
**Check**:
- Database charset is UTF-8
- Telegram parse_mode is set to 'Markdown'
- No character encoding issues

## Success Criteria

The feature is working correctly when:
- ✅ All 10 test scenarios pass
- ✅ No errors in Laravel logs
- ✅ Database correctly stores rejection reasons
- ✅ Telegram messages display correctly
- ✅ Edge cases handled gracefully
- ✅ User experience is smooth and intuitive
