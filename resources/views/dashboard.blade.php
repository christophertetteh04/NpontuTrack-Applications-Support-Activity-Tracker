@extends('layouts.app')

@section('title', 'Daily Dashboard')
@section('page-title', 'Daily Operations Board')

@section('page-actions')
    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
        <input type="date" name="date" value="{{ $date }}"
               class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button class="text-sm bg-brand-600 text-white px-4 py-1.5 rounded-lg hover:bg-brand-700 transition">Go</button>
    </form>
@endsection

@section('content')

{{-- ── Date Header ──────────────────────────────────────────────── --}}
<div class="mb-6 flex items-center gap-4">
    <div>
        <h2 class="text-xl font-semibold text-gray-900">
            {{ $carbonDate->format('l, d F Y') }}
        </h2>
        <p class="text-xs text-gray-400 font-mono mt-0.5">
            {{ $carbonDate->isToday() ? 'Today — live view' : 'Historical view' }}
        </p>
    </div>
    @if(!$carbonDate->isToday())
    <a href="{{ route('dashboard') }}"
       class="ml-auto text-xs text-blue-600 hover:underline font-mono">← Back to today</a>
    @endif
</div>

{{-- ── KPI Strip ─────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
    @php
    $kpis = [
        ['label'=>'Total Activities', 'value'=>$stats['total'],    'color'=>'gray'],
        ['label'=>'Done',             'value'=>$stats['done'],     'color'=>'emerald'],
        ['label'=>'Pending / Other',  'value'=>$stats['pending'],  'color'=>'amber'],
        ['label'=>'Escalated',        'value'=>$stats['escalated'],'color'=>'red'],
        ['label'=>'Updates Today',    'value'=>$stats['updates'],  'color'=>'blue'],
        ['label'=>'Active Personnel', 'value'=>$stats['personnel'],'color'=>'purple'],
    ];
    @endphp
    @foreach($kpis as $k)
    <div class="bg-white rounded-xl border border-gray-100 px-4 py-3">
        <p class="text-2xl font-bold text-{{ $k['color'] }}-600">{{ $k['value'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">{{ $k['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- ── Activity Groups ───────────────────────────────────────────── --}}
@forelse($grouped as $category => $activities)
<div class="mb-8">
    <h3 class="text-xs font-mono font-semibold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-brand-500 inline-block"></span>
        {{ $category }}
    </h3>

    <div class="space-y-3">
        @foreach($activities as $activity)
        @php
            $logs     = $activity->logs;                          // all logs for this date
            $latest   = $logs->last();                            // most recent update
            $status   = $latest ? $latest->status : 'pending';
        @endphp

        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            {{-- Activity header --}}
            <div class="px-5 py-4 flex items-start gap-4">
                {{-- Status badge --}}
                <span class="shrink-0 mt-0.5 text-xs font-mono px-2 py-0.5 rounded-full status-{{ $status }}">
                    {{ str_replace('_', ' ', strtoupper($status)) }}
                </span>

                {{-- Title + description --}}
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 text-sm">{{ $activity->title }}</p>
                    @if($activity->description)
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $activity->description }}</p>
                    @endif
                </div>

                {{-- Update button --}}
                <button onclick="openUpdateModal({{ $activity->id }}, '{{ addslashes($activity->title) }}', '{{ $date }}')"
                        class="shrink-0 text-xs bg-brand-600 hover:bg-brand-700 text-white px-3 py-1.5 rounded-lg transition">
                    Update
                </button>
            </div>

            {{-- ── Handover Timeline: all updates for this date ── --}}
            @if($logs->count() > 0)
            <div class="border-t border-gray-50 bg-gray-50 px-5 py-3">
                <p class="text-xs font-mono text-gray-400 mb-2">Update timeline ({{ $carbonDate->format('d M') }})</p>
                <div class="space-y-2">
                    @foreach($logs as $log)
                    <div class="flex items-start gap-3 text-xs">
                        {{-- Time --}}
                        <span class="font-mono text-gray-400 shrink-0 w-14">
                            {{ $log->updated_at_time->format('H:i') }}
                        </span>
                        {{-- Personnel --}}
                        <span class="shrink-0">
                            <span class="font-medium text-gray-700">{{ $log->updater->name }}</span>
                            <span class="text-gray-400 font-mono"> [{{ $log->updater->employee_id }}]</span>
                        </span>
                        {{-- Status --}}
                        <span class="shrink-0 px-1.5 py-0.5 rounded text-xs status-{{ $log->status }}">
                            {{ $log->status_label }}
                        </span>
                        {{-- Remark --}}
                        @if($log->remark)
                        <span class="text-gray-500 italic truncate">— {{ $log->remark }}</span>
                        @endif
                        {{-- Metric values --}}
                        @if($log->actual_value)
                        <span class="text-gray-400 font-mono ml-auto shrink-0">
                            {{ $log->actual_value }}
                            @if($log->expected_value) / {{ $log->expected_value }} @endif
                        </span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="text-center py-16 text-gray-400">
    <p class="text-lg">No activities configured.</p>
    @if(auth()->user()->isTeamLead())
    <a href="{{ route('activities.create') }}" class="mt-2 inline-block text-sm text-blue-600 hover:underline">Add your first activity →</a>
    @endif
</div>
@endforelse

@endsection

{{-- ── Update Modal ─────────────────────────────────────────────── --}}
@push('scripts')
<script>
let currentActivityId = null;

function openUpdateModal(id, title, date) {
    currentActivityId = id;
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-log-date').value = date;
    document.getElementById('update-modal').classList.remove('hidden');
}

function closeUpdateModal() {
    document.getElementById('update-modal').classList.add('hidden');
    document.getElementById('update-form').reset();
}

document.getElementById('update-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);
    const url  = `/activities/${currentActivityId}/log`;

    const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
        body: data,
    });

    if (res.ok) {
        closeUpdateModal();
        window.location.reload();
    } else {
        alert('Failed to save update. Please try again.');
    }
});
</script>

{{-- Modal markup --}}
<div id="update-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-mono">Updating activity</p>
                <p id="modal-title" class="font-semibold text-gray-900 mt-0.5"></p>
            </div>
            <button onclick="closeUpdateModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="update-form" class="px-6 py-5 space-y-4">
            <input type="hidden" name="log_date" id="modal-log-date">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="done">Done</option>
                        <option value="escalated">Escalated</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Shift</label>
                    <select name="shift" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Select —</option>
                        <option value="morning">Morning</option>
                        <option value="afternoon">Afternoon</option>
                        <option value="night">Night</option>
                    </select>
                </div>
            </div>

            {{-- SMS / Metric fields (Requirement 1) --}}
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Expected Value</label>
                    <input type="text" name="expected_value" placeholder="e.g. SMS from logs"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Actual Value</label>
                    <input type="text" name="actual_value" placeholder="e.g. Daily SMS"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Variance</label>
                    <input type="text" name="variance" placeholder="Difference"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Remark</label>
                <textarea name="remark" rows="3" placeholder="Describe what was observed, actions taken, or notes for handover…"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>

            {{-- Personnel info (auto-filled, display only) --}}
            <div class="bg-gray-50 rounded-lg px-4 py-3 text-xs text-gray-500 flex items-center gap-4">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <div>
                    Updating as <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                    <span class="font-mono text-gray-400 ml-1">[{{ auth()->user()->employee_id }}]</span>
                    — timestamp will be recorded automatically.
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-1">
                <button type="button" onclick="closeUpdateModal()" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition">Save Update</button>
            </div>
        </form>
    </div>
</div>
@endpush
