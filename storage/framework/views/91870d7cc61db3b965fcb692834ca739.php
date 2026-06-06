<?php $__env->startSection('title', 'Reports'); ?>
<?php $__env->startSection('page-title', 'Activity History Reports'); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('reports.export', request()->query())); ?>"
       class="text-sm bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5 rounded-lg transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export CSV
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<form method="GET" action="<?php echo e(route('reports.index')); ?>" class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
    <p class="text-xs font-mono text-gray-400 mb-4 uppercase tracking-widest">Filter — Custom Duration Query</p>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">From</label>
            <input type="date" name="date_from" value="<?php echo e($dateFrom); ?>"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">To</label>
            <input type="date" name="date_to" value="<?php echo e($dateTo); ?>"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Activity</label>
            <select name="activity_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Activities</option>
                <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($a->id); ?>" <?php if(($filters['activity_id'] ?? '') == $a->id): echo 'selected'; endif; ?>><?php echo e($a->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
            <select name="category" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat); ?>" <?php if(($filters['category'] ?? '') === $cat): echo 'selected'; endif; ?>><?php echo e($cat); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
            <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Statuses</option>
                <option value="done"        <?php if(($filters['status'] ?? '') === 'done'): echo 'selected'; endif; ?>>Done</option>
                <option value="pending"     <?php if(($filters['status'] ?? '') === 'pending'): echo 'selected'; endif; ?>>Pending</option>
                <option value="in_progress" <?php if(($filters['status'] ?? '') === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
                <option value="escalated"   <?php if(($filters['status'] ?? '') === 'escalated'): echo 'selected'; endif; ?>>Escalated</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Personnel</label>
            <select name="user_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Personnel</option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($u->id); ?>" <?php if(($filters['user_id'] ?? '') == $u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-brand-600 hover:bg-brand-700 text-white py-2 rounded-lg text-sm font-medium transition">Filter</button>
            <a href="<?php echo e(route('reports.index')); ?>" class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-500 hover:bg-gray-50 transition">✕</a>
        </div>
    </div>
</form>


<div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-6">
    <?php
    $sumCards = [
        ['label'=>'Total Updates', 'value'=>$summary['total_updates'], 'color'=>'blue'],
        ['label'=>'Done',          'value'=>$summary['done'],          'color'=>'emerald'],
        ['label'=>'Pending',       'value'=>$summary['pending'],       'color'=>'amber'],
        ['label'=>'Escalated',     'value'=>$summary['escalated'],     'color'=>'red'],
        ['label'=>'Days Covered',  'value'=>$summary['unique_days'],   'color'=>'purple'],
        ['label'=>'Staff Involved','value'=>$summary['unique_staff'],  'color'=>'gray'],
    ];
    ?>
    <?php $__currentLoopData = $sumCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white rounded-xl border border-gray-100 px-4 py-3">
        <p class="text-xl font-bold text-<?php echo e($c['color']); ?>-600"><?php echo e($c['value']); ?></p>
        <p class="text-xs text-gray-400 mt-0.5"><?php echo e($c['label']); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <p class="text-sm font-medium text-gray-700">
            <?php echo e($logs->total()); ?> records — <?php echo e($dateFrom); ?> to <?php echo e($dateTo); ?>

        </p>
        <p class="text-xs text-gray-400 font-mono">Page <?php echo e($logs->currentPage()); ?> of <?php echo e($logs->lastPage()); ?></p>
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
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-gray-500 text-xs whitespace-nowrap"><?php echo e($log->log_date->format('d M Y')); ?></td>
                    <td class="px-4 py-3 font-medium text-gray-900 max-w-48 truncate"><?php echo e($log->activity->title); ?></td>
                    <td class="px-4 py-3 text-gray-500 text-xs"><?php echo e($log->activity->category); ?></td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full status-<?php echo e($log->status); ?>"><?php echo e($log->status_label); ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-gray-900 text-xs font-medium"><?php echo e($log->updater->name); ?></p>
                        <p class="text-gray-400 font-mono text-xs"><?php echo e($log->updater->employee_id); ?></p>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs capitalize"><?php echo e($log->shift ?? '—'); ?></td>
                    <td class="px-4 py-3 font-mono text-gray-500 text-xs"><?php echo e($log->expected_value ?? '—'); ?></td>
                    <td class="px-4 py-3 font-mono text-gray-500 text-xs"><?php echo e($log->actual_value ?? '—'); ?></td>
                    <td class="px-4 py-3 text-gray-500 text-xs max-w-48 truncate" title="<?php echo e($log->remark); ?>">
                        <?php echo e($log->remark ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3 font-mono text-gray-400 text-xs whitespace-nowrap"><?php echo e($log->updated_at_time->format('H:i:s')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="10" class="px-4 py-12 text-center text-gray-400">No records match the selected filters.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($logs->hasPages()): ?>
    <div class="px-5 py-4 border-t border-gray-50">
        <?php echo e($logs->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/imac/Desktop/Nodejs Backend/npontu-tracker/resources/views/reports/index.blade.php ENDPATH**/ ?>