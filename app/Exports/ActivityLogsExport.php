<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivityLogsExport implements FromArray, WithHeadings
{
    protected $rows;

    public function __construct($logs)
    {
        $this->rows = $logs->map(function ($log) {
            return [
                $log->log_date->format('Y-m-d'),
                $log->activity->title,
                $log->activity->category ?? '',
                $log->status_label,
                $log->remark,
                $log->expected_value,
                $log->actual_value,
                $log->variance,
                $log->shift,
                $log->updater->name,
                $log->updater->employee_id,
                $log->updated_at_time->format('H:i:s'),
            ];
        })->toArray();
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return ['Date','Activity','Category','Status','Remark','Expected','Actual','Variance','Shift','Updated By','Employee ID','Time'];
    }
}
