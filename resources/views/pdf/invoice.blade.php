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
        .watermark {
            position: fixed;
            top: 35%;
            left: 0;
            width: 100%;
            text-align: center;
            opacity: 0.04;
            z-index: -1;
            transform: rotate(-30deg);
        }
        .watermark-text {
            font-size: 90px;
            font-weight: 900;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .watermark-img {
            max-width: 500px;
            max-height: 500px;
            opacity: 0.6;
        }
        .authenticity-stamp {
            position: absolute;
            top: 40px;
            right: 40px;
            width: 130px;
            height: 130px;
            opacity: 0.95;
            transform: rotate(-5deg);
            z-index: 50;
            filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.15));
        }
    </style>
</head>
<body>
    <div class="watermark">
        @if($business && $business->logo)
            <img src="{{ storage_path('app/public/' . $business->logo) }}" class="watermark-img" />
        @else
            <div class="watermark-text">{{ $business?->name ?? 'InvoiceFlow' }}</div>
        @endif
    </div>
    <div class="invoice-box">
        
        {{-- Header rendering based on template style --}}
        @include('pdf.templates_pdf')

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

        @if($business && ($business->bank_name || $business->account_number))
        <div class="notes-section">
            <div class="notes-title">Bank Transfer Details</div>
            <p class="notes-content">
                @if($business->bank_name)Bank: <strong style="color: #1e293b;">{{ $business->bank_name }}</strong><br>@endif
                @if($business->account_name)Account Name: <strong style="color: #1e293b;">{{ $business->account_name }}</strong><br>@endif
                @if($business->account_number)Account Number: <strong style="color: #1e293b;">{{ $business->account_number }}</strong><br>@endif
                @if($business->routing_number)Routing/Sort Code: <strong style="color: #1e293b;">{{ $business->routing_number }}</strong><br>@endif
            </p>
        </div>
        @endif

        @if($showNotes && ($invoice->notes || $invoiceFooter))
        <div class="notes-section">
            <div class="notes-title">Notes & Terms</div>
            <p class="notes-content">{{ $invoice->notes }}</p>
            @if($invoiceFooter)
            <p class="notes-content" style="margin-top: 10px; color: #64748b; font-style: italic;">{{ $invoiceFooter }}</p>
            @endif
        </div>
        @endif

        <div class="authenticity-stamp">
            @php 
                $certId = strtoupper(substr(hash('sha256', $invoice->id . $invoice->created_at), 0, 12)); 
            @endphp
            @if($invoice->status === 'paid')
            <!-- Subtle Gold Foil Seal - Paid -->
            <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.85;">
                <!-- Scalloped outer edge -->
                <path d="M60,2 C63,4 67,4 69,2 C72,4 76,3 78,5 C80,7 84,6 86,8 C87,11 91,10 93,13 C94,16 97,16 98,19 C99,22 102,23 103,26 C104,29 106,31 106,34 C107,37 109,40 109,43 C109,46 110,48 109,51 C109,54 110,57 109,60 C110,63 109,66 109,69 C110,72 109,74 109,77 C109,80 107,83 106,86 C106,89 104,91 103,94 C102,97 99,98 98,101 C97,104 94,104 93,107 C91,110 87,109 86,112 C84,114 80,113 78,115 C76,117 72,116 69,118 C67,116 63,116 60,118 C57,116 53,116 51,118 C48,116 44,117 42,115 C40,113 36,114 34,112 C33,109 29,110 27,107 C26,104 23,104 22,101 C21,98 18,97 17,94 C16,91 14,89 14,86 C13,83 11,80 11,77 C11,74 10,72 11,69 C10,66 11,63 11,60 C11,57 10,54 11,51 C10,48 11,46 11,43 C11,40 13,37 14,34 C14,31 16,29 17,26 C18,23 21,22 22,19 C23,16 26,16 27,13 C29,10 33,11 34,8 C36,6 40,7 42,5 C44,3 48,4 51,2 C53,4 57,4 60,2 Z" fill="#FDFBF7" stroke="#D4AF37" stroke-width="1"/>
                
                <!-- Inner concentric rings -->
                <circle cx="60" cy="60" r="48" fill="none" stroke="#D4AF37" stroke-width="0.75"/>
                <circle cx="60" cy="60" r="45" fill="none" stroke="#D4AF37" stroke-width="0.5" stroke-dasharray="2 2"/>
                <circle cx="60" cy="60" r="32" fill="none" stroke="#D4AF37" stroke-width="0.75"/>
                
                <!-- Circular Text path -->
                <path id="curve-paid-gold" d="M 22 60 A 38 38 0 1 1 98 60 A 38 38 0 1 1 22 60" fill="transparent"/>
                <text font-size="8" fill="#B8860B" font-weight="bold" letter-spacing="2" font-family="sans-serif">
                    <textPath href="#curve-paid-gold" startOffset="50%" text-anchor="middle">OFFICIALLY VERIFIED • SECURE</textPath>
                </text>

                <!-- Center piece -->
                <text x="60" y="55" text-anchor="middle" fill="#D4AF37" font-size="16" font-weight="900" font-family="serif" letter-spacing="2">PAID</text>
                <line x1="38" y1="60" x2="82" y2="60" stroke="#D4AF37" stroke-width="0.75"/>
                <text x="60" y="69" text-anchor="middle" fill="#B8860B" font-size="6" font-weight="bold" font-family="sans-serif" letter-spacing="1">AUTHENTIC</text>
                <text x="60" y="77" text-anchor="middle" fill="#B8860B" font-size="5" font-family="monospace">ID: {{ $certId }}</text>
                
                <!-- Subtle star accents -->
                <polygon points="60,23 61,26 64,26 62,28 63,31 60,29 57,31 58,28 56,26 59,26" fill="#D4AF37"/>
            </svg>
            @else
            <!-- Subtle Gold Foil Seal - Authentic -->
            <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.75;">
                <!-- Scalloped outer edge -->
                <path d="M60,2 C63,4 67,4 69,2 C72,4 76,3 78,5 C80,7 84,6 86,8 C87,11 91,10 93,13 C94,16 97,16 98,19 C99,22 102,23 103,26 C104,29 106,31 106,34 C107,37 109,40 109,43 C109,46 110,48 109,51 C109,54 110,57 109,60 C110,63 109,66 109,69 C110,72 109,74 109,77 C109,80 107,83 106,86 C106,89 104,91 103,94 C102,97 99,98 98,101 C97,104 94,104 93,107 C91,110 87,109 86,112 C84,114 80,113 78,115 C76,117 72,116 69,118 C67,116 63,116 60,118 C57,116 53,116 51,118 C48,116 44,117 42,115 C40,113 36,114 34,112 C33,109 29,110 27,107 C26,104 23,104 22,101 C21,98 18,97 17,94 C16,91 14,89 14,86 C13,83 11,80 11,77 C11,74 10,72 11,69 C10,66 11,63 11,60 C11,57 10,54 11,51 C10,48 11,46 11,43 C11,40 13,37 14,34 C14,31 16,29 17,26 C18,23 21,22 22,19 C23,16 26,16 27,13 C29,10 33,11 34,8 C36,6 40,7 42,5 C44,3 48,4 51,2 C53,4 57,4 60,2 Z" fill="none" stroke="#D4AF37" stroke-width="1.2"/>
                
                <!-- Inner concentric rings -->
                <circle cx="60" cy="60" r="48" fill="none" stroke="#D4AF37" stroke-width="0.5"/>
                <circle cx="60" cy="60" r="32" fill="none" stroke="#D4AF37" stroke-width="0.5" stroke-dasharray="3 3"/>
                
                <!-- Circular Text path -->
                <path id="curve-auth-gold" d="M 22 60 A 38 38 0 1 1 98 60 A 38 38 0 1 1 22 60" fill="transparent"/>
                <text font-size="7.5" fill="#B8860B" font-weight="normal" letter-spacing="1.5" font-family="sans-serif">
                    <textPath href="#curve-auth-gold" startOffset="50%" text-anchor="middle">CERTIFIED AUTHENTIC DOCUMENT</textPath>
                </text>

                <!-- Center piece -->
                <text x="60" y="52" text-anchor="middle" fill="#D4AF37" font-size="12" font-weight="bold" font-family="serif" letter-spacing="3">ORIGINAL</text>
                <line x1="40" y1="58" x2="80" y2="58" stroke="#D4AF37" stroke-width="0.5"/>
                <text x="60" y="67" text-anchor="middle" fill="#B8860B" font-size="6" font-family="sans-serif">ISSUED: {{ $invoice->issue_date->format('Y-m-d') }}</text>
                <text x="60" y="75" text-anchor="middle" fill="#B8860B" font-size="5" font-family="monospace">CERT: {{ $certId }}</text>
                
                <polygon points="60,24 61,27 64,27 62,29 63,32 60,30 57,32 58,29 56,27 59,27" fill="#D4AF37" opacity="0.5"/>
            </svg>
            @endif
        </div>
    </div>
</body>
</html>
