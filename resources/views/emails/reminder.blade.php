<div style="font-family: sans-serif; max-w-md: 600px; margin: 0 auto; padding: 20px;">
    <h2>Invoice Reminder</h2>
    <p>Dear {{ $invoice->client->name }},</p>
    
    <p>This is a reminder regarding Invoice <strong>{{ $invoice->invoice_number }}</strong>.</p>
    
    <div style="background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <p style="margin: 0;"><strong>Amount Due:</strong> ${{ number_format($invoice->total_amount, 2) }}</p>
        <p style="margin: 5px 0 0 0;"><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
    </div>
    
    @if(\Carbon\Carbon::now()->startOfDay()->greaterThan($invoice->due_date))
        <p style="color: #ef4444; font-weight: bold;">This invoice is currently overdue.</p>
    @else
        <p>Please ensure payment is made by the due date to avoid any late fees.</p>
    @endif
    
    <p>You can view and pay your invoice securely by clicking the link below:</p>
    
    <a href="{{ url('/portal/invoice/' . $invoice->token) }}" style="display: inline-block; padding: 10px 20px; background-color: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;">
        View Invoice
    </a>
    
    <p style="margin-top: 30px; font-size: 12px; color: #64748b;">
        If you have already made this payment, please disregard this email.
    </p>
</div>
