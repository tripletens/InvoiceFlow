<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Business;

class InvoiceDesigner extends Component
{
    public string $plan = 'starter';

    // Business info
    public ?int $selectedBusinessId = null;
    public ?Business $selectedBusiness = null;

    // Design settings
    public string $primaryColor = '#06b6d4';
    public string $accentColor = '#14b8a6';
    public string $fontFamily = 'Inter';
    public string $templateStyle = 'modern';
    public bool $showTax = true;
    public bool $showQty = true;
    public bool $showNotes = true;
    public bool $showTagline = true;
    public string $tagline = '';
    public string $invoiceFooter = '';

    protected array $rules = [
        'primaryColor'  => 'required|string',
        'accentColor'   => 'required|string',
        'fontFamily'    => 'required|string|in:Inter,Roboto,Outfit,Lora,Courier Prime,Montserrat,Open Sans,Poppins,Playfair Display,Merriweather,Space Mono,Fira Code,Oswald,Raleway,Lato,Nunito,Ubuntu,Source Serif Pro,Inconsolata,Dancing Script',
        'templateStyle' => 'required|string|in:modern,classic,minimalist,corporate,bold,elegant,tech,studio,monospace,geometric,agency,vintage,high_contrast,pastel,brutalist,compact,neon,newspaper,retail,executive',
        'showTax'       => 'required|boolean',
        'showQty'       => 'required|boolean',
        'showNotes'     => 'required|boolean',
        'showTagline'   => 'required|boolean',
        'tagline'       => 'nullable|string|max:200',
        'invoiceFooter' => 'nullable|string|max:500',
    ];

    public function mount(): void
    {
        $this->plan = auth()->user()->currentPlan();

        $business = auth()->user()->businesses()->first();
        if ($business) {
            $this->loadBusiness($business->id);
        }
    }

    public function loadBusiness(int $id): void
    {
        $business = auth()->user()->businesses()->findOrFail($id);
        $this->selectedBusinessId = $business->id;
        $this->selectedBusiness   = $business;
        $this->primaryColor       = $business->primary_color ?? '#06b6d4';
        $this->accentColor        = $business->accent_color  ?? '#14b8a6';
        $this->fontFamily         = $business->font_family   ?? 'Inter';
        $this->templateStyle      = $business->template_style ?? 'modern';
        $this->showTax            = (bool)($business->show_tax ?? true);
        $this->showQty            = (bool)($business->show_qty ?? true);
        $this->showNotes          = (bool)($business->show_notes ?? true);
        $this->showTagline        = (bool)($business->show_tagline ?? true);
        $this->tagline            = $business->tagline ?? '';
        $this->invoiceFooter      = $business->invoice_footer ?? '';
    }

    public function updatedSelectedBusinessId($value): void
    {
        if ($value) {
            $this->loadBusiness((int) $value);
        }
    }

    public function save(): void
    {
        if ($this->plan === 'starter') {
            session()->flash('error', 'Custom branding is a premium feature. Please upgrade to Pro or Agency to save design preferences.');
            return;
        }

        $this->validate();

        if ($this->selectedBusiness) {
            $this->selectedBusiness->update([
                'primary_color'  => $this->primaryColor,
                'accent_color'   => $this->accentColor,
                'font_family'    => $this->fontFamily,
                'template_style' => $this->templateStyle,
                'show_tax'       => $this->showTax,
                'show_qty'       => $this->showQty,
                'show_notes'     => $this->showNotes,
                'show_tagline'   => $this->showTagline,
                'tagline'        => $this->tagline,
                'invoice_footer' => $this->invoiceFooter,
            ]);
            session()->flash('success', 'Invoice template design saved successfully!');
        } else {
            session()->flash('error', 'Please create a Business Profile first before designing invoices.');
        }
    }

    public function render()
    {
        $businesses = auth()->user()->businesses()->get();

        return view('livewire.settings.invoice-designer', compact('businesses'))
            ->layout('layouts.app', ['title' => 'Custom Invoice Designer — InvoiceFlow']);
    }
}
