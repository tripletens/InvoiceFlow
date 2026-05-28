<?php

namespace App\Repositories\Contracts;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;

/**
 * InvoiceRepositoryInterface
 *
 * Defines the contract for all invoice data access operations.
 */
interface InvoiceRepositoryInterface
{
    public function allForUser(int $userId, ?string $status = null): Collection;
    public function find(int $id): Invoice;
    public function create(array $data): Invoice;
    public function update(Invoice $invoice, array $data): Invoice;
    public function delete(Invoice $invoice): bool;
    public function updateStatus(Invoice $invoice, string $status): Invoice;
    public function countByStatus(int $userId, string $status): int;
    public function sumByStatus(int $userId, string $status): float;
}
