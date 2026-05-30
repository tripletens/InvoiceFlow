<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Mail\InvoiceReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendInvoiceReminders extends Command
{
    protected $signature = 'invoice:send-reminders';
    protected $description = 'Send automated email reminders for upcoming and overdue invoices';

    public function handle()
    {
        $this->info('Scanning for invoices needing reminders...');

        // Invoices due in 3 days
        $upcoming = Invoice::where('status', 'sent')
            ->whereDate('due_date', Carbon::now()->addDays(3)->toDateString())
            ->get();

        // Overdue by 1 day
        $overdue1 = Invoice::whereIn('status', ['sent', 'overdue'])
            ->whereDate('due_date', Carbon::now()->subDays(1)->toDateString())
            ->get();

        // Overdue by 7 days
        $overdue7 = Invoice::whereIn('status', ['sent', 'overdue'])
            ->whereDate('due_date', Carbon::now()->subDays(7)->toDateString())
            ->get();

        $allToRemind = $upcoming->merge($overdue1)->merge($overdue7);

        foreach ($allToRemind as $invoice) {
            // Update status to overdue if applicable
            if (Carbon::now()->startOfDay()->greaterThan($invoice->due_date)) {
                $invoice->update(['status' => 'overdue']);
            }

            // In a real app we'd check if a reminder was already sent today
            Mail::to($invoice->client->email)->send(new InvoiceReminderMail($invoice));
            $this->info("Reminder sent for Invoice {$invoice->invoice_number}");
        }

        $this->info('Dunning process complete. Sent ' . $allToRemind->count() . ' reminders.');
    }
}
