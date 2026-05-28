<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'client_id', 'invoice_number', 'status',
        'subtotal', 'tax_rate', 'tax_amount', 'total', 'currency',
        'notes', 'issue_date', 'due_date', 'sent_at', 'viewed_at', 'paid_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date'   => 'date',
        'sent_at'    => 'datetime',
        'viewed_at'  => 'datetime',
        'paid_at'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /** Scope for status filtering */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
