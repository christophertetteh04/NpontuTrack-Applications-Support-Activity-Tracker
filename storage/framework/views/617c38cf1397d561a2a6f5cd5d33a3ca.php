<?php $__env->startSection('title', 'Daily Summary'); ?>
<?php $__env->startSection('page-title', 'Daily Summary Report'); ?>

<?php $__env->startSection('page-actions'); ?>
    <form method="GET" action="<?php echo e(route('reports.daily')); ?>" class="flex items-center gap-2">
        <input type="date" name="date" value="<?php echo e($date); ?>"
               class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button class="text-sm bg-brand-600 text-white px-4 py-1.5 rounded-lg hover:bg-brand-700 transition">View</button>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h2 class="text-xl font-semibold"><?php echo e($carbonDate->format('l, d F Y')); ?></h2>
    <p class="text-xs text-gray-400 font-mono mt-0.5">Full daily snapshot — all activities, all updates, all personnel</p>
</div>

<?php $grouped = $activities->groupBy('category'); ?>

<?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $acts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="mb-8">
    <h3 class="text-xs font-mono font-semibold uppercase tracking-widest text-gray-400 mb-3"><?php echo e($category); ?></h3>

    <?php $__currentLoopData = $acts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $logs = $activity->logs; $latest = $logs->last(); $status = $latest?->status ?? 'pending'; ?>

    <div class="bg-white rounded-xl border border-gray-100 mb-3 overflow-hidden">
        <div class="px-5 py-3 flex items-center gap-3 border-b border-gray-50">
            <span class="text-xs px-2 py-0.5 rounded-full status-<?php echo e($status); ?>"><?php echo e(str_replace('_',' ',strtoupper($status))); ?></span>
            <span class="font-medium text-gray-900 text-sm"><?php echo e($activity->title); ?></span>
            <span class="ml-auto text-xs text-gray-400 font-mono"><?php echo e($logs->count()); ?> update(s)</span>
        </div>

        <?php if($logs->count() > 0): ?>
        <div class="divide-y divide-gray-50">
            <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="px-5 py-3 flex items-start gap-4 text-xs <?php echo e($i === $logs->count()-1 ? 'bg-blue-50/30' : ''); ?>">
                <span class="font-mono text-gray-400 w-14 shrink-0"><?php echo e($log->updated_at_time->format('H:i:s')); ?></span>
                <div class="w-36 shrink-0">
                    <p class="font-semibold text-gray-800"><?php echo e($log->updater->name); ?></p>
                    <p class="text-gray-400 font-mono"><?php echo e($log->updater->employee_id); ?></p>
                    <?php if($log->shift): ?><p class="text-gray-400 capitalize mt-0.5"><?php echo e($log->shift); ?> shift</p><?php endif; ?>
                </div>
                <span class="px-1.5 py-0.5 rounded status-<?php echo e($log->status); ?> shrink-0"><?php echo e($log->status_label); ?></span>
                <div class="flex-1">
                    <?php if($log->remark): ?><p class="text-gray-600"><?php echo e($log->remark); ?></p><?php endif; ?>
                    <?php if($log->actual_value): ?>
                    <p class="text-gray-400 font-mono mt-1">Actual: <?php echo e($log->actual_value); ?> | Expected: <?php echo e($log->expected_value ?? 'N/A'); ?> | Variance: <?php echo e($log->variance ?? 'N/A'); ?></p>
                    <?php endif; ?>
                </div>
                <?php if($i === $logs->count()-1): ?>
                <span class="text-xs text-blue-600 font-mono shrink-0">← LATEST</span>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="px-5 py-3 text-xs text-gray-400 italic">No updates recorded for this activity on <?php echo e($carbonDate->format('d M')); ?>.</div>
        <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/imac/Projects/NpontuTrack-Applications-Support-Activity-Tracker/resources/views/reports/daily.blade.php ENDPATH**/ ?>