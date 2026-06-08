<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class ActivityLogController extends Controller
{
    public function store(Request $request, Activity $activity)
    {
        $limiter = RateLimiter::attempt(
            'activity-log-' . Auth::id(),
            10,
            function () {
                return true;
            },
            60
        );

        if (!$limiter) {
            if ($request->expectsJson()) {
                return response()->json(
                    ['error' => 'Too many updates. Please try again later.'],
                    429
                );
            }
            return back()->with('error', 'Too many updates. Please try again later.');
        }

        $data = $request->validate([
            'status'         => 'required|in:pending,in_progress,done,escalated',
            'remark'         => 'nullable|string|max:1000',
            'expected_value' => 'nullable|string|max:100',
            'actual_value'   => 'nullable|string|max:100',
            'variance'       => 'nullable|string|max:100',
            'shift'          => 'nullable|in:morning,afternoon,night',
            'log_date'       => 'nullable|date',
        ]);

        $logDate = $data['log_date'] ?? Carbon::today()->toDateString();

        // Automatically calculate variance if values are numeric
        $variance = $data['variance'] ?? null;
        if (is_numeric($data['expected_value']) && is_numeric($data['actual_value'])) {
            $diff = (float)$data['actual_value'] - (float)$data['expected_value'];
            $variance = ($diff >= 0 ? '+' : '') . $diff;
        }

        $previousStatus = ActivityLog::where('activity_id', $activity->id)
            ->where('log_date', $logDate)
            ->latest('updated_at_time')
            ->first()
            ?->status;

        ActivityLog::create([
            'activity_id'    => $activity->id,
            'updated_by'     => Auth::id(),
            'log_date'       => $logDate,
            'status'         => $data['status'],
            'previous_status'=> $previousStatus,
            'remark'         => $data['remark'] ?? null,
            'expected_value' => $data['expected_value'] ?? null,
            'actual_value'   => $data['actual_value'] ?? null,
            'variance'       => $variance,
            'shift'          => $data['shift'] ?? null,
            'updated_at_time'=> Carbon::now(),
            'ip_address'     => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Activity updated.']);
        }

        return redirect()->back()->with('success', "Activity '{$activity->title}' updated.");
    }

        public function history(Request $request, Activity $activity)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $logs = ActivityLog::with('updater')
            ->where('activity_id', $activity->id)
            ->where('log_date', $date)
            ->oldest('updated_at_time')
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'activity' => $activity,
                'date'     => $date,
                'logs'     => $logs->map(fn($l) => [
                    'id'             => $l->id,
                    'status'         => $l->status,
                    'status_label'   => $l->status_label,
                    'badge_color'    => $l->status_badge_color,
                    'remark'         => $l->remark,
                    'expected_value' => $l->expected_value,
                    'actual_value'   => $l->actual_value,
                    'variance'       => $l->variance,
                    'shift'          => $l->shift,
                    'updated_at_time'=> Carbon::parse($l->updated_at_time)->format('H:i:s'),
                    'updater_name'   => $l->updater->name,
                    'updater_id'     => $l->updater->employee_id,
                    'updater_role'   => $l->updater->role,
                ]),
            ]);
        }

        return view('activities.history', compact('activity', 'logs', 'date'));
    }
}