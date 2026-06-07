<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function __construct()
    {
        
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isTeamLead() && in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy'])) {
                abort(403, 'Only Team Leads and Admins can manage activity definitions.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $activities = Activity::with('creator')
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('activities.index', compact('activities'));
    }

    public function create()
    {
        return view('activities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'required|string|max:100',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        Activity::create(array_merge($data, [
            'created_by' => Auth::id(),
            'is_active'  => $request->boolean('is_active', true),
        ]));

        return redirect()->route('activities.index')
            ->with('success', 'Activity created successfully.');
    }

    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'required|string|max:100',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $activity->update(array_merge($data, [
            'is_active' => $request->boolean('is_active'),
        ]));

        return redirect()->route('activities.index')
            ->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity)
    {
        $activity->update(['is_active' => false]);
        return redirect()->route('activities.index')
            ->with('success', 'Activity deactivated.');
    }
}
