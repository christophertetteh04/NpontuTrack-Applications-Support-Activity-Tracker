<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityAuditController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->isTeamLead()) {
                abort(403, 'Only Team Leads and Admins can view the audit.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $filters = $request->validate([
            'date' => 'nullable|date',
            'activity_id' => 'nullable|exists:activities,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $date = $filters['date'] ?? Carbon::today()->toDateString();

        $query = ActivityLog::with(['activity', 'updater'])->orderBy('log_date', 'desc')->orderBy('updated_at_time', 'desc');

        if (!empty($filters['activity_id'])) $query->where('activity_id', $filters['activity_id']);
        if (!empty($filters['user_id'])) $query->where('updated_by', $filters['user_id']);
        if (!empty($filters['date'])) $query->where('log_date', $filters['date']);

        $logs = $query->paginate(50)->withQueryString();

        $activities = Activity::active()->orderBy('title')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('audit.index', compact('logs', 'activities', 'users', 'date', 'filters'));
    }
}
