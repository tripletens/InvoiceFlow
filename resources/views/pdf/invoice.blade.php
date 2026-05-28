@php
    $business = $invoice->user->businesses()->first();
    $primaryColor = $business?->primary_color ?? '#06b6d4';
    $accentColor = $business?->accent_color ?? '#14b8a6';
    $fontFamily = $business?->font_family ?? 'Inter';
    $templateStyle = $business?->template_style ?? 'modern';
    $showTax = $business ? (bool)$business->show_tax : true;
    $showQty = $business ? (bool)$business->show_qty : true;
    $showNotes = $business ? (bool)$business->show_notes : true;
    $showTagline = $business ? (bool)$business->show_tagline : true;
    $tagline = $business?->tagline;
    $invoiceFooter = $business?->invoice_footer ?? $business?->address;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: '{{ $fontFamily }}', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.5;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            background: #fff;
        }
        
        /* Modern Layout */
        .header-modern {
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 3px solid {{ $primaryColor }};
            padding-bottom: 20px;
        }
        .header-modern table {
            width: 100%;
            border-collapse: collapse;
        }
        .logo-section {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
        }
        .logo-accent {
            color: {{ $primaryColor }};
        }
        .invoice-title {
            text-align: right;
            font-size: 24px;
            color: #475569;
            font-weight: bold;
        }

        /* Classic Layout */
        .header-classic {
            text-align: center;
            border-b: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
        .header-classic-title {
            font-size: 26px;
            font-weight: bold;
            color: {{ $primaryColor }};
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        /* Minimalist Layout */
        .header-minimalist {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 15px;
        }
        .header-minimalist table {
            width: 100%;
            border-collapse: collapse;
        }

        .addresses {
            width: 100%;
            margin-bottom: 40px;
        }
        .addresses table {
            width: 100%;
            border-collapse: collapse;
        }
        .address-col {
            width: 50%;
            vertical-align: top;
        }
        .address-title {
            font-size: 11px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .address-name {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 4px 0;
        }
        .address-details {
            color: #475569;
            margin: 0;
            font-size: 13px;
        }
        .details-col {
            width: 50%;
            text-align: right;
            vertical-align: top;
        }
        .details-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: inline-block;
            text-align: left;
        }
        .details-list li {
            margin-bottom: 6px;
            font-size: 13px;
        }
        .details-label {
            color: #64748b;
            font-weight: 500;
        }
        .details-val {
            color: #0f172a;
            font-weight: bold;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8fafc;
            border-bottom: 2px solid {{ $accentColor }};
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            padding: 12px;
            text-align: left;
            letter-spacing: 0.5px;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .totals-section {
            width: 100%;
            margin-bottom: 40px;
        }
        .totals-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 12px;
            font-size: 13px;
        }
        .totals-label {
            color: #64748b;
            text-align: left;
        }
        .totals-val {
            text-align: right;
            color: #0f172a;
            font-weight: 500;
        }
        .grand-total-row {
            border-top: 2px solid {{ $primaryColor }};
        }
        .grand-total-row td {
            padding-top: 15px;
        }
        .grand-total-label {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .grand-total-val {
            font-size: 20px;
            font-weight: bold;
            color: {{ $primaryColor }};
        }
        .notes-section {
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            margin-top: 40px;
        }
        .notes-title {
            font-size: 11px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .notes-content {
            font-size: 12px;
            color: #475569;
            margin: 0;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        
        {{-- Header rendering based on template style --}}
        @if($templateStyle === 'modern')
            <div class="header-modern">
                <table>
                    <tr>
                        <td class="logo-section">
                            {{ $business?->name ?? 'Invoice' }}<span class="logo-accent">Flow</span>
                            @if($showTagline && $tagline)
                                <div style="font-size: 12px; font-weight: normal; color: #64748b; margin-top: 4px; font-style: italic;">{{ $tagline }}</div>
                            @endif
                        </td>
                        <td class="invoice-title">
                            INVOICE
                        </td>
                    </tr>
                </table>
            </div>
        @elseif($templateStyle === 'classic')
            <div class="header-classic">
                <div class="header-classic-title">{{ $business?->name ?? 'InvoiceFlow' }}</div>
                @if($showTagline && $tagline)
                    <div style="font-size: 13px; color: #64748b; font-style: italic; margin-bottom: 10px;">{{ $tagline }}</div>
                @endif
                <div style="font-size: 12px; color: #475569;">{{ $business?->address ?? '' }}</div>
            </div>
        @else {{-- Minimalist --}}
            <div class="header-minimalist">
                <table>
                    <tr>
                        <td style="font-size: 18px; font-weight: bold; color: #1e293b;">
                            {{ $business?->name ?? 'InvoiceFlow' }}
                            @if($showTagline && $tagline)
                                <div style="font-size: 11px; font-weight: normal; color: #64748b; margin-top: 2px;">{{ $tagline }}</div>
                            @endif
                        </td>
                        <td style="text-align: right; font-size: 14px; font-weight: bold; color: {{ $primaryColor }}; text-transform: uppercase;">
                            Invoice
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        <div class="addresses">
            <table>
                <tr>
                    <td class="address-col">
                        <div class="address-title">Billed To</div>
                        <div class="address-name">{{ $invoice->client->name }}</div>
                        @if($invoice->client->company)
                        <div class="address-details" style="font-weight: 500;">{{ $invoice->client->company }}</div>
                        @endif
                        <div class="address-details">{{ $invoice->client->email }}</div>
                        @if($invoice->client->phone)
                        <div class="address-details">{{ $invoice->client->phone }}</div>
                        @endif
                        @if($invoice->client->address)
                        <div class="address-details" style="margin-top: 4px; max-width: 250px;">{{ $invoice->client->address }}</div>
                        @endif
                    </td>
                    <td class="details-col">
                        <div class="address-title">Invoice Details</div>
                        <ul class="details-list">
                            <li>
                                <span class="details-label">Invoice No:</span>
                                <span class="details-val">{{ $invoice->invoice_number }}</span>
                            </li>
                            <li>
                                <span class="details-label">Issue Date:</span>
                                <span class="details-val">{{ $invoice->issue_date->format('M d, Y') }}</span>
                            </li>
                            <li>
                                <span class="details-label">Due Date:</span>
                                <span class="details-val">{{ $invoice->due_date->format('M d, Y') }}</span>
                            </li>
                            <li>
                                <span class="details-label">Status:</span>
                                <span class="details-val" style="color: {{ $invoice->status === 'paid' ? '#0f766e' : ($invoice->status === 'overdue' ? '#b91c1c' : '#475569') }};">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    @if($showQty)
                    <th style="text-align: center; width: 60px;">Qty</th>
                    <th style="text-align: right; width: 100px;">Unit Price</th>
                    @endif
                    <th style="text-align: right; width: 120px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    @if($showQty)
                    <td style="text-align: center;">{{ number_format($item->quantity, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item->unit_price, 2) }}</td>
                    @endif
                    <td style="text-align: right; font-weight: 500;">${{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="totals-label">Subtotal</td>
                    <td class="totals-val">${{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                @if($showTax && $invoice->tax_rate > 0)
                <tr>
                    <td class="totals-label">Tax ({{ $invoice->tax_rate }}%)</td>
                    <td class="totals-val">${{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total-row">
                    <td class="grand-total-label">Total Due</td>
                    <td class="grand-total-val">${{ number_format($invoice->total, 2) }}</td>
                </tr>
            </table>
        </div>

        @if($showNotes && ($invoice->notes || $invoiceFooter))
        <div class="notes-section">
            <div class="notes-title">Notes & Terms</div>
            <p class="notes-content">{{ $invoice->notes }}</p>
            @if($invoiceFooter)
            <p class="notes-content" style="margin-top: 10px; color: #64748b; font-style: italic;">{{ $invoiceFooter }}</p>
            @endif
        </div>
        @endif
    </div>
</body>
</html>
