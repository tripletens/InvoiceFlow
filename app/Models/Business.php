<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'address', 'logo', 'plan',
        'primary_color', 'accent_color', 'tagline', 'invoice_footer',
        'font_family', 'show_tax', 'show_qty', 'show_notes', 'show_tagline', 'template_style',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
