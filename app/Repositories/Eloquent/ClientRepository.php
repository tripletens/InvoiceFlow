<?php

namespace App\Repositories\Eloquent;

use App\Models\Client;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * ClientRepository
 *
 * Concrete Eloquent implementation of ClientRepositoryInterface.
 * All direct database queries for clients live here.
 */
class ClientRepository implements ClientRepositoryInterface
{
    public function allForUser(int $userId): Collection
    {
        return Client::where('user_id', $userId)
            ->withCount('invoices')
            ->orderBy('name')
            ->get();
    }

    public function find(int $id): Client
    {
        return Client::findOrFail($id);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);
        return $client->fresh();
    }

    public function delete(Client $client): bool
    {
        return $client->delete();
    }
}
