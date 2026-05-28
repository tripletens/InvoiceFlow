<?php

namespace App\Services;

use App\Models\Setting;
use App\Services\Contracts\PaymentGatewayInterface;
use App\Services\Gateways\PaystackGateway;
use App\Services\Gateways\StripeGateway;

class PaymentService
{
    /**
     * Get the currently active payment gateway based on Admin settings.
     */
    public function getActiveGateway(): PaymentGatewayInterface
    {
        // Default to Stripe if setting doesn't exist
        $activeGatewayStr = Setting::where('key', 'active_payment_gateway')->value('value') ?? 'stripe';

        if ($activeGatewayStr === 'paystack') {
            return new PaystackGateway();
        }

        return new StripeGateway();
    }
}
