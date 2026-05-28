<?php

namespace App\Services\Contracts;

use App\Models\User;
use App\Models\Subscription;

interface PaymentGatewayInterface
{
    /**
     * Get the internal name of the gateway (e.g., 'stripe', 'paystack')
     */
    public function getName(): string;

    /**
     * Subscribe a user to a specific plan
     */
    public function subscribe(User $user, string $planId, array $paymentDetails): Subscription;

    /**
     * Cancel an active subscription
     */
    public function cancelSubscription(Subscription $subscription): bool;
}
