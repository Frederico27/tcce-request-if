<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class SetupTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:setup-webhook {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up Telegram webhook for handling bot callbacks';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegramService)
    {
        $url = $this->argument('url') ?? route('telegram.webhook');

        $this->info('Setting up Telegram webhook...');
        $this->info("Webhook URL: {$url}");

        if ($telegramService->setWebhook($url)) {
            $this->info('✅ Telegram webhook has been set successfully!');
            $this->info('Your bot will now receive updates at: ' . $url);
            return Command::SUCCESS;
        } else {
            $this->error('❌ Failed to set up Telegram webhook.');
            $this->error('Please check your TELEGRAM_BOT_TOKEN in .env file and try again.');
            return Command::FAILURE;
        }
    }
}
