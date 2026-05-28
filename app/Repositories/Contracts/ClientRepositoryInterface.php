<?php

namespace App\Repositories\Contracts;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

/**
 * ClientRepositoryInterface
 *
 * Defines the contract for all client data access operations.
 * Any concrete implementation must implement these methods.
 */
interface ClientRepositoryInterface
{
    public function allForUser(int $userId): Collection;
    public function find(int $id): Client;
    public function create(array $data): Client;
    public function update(Client $client, array $data): Client;
    public function delete(Client $client): bool;
}
