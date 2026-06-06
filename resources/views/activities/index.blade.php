@extends('layouts.app')
@section('title', 'Manage Activities')
@section('page-title', 'Activity Definitions')

@section('page-actions')
    @if(auth()->user()->isTeamLead())
    <a href="{{ route('activities.create') }}"
       class="text-sm bg-brand-600 hover:bg-brand-700 text-white px-4 py-1.5 rounded-lg transition">+ New Activity</a>
    @endif
@endsection

@section('content')
@forelse($activities as $category => $acts)
<div class="mb-6">
    <h3 class="text-xs font-mono font-semibold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
        <span class="w-2 h-2 bg-brand-500 rounded-full"></span> {{ $category }}
    </h3>
    <div class="bg-white rounded-xl border border-gray-100 divide-y divide-gray-50 overflow-hidden">
        @foreach($acts as $activity)
        <div class="flex items-center gap-4 px-5 py-3">
            <div class="flex-1">
                <p class="font-medium text-sm text-gray-900">{{ $activity->title }}</p>
                @if($activity->description)
                <p class="text-xs text-gray-400 mt-0.5">{{ $activity->description }}</p>
                @endif
            </div>
            <span class="text-xs font-mono px-2 py-0.5 rounded-full {{ $activity->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
                {{ $activity->is_active ? 'Active' : 'Inactive' }}
            </span>
            <span class="text-xs text-gray-400">by {{ $activity->creator->name }}</span>
            @if(auth()->user()->isTeamLead())
            <a href="{{ route('activities.edit', $activity) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
            <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Deactivate this activity?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-400 hover:text-red-600">Deactivate</button>
            </form>
            @endif
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="text-center py-16 text-gray-400">
    <p>No activities yet.</p>
    @if(auth()->user()->isTeamLead())
    <a href="{{ route('activities.create') }}" class="mt-2 inline-block text-sm text-blue-600 hover:underline">Create your first activity →</a>
    @endif
</div>
@endforelse
@endsection
