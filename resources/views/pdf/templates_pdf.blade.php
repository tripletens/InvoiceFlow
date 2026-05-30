@if($templateStyle === 'modern')
    <div class="header-modern" style="border-bottom: 3px solid {{ $primaryColor }}; padding-bottom: 20px; margin-bottom: 40px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="font-size: 24px; font-weight: bold; color: #0f172a;">
                    {{ $business?->name ?? 'InvoiceFlow' }}
                    @if($showTagline && $tagline)<div style="font-size: 12px; font-weight: normal; color: #64748b; margin-top: 4px; font-style: italic;">{{ $tagline }}</div>@endif
                </td>
                <td style="text-align: right; font-size: 24px; color: #475569; font-weight: bold;">INVOICE</td>
            </tr>
        </table>
    </div>
@elseif($templateStyle === 'classic')
    <div style="text-align: center; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 40px;">
        <div style="font-size: 26px; font-weight: bold; color: {{ $primaryColor }}; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
        @if($showTagline && $tagline)<div style="font-size: 13px; color: #64748b; font-style: italic; margin-bottom: 10px;">{{ $tagline }}</div>@endif
        <div style="font-size: 12px; color: #475569;">{{ $business?->address ?? '' }}</div>
    </div>
@elseif($templateStyle === 'minimalist')
    <div style="width: 100%; margin-bottom: 30px; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="font-size: 18px; font-weight: bold; color: #1e293b;">
                    {{ $business?->name ?? 'InvoiceFlow' }}
                    @if($showTagline && $tagline)<div style="font-size: 11px; font-weight: normal; color: #64748b; margin-top: 2px;">{{ $tagline }}</div>@endif
                </td>
                <td style="text-align: right; font-size: 14px; font-weight: bold; color: {{ $primaryColor }}; text-transform: uppercase;">Invoice</td>
            </tr>
        </table>
    </div>
@elseif($templateStyle === 'corporate')
    <div style="background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td>
                    <div style="font-size: 22px; font-weight: bold; text-transform: uppercase; color: #1e293b;">INVOICE</div>
                    <div style="font-size: 12px; color: #64748b; font-weight: bold;">#{{ $invoice->invoice_number }}</div>
                </td>
                <td style="text-align: right; border-left: 4px solid {{ $primaryColor }}; padding-left: 15px;">
                    <div style="font-size: 20px; font-weight: bold; color: #0f172a;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
                    @if($showTagline && $tagline)<div style="font-size: 12px; color: #64748b; font-style: italic;">{{ $tagline }}</div>@endif
                </td>
            </tr>
        </table>
    </div>
@elseif($templateStyle === 'bold')
    <div style="border-bottom: 8px solid {{ $primaryColor }}; padding-bottom: 20px; margin-bottom: 30px;">
        <div style="font-size: 40px; font-weight: 900; text-transform: uppercase; color: #0f172a; line-height: 1;">INVOICE</div>
        <table style="width: 100%; margin-top: 15px;">
            <tr>
                <td>
                    <div style="font-size: 20px; font-weight: bold; color: {{ $primaryColor }};">{{ $business?->name ?? 'InvoiceFlow' }}</div>
                    @if($showTagline && $tagline)<div style="font-size: 12px; color: #64748b;">{{ $tagline }}</div>@endif
                </td>
                <td style="text-align: right; font-size: 14px; font-weight: bold; color: #64748b;">#{{ $invoice->invoice_number }}</td>
            </tr>
        </table>
    </div>
@elseif($templateStyle === 'elegant')
    <div style="text-align: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 30px; margin-bottom: 30px; position: relative;">
        <div style="background: {{ $primaryColor }}; height: 2px; width: 100%; position: absolute; top: 0; left: 0;"></div>
        <div style="font-size: 24px; font-weight: 500; color: #1e293b; letter-spacing: 2px; text-transform: uppercase; margin-top: 20px;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
        @if($showTagline && $tagline)<div style="font-size: 12px; color: #64748b; font-style: italic; margin-top: 5px;">{{ $tagline }}</div>@endif
        <div style="font-size: 14px; text-transform: uppercase; letter-spacing: 4px; color: {{ $primaryColor }}; margin-top: 25px;">Invoice</div>
    </div>
