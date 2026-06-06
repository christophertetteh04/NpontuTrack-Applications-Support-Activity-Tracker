<?php $__env->startSection('title', 'Daily Dashboard'); ?>
<?php $__env->startSection('page-title', 'Daily Operations Board'); ?>

<?php $__env->startSection('page-actions'); ?>
    <form method="GET" action="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2">
        <input type="date" name="date" value="<?php echo e($date); ?>"
               class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button class="text-sm bg-brand-600 text-white px-4 py-1.5 rounded-lg hover:bg-brand-700 transition">Go</button>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="mb-6 flex items-center gap-4">
    <div>
        <h2 class="text-xl font-semibold text-gray-900">
            <?php echo e($carbonDate->format('l, d F Y')); ?>

        </h2>
        <p class="text-xs text-gray-400 font-mono mt-0.5">
            <?php echo e($carbonDate->isToday() ? 'Today — live view' : 'Historical view'); ?>

        </p>
    </div>
    <?php if(!$carbonDate->isToday()): ?>
    <a href="<?php echo e(route('dashboard')); ?>"
       class="ml-auto text-xs text-blue-600 hover:underline font-mono">← Back to today</a>
    <?php endif; ?>
</div>


<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
    <?php
    $kpis = [
        ['label'=>'Total Activities', 'value'=>$stats['total'],    'color'=>'gray'],
        ['label'=>'Done',             'value'=>$stats['done'],     'color'=>'emerald'],
        ['label'=>'Pending / Other',  'value'=>$stats['pending'],  'color'=>'amber'],
        ['label'=>'Escalated',        'value'=>$stats['escalated'],'color'=>'red'],
        ['label'=>'Updates Today',    'value'=>$stats['updates'],  'color'=>'blue'],
        ['label'=>'Active Personnel', 'value'=>$stats['personnel'],'color'=>'purple'],
    ];
    ?>
    <?php $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white rounded-xl border border-gray-100 px-4 py-3">
        <p class="text-2xl font-bold text-<?php echo e($k['color']); ?>-600"><?php echo e($k['value']); ?></p>
        <p class="text-xs text-gray-500 mt-0.5"><?php echo e($k['label']); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php $__empty_1 = true; $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $activities): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="mb-8">
    <h3 class="text-xs font-mono font-semibold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-brand-500 inline-block"></span>
        <?php echo e($category); ?>

    </h3>

    <div class="space-y-3">
        <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $logs     = $activity->logs;                          // all logs for this date
            $latest   = $logs->last();                            // most recent update
            $status   = $latest ? $latest->status : 'pending';
        ?>

        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            
            <div class="px-5 py-4 flex items-start gap-4">
                
                <span class="shrink-0 mt-0.5 text-xs font-mono px-2 py-0.5 rounded-full status-<?php echo e($status); ?>">
                    <?php echo e(str_replace('_', ' ', strtoupper($status))); ?>

                </span>

                
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 text-sm"><?php echo e($activity->title); ?></p>
                    <?php if($activity->description): ?>
                    <p class="text-xs text-gray-400 mt-0.5 truncate"><?php echo e($activity->description); ?></p>
                    <?php endif; ?>
                </div>

                
                <button onclick="openUpdateModal(<?php echo e($activity->id); ?>, '<?php echo e(addslashes($activity->title)); ?>', '<?php echo e($date); ?>')"
                        class="shrink-0 text-xs bg-brand-600 hover:bg-brand-700 text-white px-3 py-1.5 rounded-lg transition">
                    Update
                </button>
            </div>

            
            <?php if($logs->count() > 0): ?>
            <div class="border-t border-gray-50 bg-gray-50 px-5 py-3">
                <p class="text-xs font-mono text-gray-400 mb-2">Update timeline (<?php echo e($carbonDate->format('d M')); ?>)</p>
                <div class="space-y-2">
                    <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start gap-3 text-xs">
                        
                        <span class="font-mono text-gray-400 shrink-0 w-14">
                            <?php echo e($log->updated_at_time->format('H:i')); ?>

                        </span>
                        
                        <span class="shrink-0">
                            <span class="font-medium text-gray-700"><?php echo e($log->updater->name); ?></span>
                            <span class="text-gray-400 font-mono"> [<?php echo e($log->updater->employee_id); ?>]</span>
                        </span>
                        
                        <span class="shrink-0 px-1.5 py-0.5 rounded text-xs status-<?php echo e($log->status); ?>">
                            <?php echo e($log->status_label); ?>

                        </span>
                        
                        <?php if($log->remark): ?>
                        <span class="text-gray-500 italic truncate">— <?php echo e($log->remark); ?></span>
                        <?php endif; ?>
                        
                        <?php if($log->actual_value): ?>
                        <span class="text-gray-400 font-mono ml-auto shrink-0">
                            <?php echo e($log->actual_value); ?>

                            <?php if($log->expected_value): ?> / <?php echo e($log->expected_value); ?> <?php endif; ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="text-center py-16 text-gray-400">
    <p class="text-lg">No activities configured.</p>
    <?php if(auth()->user()->isTeamLead()): ?>
    <a href="<?php echo e(route('activities.create')); ?>" class="mt-2 inline-block text-sm text-blue-600 hover:underline">Add your first activity →</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
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

            
            <div class="bg-gray-50 rounded-lg px-4 py-3 text-xs text-gray-500 flex items-center gap-4">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <div>
                    Updating as <span class="font-semibold text-gray-700"><?php echo e(auth()->user()->name); ?></span>
                    <span class="font-mono text-gray-400 ml-1">[<?php echo e(auth()->user()->employee_id); ?>]</span>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/imac/Desktop/Nodejs Backend/npontu-tracker/resources/views/dashboard.blade.php ENDPATH**/ ?>