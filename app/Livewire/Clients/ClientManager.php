<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Services\ClientService;
use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Support\Facades\Hash;

class ClientManager extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $editing = false;
    public ?int $editingId = null;

    // PIN confirmation variables
    public bool $confirmingDelete = false;
    public ?int $confirmingDeleteId = null;
    public string $confirmPinInput = '';

    // Form fields
    public string $name    = '';
    public string $email   = '';
    public string $phone   = '';
    public string $company = '';
    public string $address = '';

    protected array $rules = [
        'name'    => 'required|string|max:255',
        'email'   => 'required|email|max:255',
        'phone'   => 'nullable|string|max:50',
        'company' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:500',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['name', 'email', 'phone', 'company', 'address', 'editingId']);
        $this->editing   = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $client = Client::findOrFail($id);
        abort_if($client->user_id !== auth()->id(), 403);

        $this->editingId = $id;
        $this->name      = $client->name;
        $this->email     = $client->email;
        $this->phone     = $client->phone ?? '';
        $this->company   = $client->company ?? '';
        $this->address   = $client->address ?? '';
        $this->editing   = true;
        $this->showModal = true;
    }

    public function save(ClientService $clientService): void
    {
        $this->validate();

        $data = [
            'name'    => $this->name,
            'email'   => $this->email,
            'phone'   => $this->phone,
            'company' => $this->company,
            'address' => $this->address,
        ];

        if ($this->editing && $this->editingId) {
            $client = Client::findOrFail($this->editingId);
            $clientService->updateClient($client, auth()->id(), $data);
            session()->flash('success', 'Client updated successfully.');
        } else {
            $clientService->createClient(auth()->id(), $data);
            session()->flash('success', 'Client created successfully.');
        }

        $this->showModal = false;
        $this->reset(['name', 'email', 'phone', 'company', 'address', 'editingId', 'editing']);
    }

    public function confirmDelete(int $id): void
    {
        if (empty(auth()->user()->security_pin)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Please set up a Security PIN in your Profile first.']);
            return;
        }

        $this->confirmingDeleteId = $id;
        $this->confirmPinInput = '';
        $this->confirmingDelete = true;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = false;
        $this->confirmingDeleteId = null;
        $this->confirmPinInput = '';
    }

    public function verifyAndPerformDelete(ClientService $clientService): void
    {
        if (!$this->confirmingDeleteId) {
            return;
        }

        if (!Hash::check($this->confirmPinInput, auth()->user()->security_pin)) {
            $this->addError('confirmPinInput', 'Incorrect Security PIN.');
            return;
        }

        $client = Client::findOrFail($this->confirmingDeleteId);
        $clientService->deleteClient($client, auth()->id());
        
        $this->confirmingDelete = false;
        $this->confirmingDeleteId = null;
        $this->confirmPinInput = '';

        session()->flash('success', 'Client deleted successfully.');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Client deleted successfully.']);
    }

    public function render()
    {
        $clients = Client::where('user_id', auth()->id())
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('company', 'like', "%{$this->search}%");
            }))
            ->withCount('invoices')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.clients.client-manager', compact('clients'))
            ->layout('layouts.app', ['title' => 'Clients — InvoiceFlow']);
    }
}
