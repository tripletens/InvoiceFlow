<?php

namespace App\Repositories\Eloquent;

use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * InvoiceRepository
 *
 * Concrete Eloquent implementation of InvoiceRepositoryInterface.
 * All direct database queries for invoices live here.
 */
class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function allForUser(int $userId, ?string $status = null): Collection
    {
        $query = Invoice::with(['client', 'items'])
            ->where('user_id', $userId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function find(int $id): Invoice
    {
        return Invoice::with(['client', 'items', 'payments'])->findOrFail($id);
    }

    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        $invoice->update($data);
        return $invoice->fresh(['client', 'items']);
    }

    public function delete(Invoice $invoice): bool
    {
        return $invoice->delete();
    }

    public function updateStatus(Invoice $invoice, string $status): Invoice
    {
        $timestamps = [
            'sent'    => ['sent_at' => now()],
            'viewed'  => ['viewed_at' => now()],
            'paid'    => ['paid_at' => now()],
            'overdue' => [],
            'draft'   => [],
        ];

        $invoice->update(array_merge(['status' => $status], $timestamps[$status] ?? []));
        return $invoice->fresh();
    }

    public function countByStatus(int $userId, string $status): int
    {
        return Invoice::where('user_id', $userId)->where('status', $status)->count();
    }

    public function sumByStatus(int $userId, string $status): float
    {
        return (float) Invoice::where('user_id', $userId)
            ->where('status', $status)
            ->sum('total');
    }
}
