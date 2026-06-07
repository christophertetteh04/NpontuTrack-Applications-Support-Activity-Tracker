<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg border-l-4 border-brand-600 p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Seal Shift Handover</h1>
            <p class="text-gray-600">Formally close this shift and generate an audit PDF</p>
        </div>

        <!-- Status Messages -->
        <?php if($existingSeal): ?>
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-semibold text-amber-800">Already Sealed</p>
                    <p class="text-sm text-amber-700">This shift was sealed on <?php echo e($existingSeal->created_at->format('Y-m-d H:i')); ?> by <?php echo e($existingSeal->sealer->name); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg p-4 shadow">
                <p class="text-gray-600 text-xs font-semibold mb-1">TOTAL</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo e($summary['total']); ?></p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 shadow border-l-4 border-green-500">
                <p class="text-green-600 text-xs font-semibold mb-1">DONE</p>
                <p class="text-2xl font-bold text-green-700"><?php echo e($summary['done']); ?></p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 shadow border-l-4 border-blue-500">
                <p class="text-blue-600 text-xs font-semibold mb-1">IN PROGRESS</p>
                <p class="text-2xl font-bold text-blue-700"><?php echo e($summary['in_progress']); ?></p>
            </div>
            <div class="bg-amber-50 rounded-lg p-4 shadow border-l-4 border-amber-500">
                <p class="text-amber-600 text-xs font-semibold mb-1">PENDING</p>
                <p class="text-2xl font-bold text-amber-700"><?php echo e($summary['pending']); ?></p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 shadow border-l-4 border-red-500">
                <p class="text-red-600 text-xs font-semibold mb-1">ESCALATED</p>
                <p class="text-2xl font-bold text-red-700"><?php echo e($summary['escalated']); ?></p>
            </div>
        </div>

        <!-- Activities Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Activity</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Personnel</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Remark</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                <?php echo e($log->activity->title); ?>

                                <span class="block text-xs text-gray-500"><?php echo e($log->activity->category); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php if($log->status === 'done'): ?> bg-green-100 text-green-800
                                    <?php elseif($log->status === 'in_progress'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($log->status === 'escalated'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-amber-100 text-amber-800
                                    <?php endif; ?>
                                ">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $log->status))); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <?php echo e($log->updater->name); ?>

                                <span class="block text-xs text-gray-500">#<?php echo e($log->updater->employee_id); ?></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 font-mono">
                                <?php echo e($log->updated_at_time->format('H:i:s')); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?php echo e($log->remark ? Str::limit($log->remark, 50) : '—'); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No activities for this shift
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Shift & Date Info -->
        <div class="bg-blue-50 rounded-lg p-6 mb-6 border border-blue-200">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-semibold text-blue-900 mb-2">SHIFT DETAILS</p>
                    <div class="space-y-2">
                        <div>
                            <span class="text-xs text-blue-700">Date:</span>
                            <p class="font-mono text-lg font-bold text-blue-900"><?php echo e(\Carbon\Carbon::parse($date)->format('l, F j, Y')); ?></p>
                        </div>
                        <div>
                            <span class="text-xs text-blue-700">Shift:</span>
                            <p class="font-mono text-lg font-bold text-blue-900 capitalize"><?php echo e($shift); ?></p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-blue-900 mb-2">SEALER INFO</p>
                    <div class="space-y-2">
                        <div>
                            <span class="text-xs text-blue-700">Sealing as:</span>
                            <p class="font-semibold text-blue-900"><?php echo e(auth()->user()->name); ?></p>
                        </div>
                        <div>
                            <span class="text-xs text-blue-700">Employee ID:</span>
                            <p class="font-mono font-bold text-blue-900">#<?php echo e(auth()->user()->employee_id); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <a href="<?php echo e(route('handover.index')); ?>" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition">
                ← Back to Handover
            </a>

            <?php if(!$existingSeal): ?>
            <form method="POST" action="<?php echo e(route('handover.seal.store')); ?>" class="flex-1">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="sealed_date" value="<?php echo e($date); ?>">
                <input type="hidden" name="shift" value="<?php echo e($shift); ?>">

                <button type="submit" class="w-full px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Seal This Shift & Generate PDF
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/imac/Projects/NpontuTrack-Applications-Support-Activity-Tracker/resources/views/handover/seal.blade.php ENDPATH**/ ?>