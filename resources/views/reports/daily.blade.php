@extends('layouts.app')

@section('title', 'Daily Summary')
@section('page-title', 'Daily Summary Report')

@section('page-actions')
    <form method="GET" action="{{ route('reports.daily') }}" class="flex items-center gap-2">
        <input type="date" name="date" value="{{ $date }}"
               class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button class="text-sm bg-brand-600 text-white px-4 py-1.5 rounded-lg hover:bg-brand-700 transition">View</button>
    </form>
@endsection

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-semibold">{{ $carbonDate->format('l, d F Y') }}</h2>
    <p class="text-xs text-gray-400 font-mono mt-0.5">Full daily snapshot — all activities, all updates, all personnel</p>
</div>

@php $grouped = $activities->groupBy('category'); @endphp

@foreach($grouped as $category => $acts)
<div class="mb-8">
    <h3 class="text-xs font-mono font-semibold uppercase tracking-widest text-gray-400 mb-3">{{ $category }}</h3>

    @foreach($acts as $activity)
    @php $logs = $activity->logs; $latest = $logs->last(); $status = $latest?->status ?? 'pending'; @endphp

    <div class="bg-white rounded-xl border border-gray-100 mb-3 overflow-hidden">
        <div class="px-5 py-3 flex items-center gap-3 border-b border-gray-50">
            <span class="text-xs px-2 py-0.5 rounded-full status-{{ $status }}">{{ str_replace('_',' ',strtoupper($status)) }}</span>
            <span class="font-medium text-gray-900 text-sm">{{ $activity->title }}</span>
            <span class="ml-auto text-xs text-gray-400 font-mono">{{ $logs->count() }} update(s)</span>
        </div>

        @if($logs->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($logs as $i => $log)
            <div class="px-5 py-3 flex items-start gap-4 text-xs {{ $i === $logs->count()-1 ? 'bg-blue-50/30' : '' }}">
                <span class="font-mono text-gray-400 w-14 shrink-0">{{ $log->updated_at_time->format('H:i:s') }}</span>
                <div class="w-36 shrink-0">
                    <p class="font-semibold text-gray-800">{{ $log->updater->name }}</p>
                    <p class="text-gray-400 font-mono">{{ $log->updater->employee_id }}</p>
                    @if($log->shift)<p class="text-gray-400 capitalize mt-0.5">{{ $log->shift }} shift</p>@endif
                </div>
                <span class="px-1.5 py-0.5 rounded status-{{ $log->status }} shrink-0">{{ $log->status_label }}</span>
                <div class="flex-1">
                    @if($log->remark)<p class="text-gray-600">{{ $log->remark }}</p>@endif
                    @if($log->actual_value)
                    <p class="text-gray-400 font-mono mt-1">Actual: {{ $log->actual_value }} | Expected: {{ $log->expected_value ?? 'N/A' }} | Variance: {{ $log->variance ?? 'N/A' }}</p>
                    @endif
                </div>
                @if($i === $logs->count()-1)
                <span class="text-xs text-blue-600 font-mono shrink-0">← LATEST</span>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="px-5 py-3 text-xs text-gray-400 italic">No updates recorded for this activity on {{ $carbonDate->format('d M') }}.</div>
        @endif
    </div>
    @endforeach
</div>
@endforeach
@endsection
