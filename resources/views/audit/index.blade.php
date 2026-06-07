@extends('layouts.app')

@section('title', 'Activity Audit')
@section('page-title', 'Activity Audit & History')

@section('page-actions')
    <form method="GET" action="{{ route('audit.index') }}" class="flex items-center gap-2">
        <input type="date" name="date" value="{{ $date }}"
               class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="activity_id" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5">
            <option value="">All activities</option>
            @foreach($activities as $a)
            <option value="{{ $a->id }}" @selected(($filters['activity_id'] ?? '') == $a->id)>{{ $a->title }}</option>
            @endforeach
        </select>
        <select name="user_id" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5">
            <option value="">All personnel</option>
            @foreach($users as $u)
            <option value="{{ $u->id }}" @selected(($filters['user_id'] ?? '') == $u->id)>{{ $u->name }}</option>
            @endforeach
        </select>
        <button class="text-sm bg-brand-600 text-white px-4 py-1.5 rounded-lg hover:bg-brand-700 transition">Filter</button>
    </form>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <p class="text-sm font-medium text-gray-700">{{ $logs->total() }} records</p>
        <div class="text-xs text-gray-400 font-mono">Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}</div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-mono text-gray-400 uppercase tracking-wider">
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Time</th>
                    <th class="px-4 py-3 text-left">Activity</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Personnel</th>
                    <th class="px-4 py-3 text-left">Remark</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr>
                    <td class="px-4 py-3 font-mono text-gray-500 text-xs">{{ $log->log_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 font-mono text-gray-500 text-xs">{{ $log->updated_at_time->format('H:i:s') }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $log->activity->title }}</td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full status-{{ $log->status }}">{{ $log->status_label }}</span></td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        <div class="font-medium">{{ $log->updater->name }}</div>
                        <div class="text-xs text-gray-400 font-mono">{{ $log->updater->employee_id }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs" title="{{ $log->remark }}">{{ $log->remark ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-5 py-4 border-t border-gray-50">{{ $logs->links() }}</div>
</div>
@endsection
