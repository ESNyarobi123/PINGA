<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Mark active subscriptions as expired when their end date has passed';

    public function handle(SubscriptionService $service): int
    {
        $count = $service->expireOldSubscriptions();
        $this->info("Expired {$count} subscription(s).");
        return Command::SUCCESS;
    }
}
