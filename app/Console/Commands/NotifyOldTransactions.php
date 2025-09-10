<?php

namespace App\Console\Commands;

use App\Models\Transactions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class NotifyOldTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-old-transactions';

    protected $description = 'Test kirim notifikasi Telegram tiap menit untuk transaksi lebih dari 15 hari';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transactions::where('created_at', '<=', now()->subDays(1))
        ->where('action', 'return')
        ->get();

        if ($transactions->isEmpty()) {
            $this->info('Tidak ada transaksi lama.');
            return Command::SUCCESS;
        }

        foreach ($transactions as $trx) {
            $this->sendTelegram($trx);
        }

        $this->info('Notifikasi terkirim.');
        return Command::SUCCESS;
    }

    private function sendTelegram($transaction)
    {
        $botToken = config('services.telegram.bot_token');
        $chatId   = config('services.telegram.chat_id');

        if (!$botToken || !$chatId) {
            return;
        }

        $message = "🔔 *Test Notifikasi*\n\n";
        $message .= "📋 *Transaction ID:* #{$transaction->id_transactions}\n";
        $message .= "📅 *Tanggal:* {$transaction->created_at}\n";
        $message .= "⏳ Transaksi lebih dari 15 hari.";

        Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ]);
    }
}

