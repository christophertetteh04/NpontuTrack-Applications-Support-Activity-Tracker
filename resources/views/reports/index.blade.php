@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Activity History Reports')

@section('page-actions')
    <a href="{{ route('reports.export', request()->query()) }}"
       class="text-sm bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5 rounded-lg transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export CSV
    </a>
@endsection

@section('content')

<form method="GET" action="{{ route('reports.index') }}" class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
    <p class="text-xs font-mono text-gray-400 mb-4 uppercase tracking-widest">Filter — Custom Duration Query</p>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">From</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">To</label>
            <input type="date" name="date_to" value="{{ $dateTo }}"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Activity</label>
            <select name="activity_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Activities</option>
                @foreach($activities as $a)
                <option value="{{ $a->id }}" @selected(($filters['activity_id'] ?? '') == $a->id)>{{ $a->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
            <select name="category" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected(($filters['category'] ?? '') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
            <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Statuses</option>
                <option value="done"        @selected(($filters['status'] ?? '') === 'done')>Done</option>
                <option value="pending"     @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                <option value="in_progress" @selected(($filters['status'] ?? '') === 'in_progress')>In Progress</option>
                <option value="escalated"   @selected(($filters['status'] ?? '') === 'escalated')>Escalated</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Personnel</label>
            <select name="user_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Personnel</option>
                @foreach($users as $u)
                <option value="{{ $u->id }}" @selected(($filters['user_id'] ?? '') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-brand-600 hover:bg-brand-700 text-white py-2 rounded-lg text-sm font-medium transition">Filter</button>
            <a href="{{ route('reports.index') }}" class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-500 hover:bg-gray-50 transition">✕</a>
        </div>
    </div>
</form>

<div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-6">
    @php
    $sumCards = [
        ['label'=>'Total Updates', 'value'=>$summary['total_updates'], 'color'=>'blue'],
        ['label'=>'Done',          'value'=>$summary['done'],          'color'=>'emerald'],
        ['label'=>'Pending',       'value'=>$summary['pending'],       'color'=>'amber'],
        ['label'=>'Escalated',     'value'=>$summary['escalated'],     'color'=>'red'],
        ['label'=>'Days Covered',  'value'=>$summary['unique_days'],   'color'=>'purple'],
        ['label'=>'Staff Involved','value'=>$summary['unique_staff'],  'color'=>'gray'],
    ];
    @endphp
    @foreach($sumCards as $c)
    <div class="bg-white rounded-xl border border-gray-100 px-4 py-3">
        <p class="text-xl font-bold text-{{ $c['color'] }}-600">{{ $c['value'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $c['label'] }}</p>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <p class="text-sm font-medium text-gray-700">
            {{ $logs->total() }} records — {{ $dateFrom }} to {{ $dateTo }}
        </p>
        <p class="text-xs text-gray-400 font-mono">Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-mono text-gray-400 uppercase tracking-wider">
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Activity</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Personnel</th>
                    <th class="px-4 py-3 text-left">Shift</th>
                    <th class="px-4 py-3 text-left">Expected</th>
                    <th class="px-4 py-3 text-left">Actual</th>
                    <th class="px-4 py-3 text-left">Remark</th>
                    <th class="px-4 py-3 text-left">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-gray-500 text-xs whitespace-nowrap">{{ $log->log_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900 max-w-48 truncate">{{ $log->activity->title }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $log->activity->category }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full status-{{ $log->status }}">{{ $log->status_label }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-gray-900 text-xs font-medium">{{ $log->updater->name }}</p>
                        <p class="text-gray-400 font-mono text-xs">{{ $log->updater->employee_id }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs capitalize">{{ $log->shift ?? '—' }}</td>
                    <td class="px-4 py-3 font-mono text-gray-500 text-xs">{{ $log->expected_value ?? '—' }}</td>
                    <td class="px-4 py-3 font-mono text-gray-500 text-xs">{{ $log->actual_value ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs max-w-48 truncate" title="{{ $log->remark }}">
                        {{ $log->remark ?? '—' }}
                    </td>
                    <td class="px-4 py-3 font-mono text-gray-400 text-xs whitespace-nowrap">{{ $log->updated_at_time->format('H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-12 text-center text-gray-400">No records match the selected filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-5 py-4 border-t border-gray-50">
        {{ $logs->links() }}
    </div>
    @endif
</div>

@endsection
