<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Application\Sharing\SendShareReminders;
use Illuminate\Console\Command;

final class SendShareRemindersCommand extends Command
{
    protected $signature = 'shares:send-reminders';

    protected $description = 'Send reminder emails for shares not downloaded after 3 days';

    public function handle(SendShareReminders $sendShareReminders): int
    {
        $sent = $sendShareReminders->execute();

        $this->info("Reminders sent: {$sent}");

        return Command::SUCCESS;
    }
}
