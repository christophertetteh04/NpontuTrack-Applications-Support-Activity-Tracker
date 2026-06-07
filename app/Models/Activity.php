<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;
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

    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }

        public function latestLogForDate(string $date)
    {
        return $this->logs()
            ->where('log_date', $date)
            ->latest('updated_at_time')
            ->first();
    }

        public function logsForDate(string $date)
    {
        return $this->logs()
            ->with('updater')
            ->where('log_date', $date)
            ->oldest('updated_at_time')
            ->get();
    }

    
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
