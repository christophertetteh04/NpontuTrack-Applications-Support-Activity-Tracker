<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Reporting view.
     * Requirement 5: query activity histories by custom date ranges,
     * personnel, status, category.
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'date_from'   => 'nullable|date',
            'date_to'     => 'nullable|date|after_or_equal:date_from',
            'activity_id' => 'nullable|exists:activities,id',
            'user_id'     => 'nullable|exists:users,id',
            'status'      => 'nullable|in:pending,in_progress,done,escalated',
            'category'    => 'nullable|string',
            'shift'       => 'nullable|in:morning,afternoon,night',
        ]);

        $dateFrom = $filters['date_from'] ?? Carbon::now()->startOfMonth()->toDateString();
        $dateTo   = $filters['date_to']   ?? Carbon::today()->toDateString();

        $query = ActivityLog::with(['activity', 'updater'])
            ->forDateRange($dateFrom, $dateTo)
            ->orderBy('log_date', 'desc')
            ->orderBy('updated_at_time', 'desc');

        if (!empty($filters['activity_id'])) {
            $query->where('activity_id', $filters['activity_id']);
        }
        if (!empty($filters['user_id'])) {
            $query->forUser($filters['user_id']);
        }
        if (!empty($filters['status'])) {
            $query->withStatus($filters['status']);
        }
        if (!empty($filters['category'])) {
            $query->whereHas('activity', fn($q) => $q->where('category', $filters['category']));
        }
        if (!empty($filters['shift'])) {
            $query->where('shift', $filters['shift']);
        }

        $summaryQuery = clone $query;
        $allLogs = $summaryQuery->get();
        $logs = $query->paginate(50)->withQueryString();

        // Aggregate stats for the report header.
        $summary = [
            'total_updates' => $allLogs->count(),
            'done'          => $allLogs->where('status', 'done')->count(),
            'pending'       => $allLogs->where('status', 'pending')->count(),
            'escalated'     => $allLogs->where('status', 'escalated')->count(),
            'unique_days'   => $allLogs->unique('log_date')->count(),
            'unique_staff'  => $allLogs->unique('updated_by')->count(),
        ];

        $activities = Activity::active()->orderBy('title')->get();
        $users       = User::where('is_active', true)->orderBy('name')->get();
        $categories  = Activity::active()->distinct()->pluck('category');

        return view('reports.index', compact(
            'logs', 'filters', 'dateFrom', 'dateTo',
            'summary', 'activities', 'users', 'categories'
        ));
    }

    /**
     * Export report as CSV.
     */
    public function export(Request $request)
    {
        $filters  = $request->validate([
            'date_from'   => 'nullable|date',
            'date_to'     => 'nullable|date|after_or_equal:date_from',
            'activity_id' => 'nullable|exists:activities,id',
            'user_id'     => 'nullable|exists:users,id',
            'status'      => 'nullable|in:pending,in_progress,done,escalated',
            'category'    => 'nullable|string',
            'shift'       => 'nullable|in:morning,afternoon,night',
        ]);
        $dateFrom = $filters['date_from'] ?? Carbon::now()->startOfMonth()->toDateString();
        $dateTo   = $filters['date_to']   ?? Carbon::today()->toDateString();

        $query = ActivityLog::with(['activity', 'updater'])
            ->forDateRange($dateFrom, $dateTo)
            ->orderBy('log_date', 'desc');

        if (!empty($filters['activity_id'])) $query->where('activity_id', $filters['activity_id']);
        if (!empty($filters['user_id']))     $query->where('updated_by', $filters['user_id']);
        if (!empty($filters['status']))      $query->where('status', $filters['status']);
        if (!empty($filters['category']))    $query->whereHas('activity', fn($q) => $q->where('category', $filters['category']));
        if (!empty($filters['shift']))       $query->where('shift', $filters['shift']);

        $logs = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="activity-report-' . $dateFrom . '-to-' . $dateTo . '.csv"',
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Activity', 'Category', 'Status', 'Remark', 'Expected', 'Actual', 'Variance', 'Shift', 'Updated By', 'Employee ID', 'Time']);
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->log_date->format('Y-m-d'),
                    $log->activity->title,
                    $log->activity->category,
                    $log->status_label,
                    $log->remark,
                    $log->expected_value,
                    $log->actual_value,
                    $log->variance,
                    $log->shift,
                    $log->updater->name,
                    $log->updater->employee_id,
                    $log->updated_at_time->format('H:i:s'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Daily summary view — grouped by activity showing latest status per day.
     */
    public function daily(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $carbonDate = Carbon::parse($date);

        $activities = Activity::active()
            ->with(['logs' => function ($q) use ($date) {
                $q->with('updater')->where('log_date', $date)->oldest('updated_at_time');
            }])
            ->orderBy('category')->orderBy('sort_order')
            ->get();

        return view('reports.daily', compact('activities', 'date', 'carbonDate'));
    }
}
