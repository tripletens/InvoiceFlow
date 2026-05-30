<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncExchangeRatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:sync-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync daily exchange rates from API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching exchange rates...');
        
        // In a real scenario, this would make an HTTP request to an API
        $rates = [
            'USD' => 1.0,
            'EUR' => 0.92 + (rand(-5, 5) / 1000), // slight fluctuation for realism
            'GBP' => 0.79 + (rand(-5, 5) / 1000),
            'NGN' => 1400.50 + (rand(-50, 50)),
            'CAD' => 1.36 + (rand(-5, 5) / 1000),
        ];

        Cache::put('exchange_rates', $rates, 86400);

        $this->info('Exchange rates synced successfully.');
    }
}
