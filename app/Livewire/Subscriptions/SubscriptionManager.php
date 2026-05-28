<?php

namespace App\Livewire\Subscriptions;

use App\Services\PaymentService;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class SubscriptionManager extends Component
{
    public string $activeGateway = 'stripe';
    public string $paystackPublicKey = '';

    public bool $confirmingSubscription = false;
    public string $confirmingPlanId = '';
    public string $confirmPinInput = '';

    public function mount(PaymentService $paymentService): void
    {
        $gateway = $paymentService->getActiveGateway();
        $this->activeGateway = $gateway->getName();
        $this->paystackPublicKey = config('services.paystack.public_key') ?: env('PAYSTACK_PUBLIC_KEY', 'pk_test_a02796e625575b637d4f8fb370ff8ce7c83f6be7');
    }

    public function confirmSubscription(string $planId): void
    {
        if (empty(auth()->user()->security_pin)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Please set up a Security PIN in your Profile first.']);
            return;
        }

        $this->confirmingPlanId = $planId;
        $this->confirmPinInput = '';
        $this->confirmingSubscription = true;
    }

    public function cancelSubscriptionConfirm(): void
    {
        $this->confirmingSubscription = false;
        $this->confirmingPlanId = '';
        $this->confirmPinInput = '';
    }

    public function verifyAndSubscribe(PaymentService $paymentService)
    {
        if (!$this->confirmingPlanId) {
            return;
        }

        if (!Hash::check($this->confirmPinInput, auth()->user()->security_pin)) {
            $this->addError('confirmPinInput', 'Incorrect Security PIN.');
            return;
        }

        $this->confirmingSubscription = false;
        
        $this->subscribe($this->confirmingPlanId, $paymentService);
    }

    public function subscribe(string $planId, PaymentService $paymentService)
    {
        if ($planId === 'starter') {
            // Cancel active subscriptions to downgrade to starter (free)
            auth()->user()->subscriptions()->where('status', 'active')->update(['status' => 'canceled']);
            session()->flash('success', 'You have successfully downgraded to the Free Starter plan.');
            $this->redirect(route('dashboard'), navigate: true);
            return;
        }

        $gateway = $paymentService->getActiveGateway();
        
        if ($gateway->getName() === 'paystack') {
            $amountInKobo = match($planId) {
                'pro' => 1900000,    // 19,000 NGN ($19/mo)
                'agency' => 5900000, // 59,000 NGN ($59/mo)
                default => 1900000
            };

            $this->dispatch('trigger-paystack', [
                'email' => auth()->user()->email,
                'amount' => $amountInKobo,
                'planId' => $planId,
                'key' => $this->paystackPublicKey
            ]);
            return;
        }

        // Default / Stripe / Mock flow
        $gateway->subscribe(auth()->user(), $planId, ['reference' => 'mock_' . \Illuminate\Support\Str::random(10)]);

        session()->flash('success', 'You have successfully subscribed to the ' . ucfirst($planId) . ' plan via ' . ucfirst($gateway->getName()) . '!');
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function verifyPayment(string $reference, string $planId, PaymentService $paymentService)
    {
        $gateway = $paymentService->getActiveGateway();
        
        try {
            $gateway->subscribe(auth()->user(), $planId, ['reference' => $reference]);
            session()->flash('success', 'You have successfully subscribed to the ' . ucfirst($planId) . ' plan via ' . ucfirst($gateway->getName()) . '!');
            $this->redirect(route('dashboard'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $subscriptions = auth()->user()->subscriptions()->latest()->get();

        return view('livewire.subscriptions.subscription-manager', [
            'activeGatewayName' => $this->activeGateway,
            'subscriptions' => $subscriptions
        ])->layout('layouts.app', ['title' => 'Upgrade — InvoiceFlow']);
    }
}
