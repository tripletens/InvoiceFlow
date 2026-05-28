<?php

namespace App\Services\Gateways;

use App\Models\User;
use App\Models\Subscription;
use App\Services\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class PaystackGateway implements PaymentGatewayInterface
{
    public function getName(): string
    {
        return 'paystack';
    }

    public function subscribe(User $user, string $planId, array $paymentDetails): Subscription
    {
        $reference = $paymentDetails['reference'] ?? null;

        if (!$reference) {
            throw new \InvalidArgumentException("Paystack transaction reference is required.");
        }

        // Handle offline testing mock references
        if (str_starts_with($reference, 'mock_')) {
            // Cancel existing subscriptions
            $user->subscriptions()->where('status', 'active')->update(['status' => 'canceled']);

            return Subscription::create([
                'user_id' => $user->id,
                'gateway' => $this->getName(),
                'plan_id' => $planId,
                'gateway_subscription_id' => 'sub_paystack_' . Str::random(10),
                'gateway_customer_id' => 'cus_paystack_' . Str::random(10),
                'status' => 'active',
                'current_period_end' => now()->addMonth(),
            ]);
        }

        $secretKey = config('services.paystack.secret_key') ?: env('PAYSTACK_SECRET_KEY');

        if (!$secretKey) {
            throw new \RuntimeException("Paystack secret key is not configured.");
        }

        // Verify transaction via Paystack API
        $response = \Illuminate\Support\Facades\Http::withToken($secretKey)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if (!$response->successful()) {
            throw new \RuntimeException("Failed to communicate with Paystack API. HTTP status: " . $response->status());
        }

        $result = $response->json();

        if (!($result['status'] ?? false) || ($result['data']['status'] ?? '') !== 'success') {
            $message = $result['message'] ?? 'Transaction verification failed.';
            throw new \RuntimeException("Paystack verification failed: " . $message);
        }

        $data = $result['data'];
        $gatewaySubId = 'sub_paystack_' . ($data['id'] ?? Str::random(10));
        $gatewayCustId = $data['customer']['customer_code'] ?? 'cus_paystack_' . Str::random(10);

        // Cancel existing subscriptions
        $user->subscriptions()->where('status', 'active')->update(['status' => 'canceled']);

        // Create new active subscription
        return Subscription::create([
            'user_id' => $user->id,
            'gateway' => $this->getName(),
            'plan_id' => $planId,
            'gateway_subscription_id' => $gatewaySubId,
            'gateway_customer_id' => $gatewayCustId,
            'status' => 'active',
            'current_period_end' => now()->addMonth(),
        ]);
    }

    public function cancelSubscription(Subscription $subscription): bool
    {
        $secretKey = config('services.paystack.secret_key') ?: env('PAYSTACK_SECRET_KEY');

        if ($secretKey && !str_starts_with($subscription->gateway_subscription_id, 'sub_paystack_')) {
            // Cancel subscription on Paystack
            \Illuminate\Support\Facades\Http::withToken($secretKey)
                ->post("https://api.paystack.co/subscription/disable", [
                    'code' => $subscription->gateway_subscription_id,
                    'token' => 'mock_token'
                ]);
        }

        $subscription->update(['status' => 'canceled']);
        return true;
    }
}