@elseif($templateStyle === 'tech')
    <div style="background: #f1f5f9; padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td>
                    <div style="font-size: 22px; font-weight: bold; color: #1e293b;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
                    @if($showTagline && $tagline)<div style="font-size: 10px; font-weight: bold; color: #64748b; text-transform: uppercase;">{{ $tagline }}</div>@endif
                </td>
                <td style="text-align: right; background: #fff; padding: 10px; border-radius: 8px;">
                    <div style="font-size: 16px; font-weight: 900; color: {{ $primaryColor }}; text-transform: uppercase;">Invoice</div>
                    <div style="font-size: 11px; font-weight: bold; color: #94a3b8;">#{{ $invoice->invoice_number }}</div>
                </td>
            </tr>
        </table>
    </div>
@elseif($templateStyle === 'studio')
    <table style="width: 100%; margin-bottom: 40px; border-collapse: collapse;">
        <tr>
            <td style="width: 33%; background: {{ $primaryColor }}; color: #fff; padding: 25px;">
                <div style="font-size: 22px; font-weight: bold;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
                @if($showTagline && $tagline)<div style="font-size: 12px; color: #f8fafc; margin-top: 10px;">{{ $tagline }}</div>@endif
            </td>
            <td style="width: 67%; background: #f8fafc; padding: 25px; text-align: right; border: 1px solid #e2e8f0; border-left: none;">
                <div style="font-size: 32px; font-weight: 900; color: #1e293b; text-transform: uppercase;">INVOICE</div>
                <div style="font-size: 12px; font-weight: bold; color: #94a3b8; margin-top: 5px;">#{{ $invoice->invoice_number }}</div>
            </td>
        </tr>
    </table>
@elseif($templateStyle === 'monospace')
    <div style="border: 2px solid #0f172a; padding: 20px; margin-bottom: 30px; background: #f8fafc;">
        <table style="width: 100%; border-bottom: 2px solid #0f172a; padding-bottom: 10px; margin-bottom: 10px;">
            <tr>
                <td style="font-weight: bold; font-size: 16px;">> {{ strtoupper($business?->name ?? 'InvoiceFlow') }}</td>
                <td style="font-weight: bold; font-size: 16px; text-align: right;">> INVOICE</td>
            </tr>
        </table>
        @if($showTagline && $tagline)<div style="font-size: 12px; color: #475569; margin-bottom: 10px;">/* {{ $tagline }} */</div>@endif
        <div style="font-size: 12px;">ID: {{ $invoice->invoice_number }}</div>
    </div>
@elseif($templateStyle === 'geometric')
    <div style="padding: 25px; margin-bottom: 30px; border: 1px solid #e2e8f0; background: #fff; position: relative;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td>
                    <div style="font-size: 26px; font-weight: 900; color: #1e293b;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
                    @if($showTagline && $tagline)<div style="font-size: 12px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-top: 5px;">{{ $tagline }}</div>@endif
                </td>
                <td style="text-align: right;">
                    <div style="font-size: 20px; font-weight: bold; color: {{ $primaryColor }};">INVOICE</div>
                </td>
            </tr>
        </table>
    </div>
@elseif($templateStyle === 'agency')
    <table style="width: 100%; margin-bottom: 30px; border-collapse: collapse; height: 100px;">
        <tr>
            <td style="width: 15px; background: {{ $primaryColor }};"></td>
            <td style="width: 5px; background: #fff;"></td>
            <td style="width: 15px; background: {{ $accentColor }};"></td>
            <td style="background: #f1f5f9; padding: 0 25px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td>
                            <div style="font-size: 24px; font-weight: 900; color: #0f172a;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
                            @if($showTagline && $tagline)<div style="font-size: 12px; font-weight: 500; color: #475569; margin-top: 5px;">{{ $tagline }}</div>@endif
                        </td>
                        <td style="text-align: right;">
                            <div style="font-size: 22px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; color: #94a3b8;">Invoice</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@elseif($templateStyle === 'vintage')
    <div style="padding: 4px; margin-bottom: 30px; border: 4px solid {{ $primaryColor }};">
        <div style="border: 1px solid #cbd5e1; padding: 25px; text-align: center; background: #fffbeb;">
            <div style="font-size: 32px; font-weight: normal; color: #1e293b;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
            @if($showTagline && $tagline)<div style="font-size: 13px; font-style: italic; color: #475569; margin: 10px 0;">~ {{ $tagline }} ~</div>@endif
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #cbd5e1;">
                <span style="font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; color: #64748b; margin-right: 20px;">Invoice</span>
                <span style="font-size: 12px; color: #94a3b8;">No. {{ $invoice->invoice_number }}</span>
            </div>
        </div>
    </div>
