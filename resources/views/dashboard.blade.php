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
    <a href="{{ route('dashboard') }}" class="ml-auto text-xs text-blue-600 hover:underline font-mono">← Back to
        today</a>
    @endif
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
    @php
    $kpis = [
    ['label'=>'Total Activities', 'value'=>$stats['total'], 'color'=>'gray'],
    ['label'=>'Done', 'value'=>$stats['done'], 'color'=>'emerald'],
    ['label'=>'Pending / Other', 'value'=>$stats['pending'], 'color'=>'amber'],
    ['label'=>'Escalated', 'value'=>$stats['escalated'],'color'=>'red'],
    ['label'=>'Updates Today', 'value'=>$stats['updates'], 'color'=>'blue'],
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

<div class="grid gap-3 mb-8 lg:grid-cols-3">
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs font-mono text-gray-400 uppercase tracking-widest">Handover board</p>
                <h2 class="text-lg font-semibold text-gray-900">Pending updates needing review</h2>
            </div>
            <span class="text-xs font-mono text-gray-500">{{ $pendingHandover->count() }} open items</span>
        </div>

        @if($pendingHandover->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-8 text-center text-gray-500">
            All activities are up to date for this day. No active handover items found.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs font-mono text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3 text-left">Activity</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Updated by</th>
                        <th class="px-4 py-3 text-left">Shift</th>
                        <th class="px-4 py-3 text-left">Remark</th>
                        <th class="px-4 py-3 text-left">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($pendingHandover as $log)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $log->activity->title }}</td>
                        <td class="px-4 py-3"><span
                                class="text-xs px-2 py-0.5 rounded-full status-{{ $log->status }}">{{ $log->status_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            {{ $log->updater->name }} <span
                                class="text-gray-400">[{{ $log->updater->employee_id }}]</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs capitalize">{{ $log->shift ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs truncate max-w-xs" title="{{ $log->remark }}">
                            {{ $log->remark ?? 'No remark' }}</td>
                        <td class="px-4 py-3 font-mono text-gray-400 text-xs">{{ $log->updated_at_time->format('H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if(in_array(auth()->user()->role, ['team_lead', 'admin']))
        <div class="mt-4 flex gap-3">
            <a href="{{ route('handover.index') }}"
                class="text-xs text-gray-600 hover:text-gray-900 px-3 py-2 hover:bg-gray-100 rounded-lg transition">
                View Full Handover →
            </a>
            <div class="flex-1"></div>
            <a href="{{ route('handover.seal.create', ['date' => $date, 'shift' => 'morning']) }}"
                class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg transition border border-blue-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Seal Shift
            </a>
        </div>
        @endif
    </div>

    <div class="space-y-3">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-mono text-gray-400 uppercase tracking-widest">Team handover</p>
                    <h2 class="text-lg font-semibold text-gray-900">Active contributors</h2>
                </div>
                <span class="text-xs font-mono text-gray-500">{{ $handoverByPerson->count() }} people</span>
            </div>
            @if($handoverByPerson->isEmpty())
            <p class="text-sm text-gray-500">No open handover items at the moment.</p>
            @else
            <div class="space-y-3">
                @foreach($handoverByPerson as $person)
                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                    <p class="font-semibold text-gray-900">{{ $person['name'] }}</p>
                    <p class="text-xs text-gray-400 font-mono">{{ $person['employee_id'] }} —
                        {{ ucfirst(str_replace('_', ' ', $person['role'])) }}</p>
                    <div class="mt-2 flex items-center justify-between gap-3 text-xs text-gray-500">
                        <span>{{ $person['count'] }} pending item(s)</span>
                        <span>Last update {{ $person['latest_time'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-mono text-gray-400 uppercase tracking-widest">Recent activity</p>
                    <h2 class="text-lg font-semibold text-gray-900">Latest updates</h2>
                </div>
                <span class="text-xs font-mono text-gray-500">{{ $recentUpdates->count() }} entries</span>
            </div>
            @if($recentUpdates->isEmpty())
            <p class="text-sm text-gray-500">No updates have been submitted today yet.</p>
            @else
            <div class="space-y-3">
                @foreach($recentUpdates as $log)
                <div class="rounded-2xl border border-gray-100 p-4 bg-gray-50">
                    <div class="flex items-center gap-3">
                        <span
                            class="font-mono text-gray-400 text-xs">{{ \Carbon\Carbon::parse($log->updated_at_time)->format('H:i') }}</span>
                        <span class="text-sm font-medium text-gray-900">{{ $log->activity->title }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $log->updater->name }} · <span
                            class="uppercase">{{ $log->shift ?? 'no shift' }}</span></p>
                    <p class="text-xs mt-2 text-gray-600">{{ $log->remark ?? 'No remark provided.' }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

@forelse($grouped as $category => $activities)
<div class="mb-8">
    <h3 class="text-xs font-mono font-semibold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-brand-500 inline-block"></span>
        {{ $category }}
    </h3>

    <div class="space-y-3">
        @foreach($activities as $activity)
        @php
        $logs = $activity->logs;
        $latest = $logs->last();
        $status = $latest ? $latest->status : 'pending';
        @endphp

        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">

            <div class="px-5 py-4 flex items-start gap-4">

                <span class="shrink-0 mt-0.5 text-xs font-mono px-2 py-0.5 rounded-full status-{{ $status }}">
                    {{ str_replace('_', ' ', strtoupper($status)) }}
                </span>


                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 text-sm">{{ $activity->title }}</p>
                    @if($activity->description)
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $activity->description }}</p>
                    @endif
                </div>


                <button type="button" data-activity-id="{{ $activity->id }}"
                    data-activity-title="{{ $activity->title }}" data-log-date="{{ $date }}"
                    onclick="openUpdateModal(this)"
                    class="shrink-0 text-xs bg-brand-600 hover:bg-brand-700 text-white px-3 py-1.5 rounded-lg transition">
                    Update
                </button>
            </div>


            @if($logs->count() > 0)
            <div class="border-t border-gray-50 bg-gray-50 px-5 py-3">
                <p class="text-xs font-mono text-gray-400 mb-2">Update timeline ({{ $carbonDate->format('d M') }})</p>
                <div class="space-y-2">
                    @foreach($logs as $log)
                    <div class="space-y-1">
                        @if($log->change_tracking_label)
                        <div class="text-xs text-blue-600 flex items-center gap-2">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>{!! $log->change_tracking_label !!}</span>
                        </div>
                        @endif

                        <div class="flex items-start gap-3 text-xs">
                            <span class="font-mono text-gray-400 shrink-0 w-14">
                                {{ \Carbon\Carbon::parse($log->updated_at_time)->format('H:i') }}
                            </span>

                            <span class="shrink-0">
                                <span class="font-medium text-gray-700">{{ $log->updater->name }}</span>
                                <span class="text-gray-400 font-mono"> [{{ $log->updater->employee_id }}]</span>
                            </span>

                            <span class="shrink-0 px-1.5 py-0.5 rounded text-xs status-{{ $log->status }}">
                                {{ $log->status_label }}
                            </span>

                            @if($log->remark)
                            <span class="text-gray-500 italic truncate">— {{ $log->remark }}</span>
                            @endif

                            @if($log->actual_value)
                            <span class="text-gray-400 font-mono ml-auto shrink-0">
                                {{ $log->actual_value }}
                                @if($log->expected_value) / {{ $log->expected_value }} @endif
                            </span>
                            @endif
                        </div>
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
    @if(in_array(auth()->user()->role, ['team_lead', 'admin']))
    <a href="{{ route('activities.create') }}" class="mt-2 inline-block text-sm text-blue-600 hover:underline">Add your
        first activity →</a>
    @endif
</div>
@endforelse

@endsection

@push('scripts')
<div id="update-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-mono">Updating activity</p>
                <p id="modal-title" class="font-semibold text-gray-900 mt-0.5"></p>
            </div>
            <button onclick="closeUpdateModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="update-form" class="px-6 py-5 space-y-4">
            <input type="hidden" name="log_date" id="modal-log-date">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Status <span
                            class="text-red-500">*</span></label>
                    <select name="status" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="done">Done</option>
                        <option value="escalated">Escalated</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Shift</label>
                    <select name="shift"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Select —</option>
                        <option value="morning">Morning</option>
                        <option value="afternoon">Afternoon</option>
                        <option value="night">Night</option>
                    </select>
                </div>
            </div>


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
                <textarea name="remark" rows="3"
                    placeholder="Describe what was observed, actions taken, or notes for handover…"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>


            <div class="bg-gray-50 rounded-lg px-4 py-3 text-xs text-gray-500 flex items-center gap-4">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <div>
                    Updating as <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                    <span class="font-mono text-gray-400 ml-1">[{{ auth()->user()->employee_id }}]</span>
                    — timestamp will be recorded automatically.
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-1">
                <button type="button" onclick="closeUpdateModal()"
                    class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition">Cancel</button>
                <button type="submit"
                    class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition">Save
                    Update</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentActivityId = null;

    window.openUpdateModal = function(button) {
        currentActivityId = button.dataset.activityId;
        const title = button.dataset.activityTitle || '';
        const date = button.dataset.logDate || '';
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-log-date').value = date;
        const modal = document.getElementById('update-modal');
        modal.classList.remove('hidden');
        const firstField = modal.querySelector('select[name="status"]') || modal.querySelector(
            'input, textarea');
        if (firstField) firstField.focus();
    };

    window.closeUpdateModal = function() {
        const modal = document.getElementById('update-modal');
        modal.classList.add('hidden');
        const form = document.getElementById('update-form');
        if (form) form.reset();
    };

    const updateForm = document.getElementById('update-form');
    if (updateForm) {
        updateForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = this;
            const data = new FormData(form);
            const url = `/activities/${currentActivityId}/log`;

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                body: data,
            });

            if (res.ok) {
                closeUpdateModal();
                window.location.reload();
            } else {
                const err = await res.json().catch(() => null);
                alert((err && err.message) ? err.message :
                    'Failed to save update. Please try again.');
            }
        });
    }
});
</script>
@endpush