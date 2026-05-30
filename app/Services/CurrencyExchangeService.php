<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CurrencyExchangeService
{
    /**
     * Get the exchange rate from a base currency to a target currency.
     * Mocks a call to an external API (like ExchangeRate-API or Fixer.io).
     */
    public function getRate(string $from, string $to): float
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) {
            return 1.0;
        }

        $rates = Cache::remember('exchange_rates', 86400, function () {
            // Mock API Response for demonstration.
            // Base is USD.
            return [
                'USD' => 1.0,
                'EUR' => 0.92,
                'GBP' => 0.79,
                'NGN' => 1400.50,
                'CAD' => 1.36,
            ];
        });

        // Convert $from to USD first, then to $to
        $baseRate = $rates[$from] ?? 1.0;
        $targetRate = $rates[$to] ?? 1.0;

        return $targetRate / $baseRate;
    }

    /**
     * Convert an amount.
     */
    public function convert(float $amount, string $from, string $to): float
    {
        return $amount * $this->getRate($from, $to);
    }
}