@elseif($templateStyle === 'high_contrast')
    <div style="background: #000; color: #fff; padding: 25px; margin-bottom: 30px;">
        <table style="width: 100%; border-bottom: 1px solid #333; padding-bottom: 15px;">
            <tr>
                <td style="font-size: 30px; font-weight: 900; text-transform: uppercase;">Invoice</td>
                <td style="text-align: right;">
                    <div style="font-size: 22px; font-weight: bold;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
                    @if($showTagline && $tagline)<div style="font-size: 10px; color: #aaa; margin-top: 5px; text-transform: uppercase;">{{ $tagline }}</div>@endif
                </td>
            </tr>
        </table>
        <div style="padding-top: 15px; font-size: 12px; color: #888;">ID: {{ $invoice->invoice_number }}</div>
    </div>
@elseif($templateStyle === 'pastel')
    <div style="padding: 25px; margin-bottom: 30px; border-radius: 20px; text-align: center; background: #f8fafc; border: 2px solid {{ $primaryColor }}30;">
        <div style="font-size: 26px; font-weight: 500; color: #334155;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
        @if($showTagline && $tagline)<div style="font-size: 13px; color: #64748b; margin-top: 5px;">{{ $tagline }}</div>@endif
        <div style="margin-top: 15px; display: inline-block; padding: 5px 15px; border-radius: 20px; background: #fff; font-size: 12px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; color: {{ $primaryColor }}; border: 1px solid #e2e8f0;">Invoice</div>
    </div>
@elseif($templateStyle === 'brutalist')
    <div style="border: 4px solid #000; padding: 20px; margin-bottom: 30px; background: #fefce8; box-shadow: 5px 5px 0px #000;">
        <table style="width: 100%; border-bottom: 4px solid #000; padding-bottom: 10px; margin-bottom: 10px;">
            <tr>
                <td style="font-size: 28px; font-weight: 900; text-transform: uppercase; color: #000;">{{ $business?->name ?? 'InvoiceFlow' }}</td>
                <td style="text-align: right;"><span style="font-size: 26px; font-weight: 900; text-transform: uppercase; background: #000; color: #fefce8; padding: 0 10px;">Invoice</span></td>
            </tr>
        </table>
        @if($showTagline && $tagline)<div style="font-size: 14px; font-weight: bold; text-transform: uppercase; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px;">{{ $tagline }}</div>@endif
        <div style="font-size: 12px; font-weight: 900; text-transform: uppercase;">REF: {{ $invoice->invoice_number }}</div>
    </div>
@elseif($templateStyle === 'compact')
    <table style="width: 100%; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 20px;">
        <tr>
            <td>
                <span style="font-size: 16px; font-weight: bold; color: #1e293b;">{{ $business?->name ?? 'InvoiceFlow' }}</span>
                @if($showTagline && $tagline)<span style="font-size: 11px; color: #94a3b8; border-left: 1px solid #e2e8f0; padding-left: 10px; margin-left: 10px;">{{ $tagline }}</span>@endif
            </td>
            <td style="text-align: right;">
                <span style="font-size: 11px; color: #94a3b8; font-family: monospace; margin-right: 15px;">{{ $invoice->invoice_number }}</span>
                <span style="font-size: 12px; font-weight: bold; text-transform: uppercase; background: #f1f5f9; padding: 3px 8px; border-radius: 4px; color: #475569;">Invoice</span>
            </td>
        </tr>
    </table>
