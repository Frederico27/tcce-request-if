# Telegram Webhook Integration Guide

This guide explains how to set up and use the Telegram webhook feature for approving and rejecting transactions directly through Telegram.

## Features

- 📱 Receive transaction notifications in Telegram
- ✅ Approve transactions with a single button click
- ❌ Reject transactions with rejection reason requirement
- 🔄 Real-time status updates
- 📊 Track who approved/rejected and when
- 📝 Mandatory rejection reasons for accountability

> **Note**: When rejecting a transaction, users must provide a rejection reason. See [TELEGRAM_REJECTION_REASON.md](TELEGRAM_REJECTION_REASON.md) for details.

## Setup Instructions

### 1. Configure Environment Variables

Add the following to your `.env` file:

```env
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here
```

**How to get these values:**

1. **Bot Token**: 
   - Message [@BotFather](https://t.me/botfather) on Telegram
   - Send `/newbot` and follow the instructions
   - Copy the bot token provided

2. **Chat ID**:
   - Message [@userinfobot](https://t.me/userinfobot) to get your personal chat ID
   - Or create a group, add your bot, and use [@userinfobot](https://t.me/userinfobot) in the group to get the group chat ID

### 2. Run Database Migration

Run the migration to add approval/rejection timestamp fields:

```bash
php artisan migrate
```

This will add the following fields to the `transactions` table:
- `approved_at` - Timestamp when the transaction was approved
- `rejected_at` - Timestamp when the transaction was rejected

### 3. Set Up Telegram Webhook

The webhook URL must be publicly accessible (use ngrok for local development).

#### For Production:

```bash
php artisan telegram:setup-webhook
```

This will automatically use your `APP_URL` to set up the webhook at: `https://yourdomain.com/telegram/webhook`

#### For Local Development with ngrok:

1. Start ngrok:
```bash
ngrok http 80
```

2. Copy the ngrok URL (e.g., `https://abc123.ngrok.io`)

3. Set up the webhook:
```bash
php artisan telegram:setup-webhook https://abc123.ngrok.io/telegram/webhook
```

### 4. Test the Integration

1. Create a test transaction in your application
2. Submit the transaction for approval
3. Check your Telegram chat - you should receive a notification with Approve/Reject buttons
4. Click a button to test the functionality

## How It Works

### Transaction Submission Flow

1. **User Submits Transaction**: When a user submits a transaction, the status is set to `pending`

2. **Telegram Notification Sent**: A formatted message is sent to the configured Telegram chat with inline buttons:
   - ✅ Approve button
   - ❌ Reject button

3. **Button Click**: When someone clicks a button:
   - **Approve**: The transaction status is updated immediately
   - **Reject**: User is prompted to reply with a rejection reason
   - The webhook receives the callback
   - The transaction status is updated (after reason is provided for rejections)
   - The Telegram message is edited to show the new status
   - The approver/rejector's name and timestamp are recorded

> **Rejection Flow**: Rejections require a two-step process. After clicking Reject, users must reply to a prompt message with their reason before the rejection is executed. See [TELEGRAM_REJECTION_REASON.md](TELEGRAM_REJECTION_REASON.md) for the complete rejection workflow.

### Message Format

**Pending Transaction:**
```
🔔 New Transaction Submitted

📋 Transaction ID: TRX-12345
📦 Action: Request
🎯 Activity: Office Supplies
💰 Amount: $1,500.00
📝 Description: Purchase office supplies for Q4
📅 Date: 27/10/2025 14:30
🔄 Status: Pending Review

Please review this transaction and take action.

[✅ Approve] [❌ Reject]
```

**After Approval:**
```
✅ Transaction Approved

📋 Transaction ID: TRX-12345
📦 Action: Request
🎯 Activity: Office Supplies
💰 Amount: $1,500.00
📝 Description: Purchase office supplies for Q4
📅 Date: 27/10/2025 14:30
✅ Status: Approved
👤 Approved by: John Doe
🕐 Approved at: 27/10/2025 14:35
```

**After Rejection:**
```
❌ Transaction Rejected

📋 Transaction ID: TRX-12345
📦 Action: Request
🎯 Activity: Office Supplies
💰 Amount: $1,500.00
📝 Description: Purchase office supplies for Q4
📅 Date: 27/10/2025 14:30
❌ Status: Rejected
👤 Rejected by: Jane Smith
🕐 Rejected at: 27/10/2025 14:40
📄 Rejection Reason: Budget exceeded for this quarter
```

## API Endpoints

### Webhook Endpoint

- **URL**: `/telegram/webhook`
- **Method**: POST
- **Authentication**: None (webhook is validated by Telegram)
- **CSRF**: Excluded from CSRF verification

## Database Schema Updates

### New Fields in `transactions` Table

| Field | Type | Description |
|-------|------|-------------|
| `approved_at` | timestamp | When the transaction was approved (via Telegram or web) |
| `rejected_at` | timestamp | When the transaction was rejected (via Telegram or web) |
| `approved_by` | json | Who approved the transaction (can store Telegram username) |
| `rejected_by` | string | Who rejected the transaction (can store Telegram username) |
| `rejection_reason` | string(255) | Reason provided for rejection (required for Telegram rejections) |

## Code Structure

### Files Added/Modified

1. **Controller**: `app/Http/Controllers/TelegramWebhookController.php`
   - Handles incoming webhook requests
   - Processes approve/reject actions
   - Manages rejection reason workflow
   - Updates transaction status

2. **Service**: `app/Services/TelegramService.php`
   - `sendMessage()` - Send messages with inline keyboards
   - `sendMessageWithReply()` - Send messages requiring user reply
   - `editMessage()` - Update existing messages
   - `answerCallbackQuery()` - Respond to button clicks
   - `setWebhook()` - Configure the webhook URL

3. **Command**: `app/Console/Commands/SetupTelegramWebhook.php`
   - Artisan command to set up the webhook

4. **Routes**: `routes/web.php`
   - Added webhook route (excluded from auth middleware)

5. **Middleware**: `bootstrap/app.php`
   - Excluded `/telegram/*` from CSRF verification

6. **Migration**: `database/migrations/2025_10_27_000435_add_approval_rejection_timestamps_to_transactions.php`
   - Added timestamp fields

## Security Considerations

1. **Webhook Validation**: Telegram webhooks should be validated to ensure they come from Telegram servers
2. **Rate Limiting**: Consider adding rate limiting to the webhook endpoint
3. **Authorization**: Only authorized Telegram users should be able to approve/reject transactions
4. **Audit Trail**: All actions are logged with timestamps and user information

## Troubleshooting

### Webhook Not Receiving Updates

1. Check that your webhook URL is publicly accessible
2. Verify the webhook is set correctly:
```bash
curl https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getWebhookInfo
```

3. Check Laravel logs: `storage/logs/laravel.log`

### Buttons Not Working

1. Ensure the transaction ID is valid
2. Check that the callback_data format is correct: `action:transactionId`
3. Verify the transaction status is `pending`

### Message Not Updating

1. Check the TelegramService logs
2. Verify the bot has permission to edit messages
3. Ensure the message hasn't been deleted

## Future Enhancements

- [x] Add reason field for rejections (Implemented - see [TELEGRAM_REJECTION_REASON.md](TELEGRAM_REJECTION_REASON.md))
- [ ] Multi-level approval workflow
- [ ] Notification to requestor when approved/rejected
- [ ] Daily/weekly summary reports
- [ ] Custom approval rules based on amount
- [ ] Integration with other chat platforms (Slack, Discord)

## Support

For issues or questions, please check the application logs or contact the development team.
