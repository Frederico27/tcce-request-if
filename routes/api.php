<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramWebhookController;

Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handleWebhook'])
    ->name('telegram.webhook');
