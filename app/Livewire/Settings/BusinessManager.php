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

    protected array $rules = [
        'name'    => 'required|string|max:255',
        'email'   => 'nullable|email|max:255',
        'phone'   => 'nullable|string|max:255',
        'address' => 'nullable|string|max:500',
    ];

    public function openCreate()
    {
        if (!auth()->user()->canCreateBusiness()) {
            session()->flash('error', 'You have reached your Business Profile limit. Please upgrade your plan to add more profiles.');
            $this->redirect(route('upgrade'), navigate: true);
            return;
        }

        $this->reset(['name', 'email', 'phone', 'address', 'editing', 'editingId']);
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
        $this->editing = true;
        $this->isCreating = true;
    }

    public function cancelCreate()
    {
        $this->isCreating = false;
        $this->reset(['name', 'email', 'phone', 'address', 'editing', 'editingId']);
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
            ]);

            session()->flash('success', 'Business profile created successfully!');
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Business profile created successfully!']);
        }

        $this->isCreating = false;
        $this->reset(['name', 'email', 'phone', 'address', 'editing', 'editingId']);
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
