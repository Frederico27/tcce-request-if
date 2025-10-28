# Telegram Transaction Rejection Flow Diagram

## Flow Visualization

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    TELEGRAM TRANSACTION REJECTION FLOW                       │
└─────────────────────────────────────────────────────────────────────────────┘

┌───────────┐
│   USER    │
│  (Admin)  │
└─────┬─────┘
      │
      │ 1. Receives notification
      ▼
┌─────────────────────────────────────────┐
│  📱 Telegram Message                    │
│  ────────────────────────────────────  │
│  🔔 New Transaction Submitted           │
│                                         │
│  📋 Transaction ID: TXN-12345           │
│  💰 Amount: $500.00                     │
│  📝 Description: Office Supplies        │
│                                         │
│  ┌──────────┐  ┌──────────┐            │
│  │✅ Approve│  │❌ Reject │◄───────────┼─── 2. User clicks Reject
│  └──────────┘  └──────────┘            │
└─────────────────────────────────────────┘
      │
      │ 3. Webhook receives callback
      ▼
┌─────────────────────────────────────────┐
│  TelegramWebhookController              │
│  ─────────────────────────────────────  │
│  handleCallbackQuery()                  │
│    ↓                                    │
│  promptRejectReason()                   │
│    • Check transaction status           │
│    • Store context in Cache             │
│      Key: telegram_reject_{id}          │
│      Data: transaction_id, user_name    │
│      Expiry: 10 minutes                 │
└─────────────────────────────────────────┘
      │
      │ 4. Send prompt message
      ▼
┌─────────────────────────────────────────┐
│  📱 Telegram Prompt Message             │
│  ────────────────────────────────────  │
│  ⚠️  Please provide a reason for        │
│     rejecting this transaction          │
│                                         │
│  📋 Transaction ID: TXN-12345           │
│                                         │
│  Reply to this message with your        │
│  rejection reason.                      │
│                                         │
│  [Reply forced by Telegram]             │
└─────────────────────────────────────────┘
      │
      │ 5. User replies with reason
      │    "Budget exceeded for Q4"
      ▼
┌─────────────────────────────────────────┐
│  TelegramWebhookController              │
│  ─────────────────────────────────────  │
│  handleReplyMessage()                   │
│    • Extract reply text                 │
│    • Get context from Cache             │
│    • Clear cache entry                  │
│    ↓                                    │
│  rejectTransactionWithReason()          │
│    • Update transaction:                │
│      - status = 'rejected'              │
│      - rejected_by = 'John'             │
│      - rejected_at = now()              │
│      - rejection_reason = reason        │
└─────────────────────────────────────────┘
      │
      │ 6. Update original message
      ▼
┌─────────────────────────────────────────┐
│  📱 Updated Original Message            │
│  ────────────────────────────────────  │
│  ❌ Transaction Rejected                │
│                                         │
│  📋 Transaction ID: TXN-12345           │
│  💰 Amount: $500.00                     │
│  📝 Description: Office Supplies        │
│  ❌ Status: Rejected                    │
│  👤 Rejected by: John                   │
│  🕐 Rejected at: 28/10/2025 11:15       │
│  📄 Rejection Reason:                   │
│     Budget exceeded for Q4              │
└─────────────────────────────────────────┘
      │
      │ 7. Send confirmation
      ▼
┌─────────────────────────────────────────┐
│  📱 Confirmation Message                │
│  ────────────────────────────────────  │
│  ✅ Transaction rejected successfully   │
│     with reason: Budget exceeded for Q4 │
└─────────────────────────────────────────┘


═══════════════════════════════════════════════════════════════════════════════
                              EDGE CASES HANDLED
═══════════════════════════════════════════════════════════════════════════════

