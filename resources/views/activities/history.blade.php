@extends('layouts.app')

@section('title', 'Activity History')
@section('page-title', 'Activity Update History')

@section('page-actions')
    <form method="GET" action="{{ route('activity.log.history', $activity) }}" class="flex items-center gap-2">
        <input type="date" name="date" value="{{ $date }}"
               class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button class="text-sm bg-brand-600 text-white px-4 py-1.5 rounded-lg hover:bg-brand-700 transition">View</button>
    </form>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <p class="text-xs font-mono text-gray-400">{{ $activity->category }}</p>
        <h2 class="font-semibold text-gray-900 mt-1">{{ $activity->title }}</h2>
        @if($activity->description)
        <p class="text-sm text-gray-500 mt-1">{{ $activity->description }}</p>
        @endif
    </div>

    <div class="divide-y divide-gray-50">
        @forelse($logs as $log)
        <div class="px-5 py-4 flex items-start gap-4 text-sm">
            <span class="font-mono text-gray-400 w-20 shrink-0">{{ $log->updated_at_time->format('H:i:s') }}</span>
            <div class="w-48 shrink-0">
                <p class="font-semibold text-gray-900">{{ $log->updater->name }}</p>
                <p class="text-xs text-gray-400 font-mono">{{ $log->updater->employee_id }}</p>
                <p class="text-xs text-gray-400">{{ $log->updater->bio }}</p>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full status-{{ $log->status }}">{{ $log->status_label }}</span>
            <div class="flex-1 min-w-0">
                <p class="text-gray-600">{{ $log->remark ?: 'No remark supplied.' }}</p>
                @if($log->actual_value || $log->expected_value || $log->variance)
                <p class="text-xs text-gray-400 font-mono mt-2">
                    Actual: {{ $log->actual_value ?? 'N/A' }} |
                    Expected: {{ $log->expected_value ?? 'N/A' }} |
                    Variance: {{ $log->variance ?? 'N/A' }}
                </p>
                @endif
            </div>
        </div>
        @empty
        <div class="px-5 py-12 text-center text-gray-400">
            No updates were recorded for this activity on {{ \Carbon\Carbon::parse($date)->format('d M Y') }}.
        </div>
        @endforelse
    </div>
</div>
@endsection
