<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
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

        $grouped = $activities->groupBy('category');

        $todaysLogs = ActivityLog::with(['activity', 'updater'])
            ->forDate($date)
            ->orderBy('updated_at_time', 'desc')
            ->get();

        $latestLogs = $todaysLogs
            ->sortByDesc('updated_at_time')
            ->unique('activity_id')
            ->values();

        $pendingHandover = $latestLogs
            ->filter(fn($log) => $log->status !== 'done')
            ->values();

        $handoverByPerson = $pendingHandover
            ->groupBy('updated_by')
            ->map(fn($logs) => [
                'name'        => $logs->first()->updater->name,
                'employee_id' => $logs->first()->updater->employee_id,
                'role'        => $logs->first()->updater->role,
                'count'       => $logs->count(),
                'latest_time' => $logs->first()->updated_at_time->format('H:i'),
            ])
            ->values();

        $recentUpdates = $todaysLogs->take(6);

        $stats = [
            'total'       => $activities->count(),
            'done'        => $latestLogs->where('status', 'done')->count(),
            'pending'     => $pendingHandover->count(),
            'escalated'   => $latestLogs->where('status', 'escalated')->count(),
            'updates'     => $todaysLogs->count(),
            'personnel'   => $todaysLogs->unique('updated_by')->count(),
            'handover'    => $pendingHandover->count(),
        ];

        return view('dashboard', compact('grouped', 'date', 'carbonDate', 'stats', 'pendingHandover', 'handoverByPerson', 'recentUpdates'));
    }

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