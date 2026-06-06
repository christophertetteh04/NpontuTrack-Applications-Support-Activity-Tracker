<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Store a new status update for an activity.
     * Requirement 2: update status + remark
     * Requirement 3: capture personnel bio + timestamp automatically
     */
    public function store(Request $request, Activity $activity)
    {
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

        ActivityLog::create([
            'activity_id'    => $activity->id,
            'updated_by'     => Auth::id(),          // Req 3: who updated
            'log_date'       => $logDate,
            'status'         => $data['status'],
            'remark'         => $data['remark'] ?? null,
            'expected_value' => $data['expected_value'] ?? null,
            'actual_value'   => $data['actual_value'] ?? null,
            'variance'       => $data['variance'] ?? null,
            'shift'          => $data['shift'] ?? null,
            'updated_at_time'=> Carbon::now(),        // Req 3: exact time
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Activity updated.']);
        }

        return redirect()->back()->with('success', "Activity '{$activity->title}' updated.");
    }

    /**
     * Show full update history for a single activity on a given date.
     * Requirement 4: all updates visible for handover.
     */
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
                    'updated_at_time'=> $l->updated_at_time->format('H:i:s'),
                    'updater_name'   => $l->updater->name,
                    'updater_id'     => $l->updater->employee_id,
                    'updater_role'   => $l->updater->role,
                ]),
            ]);
        }

        return view('activities.history', compact('activity', 'logs', 'date'));
    }
}
