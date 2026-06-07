<?php $__env->startSection('title', 'Manage Activities'); ?>
<?php $__env->startSection('page-title', 'Activity Definitions'); ?>

<?php $__env->startSection('page-actions'); ?>
    <?php if(auth()->user()->isTeamLead()): ?>
    <a href="<?php echo e(route('activities.create')); ?>"
       class="text-sm bg-brand-600 hover:bg-brand-700 text-white px-4 py-1.5 rounded-lg transition">+ New Activity</a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $acts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="mb-6">
    <h3 class="text-xs font-mono font-semibold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
        <span class="w-2 h-2 bg-brand-500 rounded-full"></span> <?php echo e($category); ?>

    </h3>
    <div class="bg-white rounded-xl border border-gray-100 divide-y divide-gray-50 overflow-hidden">
        <?php $__currentLoopData = $acts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center gap-4 px-5 py-3">
            <div class="flex-1">
                <p class="font-medium text-sm text-gray-900"><?php echo e($activity->title); ?></p>
                <?php if($activity->description): ?>
                <p class="text-xs text-gray-400 mt-0.5"><?php echo e($activity->description); ?></p>
                <?php endif; ?>
            </div>
            <span class="text-xs font-mono px-2 py-0.5 rounded-full <?php echo e($activity->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-400'); ?>">
                <?php echo e($activity->is_active ? 'Active' : 'Inactive'); ?>

            </span>
            <span class="text-xs text-gray-400">by <?php echo e($activity->creator->name); ?></span>
            <?php if(auth()->user()->isTeamLead()): ?>
            <a href="<?php echo e(route('activities.edit', $activity)); ?>" class="text-xs text-blue-600 hover:underline">Edit</a>
            <form action="<?php echo e(route('activities.destroy', $activity)); ?>" method="POST" onsubmit="return confirm('Deactivate this activity?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="text-xs text-red-400 hover:text-red-600">Deactivate</button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="text-center py-16 text-gray-400">
    <p>No activities yet.</p>
    <?php if(auth()->user()->isTeamLead()): ?>
    <a href="<?php echo e(route('activities.create')); ?>" class="mt-2 inline-block text-sm text-blue-600 hover:underline">Create your first activity →</a>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/imac/Projects/NpontuTrack-Applications-Support-Activity-Tracker/resources/views/activities/index.blade.php ENDPATH**/ ?>