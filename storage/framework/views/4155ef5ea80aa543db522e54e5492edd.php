<?php $__env->startSection('title', 'Activity History'); ?>
<?php $__env->startSection('page-title', 'Activity Update History'); ?>

<?php $__env->startSection('page-actions'); ?>
    <form method="GET" action="<?php echo e(route('activity.log.history', $activity)); ?>" class="flex items-center gap-2">
        <input type="date" name="date" value="<?php echo e($date); ?>"
               class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button class="text-sm bg-brand-600 text-white px-4 py-1.5 rounded-lg hover:bg-brand-700 transition">View</button>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <p class="text-xs font-mono text-gray-400"><?php echo e($activity->category); ?></p>
        <h2 class="font-semibold text-gray-900 mt-1"><?php echo e($activity->title); ?></h2>
        <?php if($activity->description): ?>
        <p class="text-sm text-gray-500 mt-1"><?php echo e($activity->description); ?></p>
        <?php endif; ?>
    </div>

    <div class="divide-y divide-gray-50">
        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="px-5 py-4 flex items-start gap-4 text-sm">
            <span class="font-mono text-gray-400 w-20 shrink-0"><?php echo e($log->updated_at_time->format('H:i:s')); ?></span>
            <div class="w-48 shrink-0">
                <p class="font-semibold text-gray-900"><?php echo e($log->updater->name); ?></p>
                <p class="text-xs text-gray-400 font-mono"><?php echo e($log->updater->employee_id); ?></p>
                <p class="text-xs text-gray-400"><?php echo e($log->updater->bio); ?></p>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full status-<?php echo e($log->status); ?>"><?php echo e($log->status_label); ?></span>
            <div class="flex-1 min-w-0">
                <p class="text-gray-600"><?php echo e($log->remark ?: 'No remark supplied.'); ?></p>
                <?php if($log->actual_value || $log->expected_value || $log->variance): ?>
                <p class="text-xs text-gray-400 font-mono mt-2">
                    Actual: <?php echo e($log->actual_value ?? 'N/A'); ?> |
                    Expected: <?php echo e($log->expected_value ?? 'N/A'); ?> |
                    Variance: <?php echo e($log->variance ?? 'N/A'); ?>

                </p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="px-5 py-12 text-center text-gray-400">
            No updates were recorded for this activity on <?php echo e(\Carbon\Carbon::parse($date)->format('d M Y')); ?>.
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/imac/Desktop/Nodejs Backend/npontu-tracker/resources/views/activities/history.blade.php ENDPATH**/ ?>