┌──────────────────────────────────────────────────────────────────────────┐
│ CASE 1: Transaction Already Processed                                    │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  User clicks Reject → Check status → Status != 'pending'                 │
│                                    ↓                                      │
│                    Show alert: "⚠️  Transaction already approved"         │
│                                    ↓                                      │
│                              No further action                            │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│ CASE 2: Cache Expiration (>10 minutes)                                   │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  User clicks Reject → Prompt sent → User waits 11+ minutes → Replies     │
│                                    ↓                                      │
│                    Cache::get() returns null                              │
│                                    ↓                                      │
│                    Reply is ignored (no action taken)                     │
│                                    ↓                                      │
│                    User must click Reject button again                    │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│ CASE 3: Non-Rejection Reply                                              │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  User replies to non-rejection message                                    │
│                                    ↓                                      │
│                    Cache::get() returns null                              │
│                                    ↓                                      │
│                    Reply is ignored (webhook returns 'ok')                │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘


═══════════════════════════════════════════════════════════════════════════════
                              DATA FLOW DIAGRAM
═══════════════════════════════════════════════════════════════════════════════

┌─────────────┐       ┌──────────────┐       ┌──────────────┐
│   Telegram  │◄─────►│   Laravel    │◄─────►│   Database   │
│     API     │       │  Application │       │   (MySQL)    │
└─────────────┘       └──────────────┘       └──────────────┘
      │                      │                       │
      │ 1. Callback          │                       │
      │    (Reject)          │                       │
      ├─────────────────────►│                       │
      │                      │                       │
      │                      │ 2. Store Context      │
      │                      ├──────────────────────►│
      │                      │    (Cache)            │
      │                      │                       │
      │ 3. Prompt Message    │                       │
      │◄─────────────────────┤                       │
      │    (Force Reply)     │                       │
      │                      │                       │
      │ 4. User Reply        │                       │
      │    (Reason Text)     │                       │
      ├─────────────────────►│                       │
      │                      │                       │
      │                      │ 5. Get Context        │
      │                      │◄──────────────────────┤
      │                      │    (Cache)            │
      │                      │                       │
      │                      │ 6. Update Transaction │
      │                      ├──────────────────────►│
      │                      │    (rejection_reason) │
      │                      │                       │
      │ 7. Edit Message      │                       │
      │◄─────────────────────┤                       │
      │    (Show Rejection)  │                       │
      │                      │                       │
      │ 8. Confirmation      │                       │
      │◄─────────────────────┤                       │
      │                      │                       │


═══════════════════════════════════════════════════════════════════════════════
                            COMPARISON: OLD vs NEW
═══════════════════════════════════════════════════════════════════════════════

OLD FLOW (Before)                    NEW FLOW (After)
──────────────────                   ─────────────────

1. User clicks Reject                1. User clicks Reject
                                     
2. Transaction rejected              2. Bot asks for reason
   immediately                       
                                     3. User provides reason
3. No reason captured                
                                     4. Transaction rejected
4. Message updated                      with reason
   (no reason shown)                 
                                     5. Message updated
                                        (includes reason)

                                     6. Confirmation sent


Steps: 3                             Steps: 6
Reason: ❌ None                      Reason: ✅ Required & Stored
Accountability: ⚠️  Low              Accountability: ✅ High
```

## Key Components

### 1. Cache Storage
```php
Cache::put("telegram_reject_{chatId}_{messageId}", [
    'transaction_id' => $transaction->id_transactions,
    'user_name' => $userName,
    'chat_id' => $chatId,
    'message_id' => $messageId,
], now()->addMinutes(10));
```

### 2. Force Reply
```php
'reply_markup' => json_encode([
    'force_reply' => true,
    'selective' => true
])
```

### 3. Database Update
```php
$transaction->status = 'rejected';
$transaction->rejected_by = $userName;
$transaction->rejected_at = now();
$transaction->rejection_reason = $reason;  // ← New field
$transaction->save();
```

## Benefits Summary

✅ **User Experience**: Simple two-step process
✅ **Data Quality**: All rejections have documented reasons
✅ **Transparency**: Reasons are visible to all stakeholders
✅ **Audit Trail**: Complete rejection history with reasons
✅ **Accountability**: Clear record of who rejected and why
✅ **Non-Breaking**: Approval flow unchanged
