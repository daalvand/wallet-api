<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;

class CalculateTotalTransactionsCommand extends Command
{
    protected $signature = 'transactions:total';

    protected $description = 'Calculate the total amount of transactions';

    public function handle(): void
    {
        $total = Transaction::sum('amount');
        $this->info("Total amount of transactions: $total");
    }
}