@elseif($templateStyle === 'neon')
    <div style="background: #020617; padding: 25px; margin-bottom: 30px; border-radius: 12px; border: 1px solid #1e293b;">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div style="font-size: 26px; font-weight: 900; letter-spacing: 1px; color: {{ $primaryColor }};">{{ strtoupper($business?->name ?? 'InvoiceFlow') }}</div>
                    @if($showTagline && $tagline)<div style="font-size: 10px; color: #94a3b8; margin-top: 5px; text-transform: uppercase; letter-spacing: 2px;">{{ $tagline }}</div>@endif
                </td>
                <td style="text-align: right;">
                    <div style="font-size: 16px; font-weight: bold; color: #fff; text-transform: uppercase; letter-spacing: 2px;">Invoice</div>
                    <div style="font-size: 13px; color: {{ $accentColor }}; margin-top: 5px;">#{{ $invoice->invoice_number }}</div>
                </td>
            </tr>
        </table>
    </div>
@elseif($templateStyle === 'newspaper')
    <div style="border-bottom: 4px double #1e293b; padding-bottom: 20px; margin-bottom: 30px; text-align: center;">
        <div style="font-size: 40px; font-weight: 900; color: #0f172a; text-transform: uppercase;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
        @if($showTagline && $tagline)<div style="font-size: 13px; font-style: italic; color: #475569; border-top: 1px solid #cbd5e1; border-bottom: 1px solid #cbd5e1; padding: 5px 0; margin: 10px 0;">{{ $tagline }}</div>@endif
        <table style="width: 100%; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; margin-top: 10px;">
            <tr>
                <td style="text-align: left;">Vol. 1</td>
                <td style="text-align: center;">Invoice Statement</td>
                <td style="text-align: right;">No. {{ $invoice->invoice_number }}</td>
            </tr>
        </table>
    </div>
@elseif($templateStyle === 'retail')
    <div style="width: 300px; margin: 0 auto 30px auto; border: 1px dashed #cbd5e1; padding: 20px; text-align: center; background: #f8fafc; font-family: monospace;">
        <div style="font-size: 18px; font-weight: bold; color: #0f172a; text-transform: uppercase;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
        @if($showTagline && $tagline)<div style="font-size: 11px; color: #64748b; margin-top: 5px;">{{ $tagline }}</div>@endif
        <div style="margin: 10px 0; border-bottom: 1px dashed #cbd5e1;"></div>
        <div style="font-weight: bold; color: #334155;">INVOICE RECEIPT</div>
        <div style="font-size: 11px; color: #64748b; margin-top: 5px;">#{{ $invoice->invoice_number }}</div>
        <div style="margin: 10px 0; border-bottom: 1px dashed #cbd5e1;"></div>
    </div>
@elseif($templateStyle === 'executive')
    <div style="padding: 30px; margin-bottom: 30px; border: 1px solid #e2e8f0; background: #f8fafc; position: relative;">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div style="font-size: 20px; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 1px;">{{ $business?->name ?? 'InvoiceFlow' }}</div>
                    @if($showTagline && $tagline)<div style="font-size: 12px; color: #64748b; margin-top: 5px;">{{ $tagline }}</div>@endif
                </td>
                <td style="text-align: right;">
                    <div style="font-size: 22px; font-weight: normal; color: #94a3b8; text-transform: uppercase; letter-spacing: 4px;">Invoice</div>
                </td>
            </tr>
        </table>
    </div>
@else
    <div class="header-modern" style="border-bottom: 3px solid {{ $primaryColor }}; padding-bottom: 20px; margin-bottom: 40px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="font-size: 24px; font-weight: bold; color: #0f172a;">
                    {{ $business?->name ?? 'InvoiceFlow' }}
                    @if($showTagline && $tagline)<div style="font-size: 12px; font-weight: normal; color: #64748b; margin-top: 4px; font-style: italic;">{{ $tagline }}</div>@endif
                </td>
                <td style="text-align: right; font-size: 24px; color: #475569; font-weight: bold;">INVOICE</td>
            </tr>
        </table>
    </div>
@endif
