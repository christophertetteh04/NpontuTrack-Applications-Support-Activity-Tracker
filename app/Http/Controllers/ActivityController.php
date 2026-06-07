<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function __construct()
    {
        // Authorize using policies defined in ActivityPolicy
        $this->middleware('auth');
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
        $this->authorize('create', Activity::class);
        return view('activities.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Activity::class);
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
        $this->authorize('update', $activity);
        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $this->authorize('update', $activity);
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
        $this->authorize('delete', $activity);
        $activity->delete(); // This will trigger soft delete now
        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully.');
    }
}
