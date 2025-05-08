<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'client_id',
        'invoice_date',
        'total_excl_btw',
        'btw_percentage',
        'btw_amount',
        'total_incl_btw',
        'payment_due_days',
        'pdf_path',
        'status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'total_excl_btw' => 'decimal:2',
        'btw_percentage' => 'decimal:2',
        'btw_amount' => 'decimal:2',
        'total_incl_btw' => 'decimal:2',
    ];

    /**
     * Get the client that owns the invoice
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the invoice lines for this invoice
     */
    public function invoiceLines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }

    /**
     * Calculate the due date based on invoice date and payment days
     */
    public function getDueDateAttribute()
    {
        return $this->invoice_date->addDays($this->payment_due_days);
    }

    /**
     * Check if the invoice is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'paid' && now()->gt($this->due_date);
    }
}
