<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftSeal extends Model
{
    protected $table = 'shift_seals';

    protected $fillable = [
        'sealed_date',
        'shift',
        'sealed_by',
        'pdf_path',
        'summary',
        'total_activities',
        'completed_activities',
        'pending_activities',
    ];

    protected function casts(): array
    {
        return [
            'sealed_date' => 'date',
            'total_activities' => 'integer',
            'completed_activities' => 'integer',
            'pending_activities' => 'integer',
        ];
    }

    public function sealer()
    {
        return $this->belongsTo(User::class, 'sealed_by');
    }

    public function getLogs()
    {
        return ActivityLog::where('log_date', $this->sealed_date)
            ->where('shift', $this->shift)
            ->get();
    }
}
