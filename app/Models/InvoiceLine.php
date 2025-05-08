<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'project_id',
        'description',
        'hours',
        'rate',
        'line_total',
    ];

    protected $casts = [
        'hours' => 'decimal:2',
        'rate' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    /**
     * Get the invoice that owns the line
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the project that this line relates to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
