<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Clients\ClientManager;
use App\Livewire\Dashboard;
use App\Livewire\Invoices\InvoiceCreate;
use App\Livewire\Invoices\InvoiceManager;
use App\Livewire\Invoices\InvoiceShow;
use App\Livewire\Settings\ApiSettings;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified', 'subscribed'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/clients', ClientManager::class)->name('clients.index');
    Route::get('/invoices', InvoiceManager::class)->name('invoices.index');
    Route::get('/recurring-invoices', \App\Livewire\RecurringInvoices\RecurringInvoiceManager::class)->name('recurring-invoices.index');
    Route::get('/expenses', \App\Livewire\Expenses\ExpenseManager::class)->name('expenses.index');
    Route::get('/invoices/create', InvoiceCreate::class)->name('invoices.create');
    Route::get('/invoices/{invoice}', InvoiceShow::class)->name('invoices.show');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/settings/businesses', \App\Livewire\Settings\BusinessManager::class)->name('settings.businesses');
    Route::get('/settings/categories', \App\Livewire\Settings\ExpenseCategoryManager::class)->name('settings.categories');
    Route::get('/settings/api', ApiSettings::class)->name('settings.api');
    Route::get('/settings/reminders', \App\Livewire\Settings\RemindersSettings::class)->name('settings.reminders');
    Route::get('/settings/branding', \App\Livewire\Settings\BrandingSettings::class)->name('settings.branding');
    Route::get('/settings/designer', \App\Livewire\Settings\InvoiceDesigner::class)->name('settings.designer');
    Route::get('/upgrade', \App\Livewire\Subscriptions\SubscriptionManager::class)->name('upgrade');
    Route::get('/profile', \App\Livewire\Profile::class)->name('profile');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
});

require __DIR__.'/auth.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Client Portal Routes
|--------------------------------------------------------------------------
*/
Route::get('/client/login', \App\Livewire\ClientPortal\Login::class)->name('client.login');

Route::middleware(['client.auth'])->group(function () {
    Route::get('/client/dashboard', \App\Livewire\ClientPortal\PortalDashboard::class)->name('client.dashboard');
    Route::post('/client/logout', function () {
        session()->forget('client_id');
        return redirect()->route('client.login');
    })->name('client.logout');
});
