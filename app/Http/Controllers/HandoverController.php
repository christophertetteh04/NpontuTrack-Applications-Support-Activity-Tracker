<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HandoverController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $pending = ActivityLog::with(['activity', 'updater'])
            ->where('log_date', $date)
            ->where('status', '!=', 'done')
            ->orderBy('updated_at_time', 'desc')
            ->get();

        return view('handover.timeline', compact('pending', 'date'));
    }
}
