<?php

namespace App\Services\Gateways;

use App\Models\User;
use App\Models\Subscription;
use App\Services\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class StripeGateway implements PaymentGatewayInterface
{
    public function getName(): string
    {
        return 'stripe';
    }

    public function subscribe(User $user, string $planId, array $paymentDetails): Subscription
    {
        // Mocking Stripe API call
        // In reality, this would use Stripe's API or Laravel Cashier
        
        // Cancel existing subscriptions
        $user->subscriptions()->where('status', 'active')->update(['status' => 'canceled']);

        return Subscription::create([
            'user_id' => $user->id,
            'gateway' => $this->getName(),
            'plan_id' => $planId,
            'gateway_subscription_id' => 'sub_stripe_' . Str::random(10),
            'gateway_customer_id' => 'cus_stripe_' . Str::random(10),
            'status' => 'active',
            'current_period_end' => now()->addMonth(),
        ]);
    }

    public function cancelSubscription(Subscription $subscription): bool
    {
        // Mocking cancellation
        $subscription->update(['status' => 'canceled']);
        return true;
    }
}
