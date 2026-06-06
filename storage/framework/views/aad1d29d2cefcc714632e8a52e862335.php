<?php $__env->startSection('title', 'Manage Users'); ?>
<?php $__env->startSection('page-title', 'User Management'); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('users.create')); ?>"
       class="text-sm bg-brand-600 hover:bg-brand-700 text-white px-4 py-1.5 rounded-lg transition">+ Add User</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 text-xs font-mono text-gray-400 uppercase tracking-wider">
                <th class="px-5 py-3 text-left">Name</th>
                <th class="px-5 py-3 text-left">Employee ID</th>
                <th class="px-5 py-3 text-left">Email</th>
                <th class="px-5 py-3 text-left">Department</th>
                <th class="px-5 py-3 text-left">Role</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 text-xs font-bold uppercase">
                            <?php echo e(substr($user->name, 0, 2)); ?>

                        </div>
                        <span class="font-medium text-gray-900"><?php echo e($user->name); ?></span>
                    </div>
                </td>
                <td class="px-5 py-3 font-mono text-gray-500 text-xs"><?php echo e($user->employee_id); ?></td>
                <td class="px-5 py-3 text-gray-600"><?php echo e($user->email); ?></td>
                <td class="px-5 py-3 text-gray-500"><?php echo e($user->department); ?></td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full
                        <?php if($user->role === 'admin'): ?> bg-red-100 text-red-700
                        <?php elseif($user->role === 'team_lead'): ?> bg-amber-100 text-amber-700
                        <?php else: ?> bg-sky-100 text-sky-700 <?php endif; ?>">
                        <?php echo e(ucfirst(str_replace('_', ' ', $user->role))); ?>

                    </span>
                </td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full <?php echo e($user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-400'); ?>">
                        <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                    </span>
                </td>
                <td class="px-5 py-3">
                    <a href="<?php echo e(route('users.edit', $user)); ?>" class="text-xs text-blue-600 hover:underline">Edit</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/imac/Desktop/Nodejs Backend/npontu-tracker/resources/views/users/index.blade.php ENDPATH**/ ?>