<?php $__env->startSection('title', isset($activity) ? 'Edit Activity' : 'New Activity'); ?>
<?php $__env->startSection('page-title', isset($activity) ? 'Edit Activity' : 'Create Activity'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form action="<?php echo e(isset($activity) ? route('activities.update', $activity) : route('activities.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php if(isset($activity)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Activity Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?php echo e(old('title', $activity->title ?? '')); ?>" required
                           placeholder="e.g. Daily SMS count in comparison to SMS count from logs"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" placeholder="Detailed instructions for the activity…"
                              class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"><?php echo e(old('description', $activity->description ?? '')); ?></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                        <input type="text" name="category" value="<?php echo e(old('category', $activity->category ?? '')); ?>" required
                               placeholder="e.g. SMS Monitoring"
                               list="category-suggestions"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <datalist id="category-suggestions">
                            <option value="SMS Monitoring">
                            <option value="System Health">
                            <option value="Incident Management">
                            <option value="Database Checks">
                            <option value="Network Monitoring">
                            <option value="End-of-Day Tasks">
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="<?php echo e(old('sort_order', $activity->sort_order ?? 0)); ?>" min="0"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           <?php echo e(old('is_active', $activity->is_active ?? true) ? 'checked' : ''); ?>

                           class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <label for="is_active" class="text-sm text-gray-700">Active (visible on daily dashboard)</label>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition">
                    <?php echo e(isset($activity) ? 'Save Changes' : 'Create Activity'); ?>

                </button>
                <a href="<?php echo e(route('activities.index')); ?>" class="px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/imac/Projects/NpontuTrack-Applications-Support-Activity-Tracker/resources/views/activities/create.blade.php ENDPATH**/ ?>