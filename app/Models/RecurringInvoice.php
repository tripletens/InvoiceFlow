<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'client_id', 'frequency', 'status',
        'subtotal', 'tax_rate', 'tax_amount', 'total', 'currency',
        'notes', 'last_generated_at', 'next_generation_date',
    ];

    protected $casts = [
        'last_generated_at' => 'date',
        'next_generation_date' => 'date',
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
        return $this->hasMany(RecurringInvoiceItem::class);
    }
}
