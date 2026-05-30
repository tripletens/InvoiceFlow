<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use App\Events\InvoiceCreated;
use App\Events\InvoicePaid;
use App\Events\InvoiceOverdue;

/**
 * InvoiceService
 *
 * Handles all business logic related to invoices:
 * creation, calculations, status changes, and summary stats.
 */
class InvoiceService
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository
    ) {}

    /**
     * Get all invoices for a user, optionally filtered by status.
     */
    public function getInvoicesForUser(int $userId, ?string $status = null): Collection
    {
        return $this->invoiceRepository->allForUser($userId, $status);
    }

    /**
     * Get a single invoice by ID.
     */
    public function getInvoice(int $id): Invoice
    {
        return $this->invoiceRepository->find($id);
    }

    /**
     * Create a new invoice with its line items.
     *
     * Automatically calculates subtotal, tax, and total.
     */
    public function createInvoice(int $userId, array $data, array $items): Invoice
    {
        // Calculate financials
        $subtotal  = collect($items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        $taxRate   = $data['tax_rate'] ?? 0;
        $taxAmount = $subtotal * ($taxRate / 100);
        $total     = $subtotal + $taxAmount;

        $invoice = $this->invoiceRepository->create(array_merge($data, [
            'user_id'        => $userId,
            'invoice_number' => $this->generateInvoiceNumber(),
            'subtotal'       => $subtotal,
            'tax_amount'     => $taxAmount,
            'total'          => $total,
            'status'         => 'draft',
        ]));

        // Create line items
        foreach ($items as $item) {
            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total'       => $item['quantity'] * $item['unit_price'],
            ]);
        }

        $invoice->load('items', 'client');
        
        InvoiceCreated::dispatch($invoice);

        return $invoice;
    }

    /**
     * Update invoice status (draft → sent → viewed → paid / overdue).
     */
    public function updateStatus(Invoice $invoice, int $userId, string $status): Invoice
    {
        abort_if($invoice->user_id !== $userId, 403, 'Unauthorized');

        $allowedStatuses = ['draft', 'sent', 'viewed', 'paid', 'overdue'];
        abort_unless(in_array($status, $allowedStatuses), 422, 'Invalid status.');

        $updatedInvoice = $this->invoiceRepository->updateStatus($invoice, $status);

        if ($status === 'paid') {
            InvoicePaid::dispatch($updatedInvoice);
        } elseif ($status === 'overdue') {
            InvoiceOverdue::dispatch($updatedInvoice);
        }

        return $updatedInvoice;
    }

    /**
     * Delete an invoice.
     */
    public function deleteInvoice(Invoice $invoice, int $userId): bool
    {
        abort_if($invoice->user_id !== $userId, 403, 'Unauthorized');
        return $this->invoiceRepository->delete($invoice);
    }

    /**
     * Get a dashboard summary of invoice stats for a user.
     */
    public function getDashboardStats(int $userId): array
    {
        return [
            'total_invoiced' => $this->invoiceRepository->sumByStatus($userId, 'paid')
                + $this->invoiceRepository->sumByStatus($userId, 'sent')
                + $this->invoiceRepository->sumByStatus($userId, 'viewed'),
            'paid'           => $this->invoiceRepository->sumByStatus($userId, 'paid'),
            'overdue'        => $this->invoiceRepository->countByStatus($userId, 'overdue'),
            'drafts'         => $this->invoiceRepository->countByStatus($userId, 'draft'),
            'paid_count'     => $this->invoiceRepository->countByStatus($userId, 'paid'),
        ];
    }

    /**
     * Generate a unique invoice number like INV-2024-00042.
     */
    private function generateInvoiceNumber(): string
    {
        $year  = date('Y');
        $count = Invoice::whereYear('created_at', $year)->count() + 1;
        return 'INV-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
