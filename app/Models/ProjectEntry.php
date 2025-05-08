<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'description',
        'hours',
        'rate',
        'entry_date',
    ];

    protected $casts = [
        'hours' => 'decimal:2',
        'rate' => 'decimal:2',
        'entry_date' => 'date',
    ];

    /**
     * Get the project that owns the entry
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Calculate the total amount for this entry
     */
    public function getTotalAttribute(): float
    {
        return $this->hours * $this->rate;
    }
}
