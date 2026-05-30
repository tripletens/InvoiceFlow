<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Business;

class BusinessManager extends Component
{
    public bool $isCreating = false;
    public bool $editing = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';

    public string $bank_name = '';
    public string $account_name = '';
    public string $account_number = '';
    public string $routing_number = '';

    protected array $rules = [
        'name'    => 'required|string|max:255',
        'email'   => 'nullable|email|max:255',
        'phone'   => 'nullable|string|max:255',
        'address' => 'nullable|string|max:500',
        'bank_name' => 'nullable|string|max:255',
        'account_name' => 'nullable|string|max:255',
        'account_number' => 'nullable|string|max:255',
        'routing_number' => 'nullable|string|max:255',
    ];

    public function openCreate()
    {
        if (!auth()->user()->canCreateBusiness()) {
            session()->flash('error', 'You have reached your Business Profile limit. Please upgrade your plan to add more profiles.');
            $this->redirect(route('upgrade'), navigate: true);
            return;
        }

        $this->reset(['name', 'email', 'phone', 'address', 'bank_name', 'account_name', 'account_number', 'routing_number', 'editing', 'editingId']);
        $this->isCreating = true;
    }

    public function openEdit(int $id)
    {
        $business = auth()->user()->businesses()->findOrFail($id);
        $this->editingId = $business->id;
        $this->name = $business->name;
        $this->email = $business->email ?? '';
        $this->phone = $business->phone ?? '';
        $this->address = $business->address ?? '';
        $this->bank_name = $business->bank_name ?? '';
        $this->account_name = $business->account_name ?? '';
        $this->account_number = $business->account_number ?? '';
        $this->routing_number = $business->routing_number ?? '';
        $this->editing = true;
        $this->isCreating = true;
    }

    public function cancelCreate()
    {
        $this->isCreating = false;
        $this->reset(['name', 'email', 'phone', 'address', 'bank_name', 'account_name', 'account_number', 'routing_number', 'editing', 'editingId']);
    }

    public function save()
    {
        $this->validate();

        if ($this->editing && $this->editingId) {
            $business = auth()->user()->businesses()->findOrFail($this->editingId);
            $business->update([
                'name'    => $this->name,
                'email'   => $this->email,
                'phone'   => $this->phone,
                'address' => $this->address,
                'bank_name' => $this->bank_name,
                'account_name' => $this->account_name,
                'account_number' => $this->account_number,
                'routing_number' => $this->routing_number,
            ]);

            session()->flash('success', 'Business profile updated successfully!');
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Business profile updated successfully!']);
        } else {
            if (!auth()->user()->canCreateBusiness()) {
                session()->flash('error', 'You have reached your Business Profile limit. Please upgrade your plan to add more profiles.');
                $this->redirect(route('upgrade'), navigate: true);
                return;
            }

            auth()->user()->businesses()->create([
                'name'    => $this->name,
                'email'   => $this->email,
                'phone'   => $this->phone,
                'address' => $this->address,
                'bank_name' => $this->bank_name,
                'account_name' => $this->account_name,
                'account_number' => $this->account_number,
                'routing_number' => $this->routing_number,
            ]);

            session()->flash('success', 'Business profile created successfully!');
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Business profile created successfully!']);
        }

        $this->isCreating = false;
        $this->reset(['name', 'email', 'phone', 'address', 'bank_name', 'account_name', 'account_number', 'routing_number', 'editing', 'editingId']);
    }

    public function deleteBusiness(int $id)
    {
        $business = auth()->user()->businesses()->findOrFail($id);

        if ($business->logo) {
            \Storage::disk('public')->delete($business->logo);
        }

        $business->delete();
        session()->flash('success', 'Business profile deleted successfully!');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Business profile deleted successfully!']);
    }

    public function render()
    {
        return view('livewire.settings.business-manager', [
            'businesses' => auth()->user()->businesses()->latest()->get()
        ])->layout('layouts.app', ['title' => 'Business Profiles — InvoiceFlow']);
    }
}
