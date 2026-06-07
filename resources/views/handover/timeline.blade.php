@extends('layouts.app')

@section('title', 'Handover Timeline')
@section('page-title', 'Handover Timeline')

@section('page-actions')
    <form method="GET" action="{{ route('handover.index') }}" class="flex items-center gap-2">
        <input type="date" name="date" value="{{ $date }}"
               class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button class="text-sm bg-brand-600 text-white px-4 py-1.5 rounded-lg hover:bg-brand-700 transition">Go</button>
    </form>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <div>
            <p class="text-xs font-mono text-gray-400">Handover timeline for {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</p>
            <h2 class="text-lg font-semibold text-gray-900 mt-1">Open (non-done) updates</h2>
        </div>
        <p class="text-xs text-gray-400 font-mono">{{ $pending->count() }} items</p>
    </div>

    @if($pending->isEmpty())
    <div class="px-5 py-12 text-center text-gray-400">No pending handover items for this date.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-mono text-gray-400 uppercase tracking-wider">
                    <th class="px-4 py-3 text-left">Time</th>
                    <th class="px-4 py-3 text-left">Activity</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Updated by</th>
                    <th class="px-4 py-3 text-left">Remark</th>
                    <th class="px-4 py-3 text-left">Shift</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($pending as $log)
                <tr>
                    <td class="px-4 py-3 font-mono text-gray-500 text-xs">{{ $log->updated_at_time->format('H:i:s') }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $log->activity->title }}</td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full status-{{ $log->status }}">{{ $log->status_label }}</span></td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        <p class="font-medium text-gray-900">{{ $log->updater->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $log->updater->employee_id }} — {{ ucfirst(str_replace('_',' ', $log->updater->role)) }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs truncate max-w-xs" title="{{ $log->remark }}">{{ $log->remark ?? 'No remark' }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs capitalize">{{ $log->shift ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
