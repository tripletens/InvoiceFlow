<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Business;

class BrandingSettings extends Component
{
    use WithFileUploads;

    public string $plan = 'starter';

    // Selected business
    public ?int $selectedBusinessId = null;
    public ?Business $selectedBusiness = null;

    // Logo upload
    public $logo = null; // temp Livewire upload
    public ?string $existingLogo = null;

    // Branding fields (Agency only)
    public string $primaryColor = '#06b6d4';
    public string $accentColor = '#14b8a6';
    public string $invoiceFooter = '';
    public string $tagline = '';

    protected array $rules = [
        'logo'          => 'nullable|image|max:2048', // max 2MB
        'primaryColor'  => 'required|string',
        'accentColor'   => 'required|string',
        'invoiceFooter' => 'nullable|string|max:500',
        'tagline'       => 'nullable|string|max:200',
    ];

    public function mount(): void
    {
        $this->plan = auth()->user()->currentPlan();

        if ($this->plan === 'agency') {
            $first = auth()->user()->businesses()->first();
            if ($first) {
                $this->loadBusiness($first->id);
            }
        }
    }

    public function loadBusiness(int $id): void
    {
        $business = auth()->user()->businesses()->findOrFail($id);
        $this->selectedBusinessId = $business->id;
        $this->selectedBusiness   = $business;
        $this->existingLogo       = $business->logo;
        $this->primaryColor       = $business->primary_color   ?? '#06b6d4';
        $this->accentColor        = $business->accent_color    ?? '#14b8a6';
        $this->tagline            = $business->tagline          ?? '';
        $this->invoiceFooter      = $business->invoice_footer  ?? '';
        $this->logo               = null;
    }

    public function updatedSelectedBusinessId($value): void
    {
        if ($value) {
            $this->loadBusiness((int) $value);
        }
    }

    public function removeLogo(): void
    {
        if ($this->selectedBusiness) {
            if ($this->selectedBusiness->logo) {
                \Storage::disk('public')->delete($this->selectedBusiness->logo);
            }
            $this->selectedBusiness->update(['logo' => null]);
            $this->existingLogo = null;
        }
    }

    public function save(): void
    {
        if ($this->plan !== 'agency') {
            session()->flash('error', 'Custom branding requires an Agency plan.');
            return;
        }

        $this->validate();

        $logoPath = $this->existingLogo;

        if ($this->logo) {
            // Delete old logo if exists
            if ($this->existingLogo) {
                \Storage::disk('public')->delete($this->existingLogo);
            }
            $logoPath = $this->logo->store('logos', 'public');
        }

        if ($this->selectedBusiness) {
            $this->selectedBusiness->update([
                'logo'           => $logoPath,
                'primary_color'  => $this->primaryColor,
                'accent_color'   => $this->accentColor,
                'tagline'        => $this->tagline,
                'invoice_footer' => $this->invoiceFooter,
            ]);
            $this->existingLogo = $logoPath;
        }

        $this->logo = null;
        session()->flash('success', 'Branding preferences saved! They will be applied to all new invoices.');
    }

    public function render()
    {
        $businesses = auth()->user()->businesses()->get();

        return view('livewire.settings.branding-settings', compact('businesses'))
            ->layout('layouts.app', ['title' => 'Custom Branding — InvoiceFlow']);
    }
}
