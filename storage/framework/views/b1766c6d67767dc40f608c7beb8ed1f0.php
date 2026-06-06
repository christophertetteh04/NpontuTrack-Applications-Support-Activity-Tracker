<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'NpontuTrack'); ?> — Applications Support</title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:  ['IBM Plex Sans', 'sans-serif'],
                        mono:  ['IBM Plex Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50:  '#f0f4ff',
                            100: '#dce6ff',
                            500: '#3b5bdb',
                            600: '#2f4ac9',
                            700: '#2440b0',
                            800: '#1a306e',
                            900: '#111f47',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .status-done { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }
        .status-pending { background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
        .status-in_progress { background:#e0f2fe; color:#075985; border:1px solid #bae6fd; }
        .status-escalated { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="h-full bg-gray-50 font-sans text-gray-900 antialiased">


<div class="flex h-full min-h-screen">

    
    <aside class="w-64 shrink-0 bg-brand-900 text-white flex flex-col">
        
        <div class="px-6 py-5 border-b border-brand-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-brand-500 flex items-center justify-center text-white font-bold text-sm">NT</div>
                <div>
                    <p class="font-semibold text-sm leading-tight">NpontuTrack</p>
                    <p class="text-xs text-brand-100 opacity-60 font-mono">App Support v1.0</p>
                </div>
            </div>
        </div>

        
        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
            <a href="<?php echo e(route('dashboard')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo e(request()->routeIs('dashboard') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-800'); ?> transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Daily Dashboard
            </a>
            <a href="<?php echo e(route('activities.index')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo e(request()->routeIs('activities.*') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-800'); ?> transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Activities
            </a>
            <a href="<?php echo e(route('reports.daily')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo e(request()->routeIs('reports.daily') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-800'); ?> transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Daily Summary
            </a>
            <a href="<?php echo e(route('reports.index')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo e(request()->routeIs('reports.index') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-800'); ?> transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Reports
            </a>
            <?php if(auth()->user()->isAdmin()): ?>
            <div class="pt-3 mt-3 border-t border-brand-800">
                <p class="px-3 text-xs font-mono text-brand-100 opacity-40 uppercase tracking-widest mb-1">Admin</p>
                <a href="<?php echo e(route('users.index')); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo e(request()->routeIs('users.*') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-800'); ?> transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Manage Users
                </a>
            </div>
            <?php endif; ?>
        </nav>

        
        <div class="px-4 py-4 border-t border-brand-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-brand-600 flex items-center justify-center text-xs font-bold uppercase">
                    <?php echo e(substr(auth()->user()->name, 0, 2)); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate"><?php echo e(auth()->user()->name); ?></p>
                    <p class="text-xs text-brand-100 opacity-50 font-mono"><?php echo e(auth()->user()->employee_id); ?></p>
                </div>
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" title="Logout" class="text-brand-100 opacity-40 hover:opacity-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between shrink-0">
            <div>
                <h1 class="text-lg font-semibold text-gray-900"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                <p class="text-xs text-gray-400 font-mono"><?php echo e(now()->format('l, d M Y')); ?></p>
            </div>
            <div class="flex items-center gap-3">
                <?php echo $__env->yieldContent('page-actions'); ?>
                <span class="text-xs font-mono px-2 py-1 rounded bg-brand-50 text-brand-600 border border-brand-100">
                    <?php echo e(ucfirst(auth()->user()->role)); ?>

                </span>
            </div>
        </header>

        
        <?php if(session('success')): ?>
        <div class="mx-8 mt-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
        <div class="mx-8 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        
        <main class="flex-1 overflow-y-auto p-8">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/imac/Desktop/Nodejs Backend/npontu-tracker/resources/views/layouts/app.blade.php ENDPATH**/ ?>