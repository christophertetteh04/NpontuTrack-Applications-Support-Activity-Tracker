<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Activity extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'sort_order',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Returns the most recent log for a given date.
     */
    public function latestLogForDate(string $date)
    {
        return $this->logs()
            ->where('log_date', $date)
            ->latest('updated_at_time')
            ->first();
    }

    /**
     * Returns ALL logs for a date ordered chronologically (for handover view).
     */
    public function logsForDate(string $date)
    {
        return $this->logs()
            ->with('updater')
            ->where('log_date', $date)
            ->oldest('updated_at_time')
            ->get();
    }

    // ── Scopes ─────────────────────────────────────────────────────
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
