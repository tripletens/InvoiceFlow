<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Invoice from {{ config('app.name') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #0f172a; line-height: 1.5; padding: 20px; }
        .container { max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 p-8; max-width: 600px; margin: 0 auto; }
        h1 { font-size: 24px; font-weight: 800; color: #0f172a; margin-top: 0; }
        p { color: #475569; }
        .details { background-color: #f1f5f9; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .details-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .label { font-weight: 600; color: #64748b; }
        .value { font-weight: 700; color: #0f172a; }
        .total-row { display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 2px solid #cbd5e1; font-size: 18px; font-weight: 800; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #0ea5e9; color: #ffffff; text-decoration: none; font-weight: bold; border-radius: 8px; margin-top: 20px; text-align: center; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <h1>You have a new invoice!</h1>
        <p>Hi {{ $invoice->client->name }},</p>
        <p>A new invoice has been generated for you by <strong>{{ $invoice->user->name ?? config('app.name') }}</strong>.</p>
        
        <div class="details">
            <div class="details-row">
                <span class="label">Invoice Number:</span>
                <span class="value">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="details-row">
                <span class="label">Issue Date:</span>
                <span class="value">{{ $invoice->issue_date->format('M d, Y') }}</span>
            </div>
            <div class="details-row">
                <span class="label">Due Date:</span>
                <span class="value" style="color: #ef4444;">{{ $invoice->due_date->format('M d, Y') }}</span>
            </div>
            
            <div class="total-row">
                <span>Total Due:</span>
                <span>{{ \App\Helpers\CurrencyHelper::format($invoice->total, $invoice->currency) }}</span>
            </div>
        </div>

        <p>Please click the button below to view and pay your invoice online securely.</p>
        
        <!-- Normally this would link to a public payment page. For now, it links home. -->
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}" class="btn">View & Pay Invoice</a>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
