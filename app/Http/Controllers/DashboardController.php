<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Daily operations dashboard.
     * Shows all active activities with their latest status for the selected date.
     * Requirement 4: handover view — all personnel updates visible per activity per day.
     */
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $carbonDate = Carbon::parse($date);

        $activities = Activity::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->with(['logs' => function ($q) use ($date) {
                $q->with('updater')
                  ->where('log_date', $date)
                  ->oldest('updated_at_time');
            }])
            ->get();

        // Group by category for dashboard layout
        $grouped = $activities->groupBy('category');

        // Summary counts for today
        $todaysLogs = ActivityLog::forDate($date)->get();
        $stats = [
            'total'       => $activities->count(),
            'done'        => $todaysLogs->where('status', 'done')->unique('activity_id')->count(),
            'pending'     => $activities->count() - $todaysLogs->where('status', 'done')->unique('activity_id')->count(),
            'escalated'   => $todaysLogs->where('status', 'escalated')->unique('activity_id')->count(),
            'updates'     => $todaysLogs->count(),
            'personnel'   => $todaysLogs->unique('updated_by')->count(),
        ];

        return view('dashboard', compact('grouped', 'date', 'carbonDate', 'stats'));
    }

    /**
     * Quick stats for homepage cards via AJAX.
     */
    public function stats(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $logs = ActivityLog::forDate($date)->get();

        return response()->json([
            'done'      => $logs->where('status', 'done')->unique('activity_id')->count(),
            'pending'   => $logs->where('status', 'pending')->count(),
            'escalated' => $logs->where('status', 'escalated')->count(),
        ]);
    }
}
