<?php

namespace App\Providers;

use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use App\Repositories\Eloquent\ClientRepository;
use App\Repositories\Eloquent\InvoiceRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\InvoiceCreated;
use App\Events\InvoicePaid;
use App\Events\InvoiceOverdue;
use App\Events\ClientCreated;
use App\Listeners\DispatchWebhooks;

/**
 * AppServiceProvider
 *
 * Binds repository interfaces to their concrete Eloquent implementations.
 * This follows the Dependency Inversion Principle — code depends on abstractions, not concretions.
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
    }

    protected static bool $toastrDispatched = false;

    public function boot(): void
    {
        Event::listen([
            InvoiceCreated::class,
            InvoicePaid::class,
            InvoiceOverdue::class,
            ClientCreated::class,
        ], DispatchWebhooks::class);
        \Livewire\Livewire::listen('component.dehydrate', function ($component) {
            \Log::info('Livewire dehydrate hook ran for component: ' . get_class($component) . ' | success: ' . (session()->has('success') ? 'yes (' . session('success') . ')' : 'no') . ' | error: ' . (session()->has('error') ? 'yes (' . session('error') . ')' : 'no'));
            
            if (self::$toastrDispatched) {
                return;
            }

            if (session()->has('success')) {
                \Log::info('Dispatching success toastr event for ' . session('success'));
                $component->dispatch('notify', [
                    'type' => 'success',
                    'message' => session('success')
                ]);
                self::$toastrDispatched = true;
            } elseif (session()->has('error')) {
                \Log::info('Dispatching error toastr event for ' . session('error'));
                $component->dispatch('notify', [
                    'type' => 'error',
                    'message' => session('error')
                ]);
                self::$toastrDispatched = true;
            }
        });
    }
}
