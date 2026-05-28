<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * ClientService
 *
 * Handles all business logic related to clients.
 * Controllers and Livewire components call this service — never the repository directly.
 */
class ClientService
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository
    ) {}

    /**
     * Get all clients belonging to a user.
     */
    public function getClientsForUser(int $userId): Collection
    {
        return $this->clientRepository->allForUser($userId);
    }

    /**
     * Create a new client for a user.
     */
    public function createClient(int $userId, array $data): Client
    {
        $data['user_id'] = $userId;
        return $this->clientRepository->create($data);
    }

    /**
     * Update an existing client (validates ownership).
     */
    public function updateClient(Client $client, int $userId, array $data): Client
    {
        abort_if($client->user_id !== $userId, 403, 'Unauthorized');
        return $this->clientRepository->update($client, $data);
    }

    /**
     * Delete a client (validates ownership).
     */
    public function deleteClient(Client $client, int $userId): bool
    {
        abort_if($client->user_id !== $userId, 403, 'Unauthorized');
        return $this->clientRepository->delete($client);
    }
}
