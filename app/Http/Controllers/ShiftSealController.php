<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\ShiftSeal;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftSealController extends Controller
{
    public function __construct()
    {
        // Only team leads and admins can seal handovers
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->isTeamLead()) {
                abort(403, 'Unauthorized to seal handovers.');
            }
            return $next($request);
        });
    }

    /**
     * Display the shift seal form
     */
    public function create(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $shift = $request->input('shift', 'morning');

        // Get all activities for this shift/date that need to be sealed
        $logs = ActivityLog::with(['activity', 'updater'])
            ->where('log_date', $date)
            ->where('shift', $shift)
            ->oldest('updated_at_time')
            ->get();

        $summary = [
            'total' => $logs->count(),
            'done' => $logs->where('status', 'done')->count(),
            'pending' => $logs->where('status', 'pending')->count(),
            'in_progress' => $logs->where('status', 'in_progress')->count(),
            'escalated' => $logs->where('status', 'escalated')->count(),
        ];

        // Check if already sealed
        $existingSeal = ShiftSeal::where('sealed_date', $date)
            ->where('shift', $shift)
            ->first();

        return view('handover.seal', compact('date', 'shift', 'logs', 'summary', 'existingSeal'));
    }

    /**
     * Seal the handover and generate PDF
     */
    public function seal(Request $request)
    {
        $data = $request->validate([
            'sealed_date' => 'required|date',
            'shift' => 'required|in:morning,afternoon,night',
        ]);

        // Check if already sealed
        $existingSeal = ShiftSeal::where('sealed_date', $data['sealed_date'])
            ->where('shift', $data['shift'])
            ->first();

        if ($existingSeal) {
            return back()->with('warning', 'This shift was already sealed on ' . $existingSeal->created_at->format('Y-m-d H:i'));
        }

        // Get all logs for this shift
        $logs = ActivityLog::with(['activity', 'updater'])
            ->where('log_date', $data['sealed_date'])
            ->where('shift', $data['shift'])
            ->oldest('updated_at_time')
            ->get();

        $summary = [
            'total' => $logs->count(),
            'done' => $logs->where('status', 'done')->count(),
            'pending' => $logs->where('status', 'pending')->count(),
            'in_progress' => $logs->where('status', 'in_progress')->count(),
            'escalated' => $logs->where('status', 'escalated')->count(),
        ];

        // Generate PDF
        try {
            $pdf = Pdf::loadView('reports.shift-seal-pdf', [
                'date' => $data['sealed_date'],
                'shift' => $data['shift'],
                'logs' => $logs,
                'summary' => $summary,
                'sealer' => Auth::user(),
            ]);

            $filename = 'shift-seal-' . $data['sealed_date'] . '-' . $data['shift'] . '.pdf';
            $pdfPath = 'shift-seals/' . $filename;

            // Store PDF in storage
            \Illuminate\Support\Facades\Storage::disk('local')->put(
                'public/' . $pdfPath,
                $pdf->output()
            );

            // Create seal record
            $seal = ShiftSeal::create([
                'sealed_date' => $data['sealed_date'],
                'shift' => $data['shift'],
                'sealed_by' => Auth::id(),
                'pdf_path' => $pdfPath,
                'summary' => json_encode($summary),
                'total_activities' => $summary['total'],
                'completed_activities' => $summary['done'],
                'pending_activities' => $summary['pending'],
            ]);

            // Mark all logs as sealed (add is_sealed flag to ActivityLog if needed)
            // For now, the existence of a ShiftSeal record serves as the seal

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Shift sealed successfully',
                    'pdf_url' => asset('storage/' . $pdfPath),
                    'seal_id' => $seal->id,
                ]);
            }

            return redirect()
                ->route('handover.index')
                ->with('success', 'Shift sealed successfully')
                ->with('pdf_download', asset('storage/' . $pdfPath));

        } catch (\Exception $e) {
            \Log::error('Shift seal failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Failed to seal shift: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Failed to seal shift. Please try again.');
        }
    }

    /**
     * Download seal PDF
     */
    public function download(ShiftSeal $seal)
    {
        // Verify user has permission
        if (!Auth::user()->isTeamLead()) {
            abort(403);
        }

        if (!$seal->pdf_path || !\Illuminate\Support\Facades\Storage::disk('local')->exists('public/' . $seal->pdf_path)) {
            abort(404, 'PDF not found');
        }

        return \Illuminate\Support\Facades\Storage::disk('local')
            ->download('public/' . $seal->pdf_path);
    }
}
