<?php $__env->startSection('title', isset($user) ? 'Edit User' : 'Add User'); ?>
<?php $__env->startSection('page-title', isset($user) ? 'Edit User' : 'Add Team Member'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form action="<?php echo e(isset($user) ? route('users.update', $user) : route('users.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php if(isset($user)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <div class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="<?php echo e(old('name', $user->name ?? '')); ?>" required
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID <span class="text-red-500">*</span></label>
                        <input type="text" name="employee_id" value="<?php echo e(old('employee_id', $user->employee_id ?? '')); ?>"
                               <?php echo e(isset($user) ? 'readonly class="bg-gray-50"' : 'required'); ?>

                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="<?php echo e(old('email', $user->email ?? '')); ?>"
                           <?php echo e(isset($user) ? 'readonly class="bg-gray-50"' : 'required'); ?>

                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone ?? '')); ?>"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <input type="text" name="department" value="<?php echo e(old('department', $user->department ?? 'Applications Support')); ?>"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                    <input type="text" name="designation" value="<?php echo e(old('designation', $user->designation ?? '')); ?>" placeholder="e.g. Support Analyst"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="staff"     <?php if(old('role', $user->role ?? '') === 'staff'): echo 'selected'; endif; ?>>Staff</option>
                        <option value="team_lead" <?php if(old('role', $user->role ?? '') === 'team_lead'): echo 'selected'; endif; ?>>Team Lead</option>
                        <option value="admin"     <?php if(old('role', $user->role ?? '') === 'admin'): echo 'selected'; endif; ?>>Administrator</option>
                    </select>
                </div>

                <?php if(isset($user)): ?>
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo e($user->is_active ? 'checked' : ''); ?>

                           class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <label for="is_active" class="text-sm text-gray-700">Account active</label>
                </div>
                <?php endif; ?>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-xs text-gray-400 mb-3"><?php echo e(isset($user) ? 'Leave blank to keep current password' : 'Set initial password'); ?></p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <?php echo e(isset($user) ? '' : '*'); ?></label>
                            <input type="password" name="password" <?php echo e(isset($user) ? '' : 'required'); ?>

                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition">
                    <?php echo e(isset($user) ? 'Save Changes' : 'Create User'); ?>

                </button>
                <a href="<?php echo e(route('users.index')); ?>" class="px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/imac/Desktop/Nodejs Backend/npontu-tracker/resources/views/users/create.blade.php ENDPATH**/ ?>