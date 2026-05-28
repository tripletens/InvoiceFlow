<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'is_admin', 'security_pin', 'default_currency'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    public function trialEndsAt()
    {
        return $this->created_at->addDays(14);
    }

    public function onTrial(): bool
    {
        return now()->lessThan($this->trialEndsAt());
    }

    public function daysLeftOnTrial(): int
    {
        if (!$this->onTrial()) return 0;
        return max(0, now()->diffInDays($this->trialEndsAt(), false));
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()->where('status', 'active')->exists();
    }

    public function hasAccess(): bool
    {
        return true; // Free-forever Starter tier ensures all registered users have access
    }

    public function currentPlan(): string
    {
        if ($this->is_admin) {
            return 'agency'; // Admin gets full agency features
        }

        $activeSub = $this->subscriptions()->where('status', 'active')->first();
        if ($activeSub) {
            return $activeSub->plan_id;
        }

        if ($this->onTrial()) {
            return 'agency'; // Trial users get full agency features for 14 days
        }

        return 'starter';
    }

    public function canCreateInvoice(): bool
    {
        $plan = $this->currentPlan();
        if ($plan === 'pro' || $plan === 'agency') return true;
        
        // Free Starter plan limit: 3 per month
        $countThisMonth = $this->invoices()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        return $countThisMonth < 3;
    }

    public function canCreateBusiness(): bool
    {
        $plan = $this->currentPlan();
        if ($plan === 'agency') return true;

        $count = $this->businesses()->count();

        if ($plan === 'starter') return $count < 1;
        if ($plan === 'pro') return $count < 2; // Pro limit: 2 profiles
        
        return false;
    }
}
