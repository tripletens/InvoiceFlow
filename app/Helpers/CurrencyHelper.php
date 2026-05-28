<?php

namespace App\Helpers;

/**
 * CurrencyHelper
 *
 * Utility class for formatting monetary values in a consistent, readable way.
 */
class CurrencyHelper
{
    /**
     * Format a numeric value as a currency string.
     *
     * Example: 1500.5 -> "$1,500.50"
     */
    public static function format(float $amount, ?string $currency = null): string
    {
        if (empty($currency)) {
            $currency = auth()->check() ? (auth()->user()->default_currency ?? 'USD') : 'USD';
        }

        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'NGN' => '₦',
            'CAD' => 'CA$',
        ];

        $symbol = $symbols[$currency] ?? $currency . ' ';

        return $symbol . number_format($amount, 2);
    }

    /**
     * Return a CSS color class based on invoice status.
     */
    public static function statusColor(string $status): string
    {
        return match ($status) {
            'draft'   => 'text-slate-400',
            'sent'    => 'text-blue-400',
            'viewed'  => 'text-cyan-400',
            'paid'    => 'text-teal-400',
            'overdue' => 'text-red-400',
            default   => 'text-slate-400',
        };
    }

    /**
     * Return a readable label for an invoice status.
     */
    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'draft'   => '📝 Draft',
            'sent'    => '📤 Sent',
            'viewed'  => '👁️ Viewed',
            'paid'    => '✅ Paid',
            'overdue' => '⚠️ Overdue',
            default   => ucfirst($status),
        };
    }
}
