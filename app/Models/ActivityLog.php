<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ActivityLog extends Model
{
    protected $fillable = [
        'activity_id',
        'updated_by',
        'log_date',
        'status',
        'previous_status',
        'remark',
        'expected_value',
        'actual_value',
        'variance',
        'shift',
        'updated_at_time',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'log_date'        => 'date',
            'updated_at_time' => 'datetime',
        ];
    }

    
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    
    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->where('log_date', $date);
    }

    public function scopeForDateRange(Builder $query, string $from, string $to): Builder
    {
        return $query->whereBetween('log_date', [$from, $to]);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('updated_by', $userId);
    }

    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'done'        => 'green',
            'in_progress' => 'blue',
            'escalated'   => 'red',
            default       => 'amber',   
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'done'        => 'Done',
            'in_progress' => 'In Progress',
            'escalated'   => 'Escalated',
            default       => 'Pending',
        };
    }

    public function getChangeTrackingLabelAttribute(): ?string
    {
        if (!$this->previous_status || $this->previous_status === $this->status) {
            return null;
        }

        $oldLabel = match ($this->previous_status) {
            'done'        => 'Done',
            'in_progress' => 'In Progress',
            'escalated'   => 'Escalated',
            default       => 'Pending',
        };

        $newLabel = $this->status_label;

        return "{$this->updater->name} changed status from <strong>{$oldLabel}</strong> to <strong>{$newLabel}</strong>";
    }
}
