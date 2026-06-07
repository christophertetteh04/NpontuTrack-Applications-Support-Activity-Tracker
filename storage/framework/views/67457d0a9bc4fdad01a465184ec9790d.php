<?php $__env->startSection('title', 'Handover Timeline'); ?>
<?php $__env->startSection('page-title', 'Handover Timeline'); ?>

<?php $__env->startSection('page-actions'); ?>
    <form method="GET" action="<?php echo e(route('handover.index')); ?>" class="flex items-center gap-2">
        <input type="date" name="date" value="<?php echo e($date); ?>"
               class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button class="text-sm bg-brand-600 text-white px-4 py-1.5 rounded-lg hover:bg-brand-700 transition">Go</button>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <div>
            <p class="text-xs font-mono text-gray-400">Handover timeline for <?php echo e(\Carbon\Carbon::parse($date)->format('l, d F Y')); ?></p>
            <h2 class="text-lg font-semibold text-gray-900 mt-1">Open (non-done) updates</h2>
        </div>
        <p class="text-xs text-gray-400 font-mono"><?php echo e($pending->count()); ?> items</p>
    </div>

    <?php if($pending->isEmpty()): ?>
    <div class="px-5 py-12 text-center text-gray-400">No pending handover items for this date.</div>
    <?php else: ?>
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
                <?php $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="px-4 py-3 font-mono text-gray-500 text-xs"><?php echo e($log->updated_at_time->format('H:i:s')); ?></td>
                    <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($log->activity->title); ?></td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full status-<?php echo e($log->status); ?>"><?php echo e($log->status_label); ?></span></td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        <p class="font-medium text-gray-900"><?php echo e($log->updater->name); ?></p>
                        <p class="text-xs text-gray-400 font-mono"><?php echo e($log->updater->employee_id); ?> — <?php echo e(ucfirst(str_replace('_',' ', $log->updater->role))); ?></p>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs truncate max-w-xs" title="<?php echo e($log->remark); ?>"><?php echo e($log->remark ?? 'No remark'); ?></td>
                    <td class="px-4 py-3 text-gray-500 text-xs capitalize"><?php echo e($log->shift ?? '—'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/imac/Projects/NpontuTrack-Applications-Support-Activity-Tracker/resources/views/handover/timeline.blade.php ENDPATH**/ ?>