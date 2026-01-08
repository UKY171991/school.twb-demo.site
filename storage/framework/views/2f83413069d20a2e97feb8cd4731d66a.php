<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?> - <?php echo e(config('app.name', 'SMS')); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-72 bg-slate-900 text-slate-400 flex-shrink-0 flex flex-col transition-all duration-300">
            <div class="h-20 flex items-center px-8 bg-slate-950 border-b border-slate-800/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center shadow-lg transform rotate-3">
                        <span class="text-xl">🏫</span>
                    </div>
                    <div>
                        <span class="text-white font-bold text-xl tracking-tight">EduManage</span>
                        <p class="text-[10px] uppercase tracking-widest text-indigo-400 font-bold">Pro Edition</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 mt-6 px-4 space-y-1 sidebar-scroll overflow-y-auto">
                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4 opacity-50">Core Management</p>
                
                <!-- Dashboard -->
                <a href="<?php echo e(route('admin.dashboard')); ?>" 
                   class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo e(request()->routeIs('admin.dashboard') ? 'sidebar-item-active' : 'hover:bg-white/5 hover:text-white'); ?>">
                    <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-white'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-semibold">Dashboard</span>
                </a>

                <!-- Schools -->
                <a href="<?php echo e(route('admin.schools')); ?>" 
                   class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo e(request()->routeIs('admin.schools*') ? 'sidebar-item-active' : 'hover:bg-white/5 hover:text-white'); ?>">
                    <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('admin.schools*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-white'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="font-semibold">Schools</span>
                </a>

                <div class="pt-6">
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4 opacity-50">Academic</p>
                    <a href="<?php echo e(route('admin.students')); ?>" 
                       class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo e(request()->routeIs('admin.students*') ? 'sidebar-item-active' : 'hover:bg-white/5 hover:text-white'); ?>">
                        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('admin.students*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-white'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <span class="font-semibold">Students</span>
                    </a>

                    <a href="<?php echo e(route('admin.teachers')); ?>" 
                       class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo e(request()->routeIs('admin.teachers*') ? 'sidebar-item-active' : 'hover:bg-white/5 hover:text-white'); ?>">
                        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('admin.teachers*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-white'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-semibold">Teachers</span>
                    </a>

                    <a href="<?php echo e(route('admin.subjects')); ?>" 
                       class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo e(request()->routeIs('admin.subjects*') ? 'sidebar-item-active' : 'hover:bg-white/5 hover:text-white'); ?>">
                        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('admin.subjects*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-white'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="font-semibold">Subjects</span>
                    </a>
                </div>

                <div class="pt-6">
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4 opacity-50">Infrastructure</p>
                    <a href="<?php echo e(route('admin.classrooms')); ?>" 
                       class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo e(request()->routeIs('admin.classrooms*') ? 'sidebar-item-active' : 'hover:bg-white/5 hover:text-white'); ?>">
                        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('admin.classrooms*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-white'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="font-semibold">Classrooms</span>
                    </a>

                    <a href="<?php echo e(route('admin.grades')); ?>" 
                       class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo e(request()->routeIs('admin.grades*') ? 'sidebar-item-active' : 'hover:bg-white/5 hover:text-white'); ?>">
                        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('admin.grades*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-white'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-semibold">Grades</span>
                    </a>
                </div>
            </nav>

            <div class="p-4 bg-slate-950/50 mt-auto border-t border-slate-800/50">
                <div class="flex items-center p-3 glass-card bg-white/5 border-white/5 rounded-2xl shadow-none">
                    <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center text-white font-bold mr-3 shadow-sm border border-white/10">
                        <?php echo e(substr(auth()->user()->name, 0, 1)); ?>

                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-tight">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">
            <!-- Top Navigation -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 flex items-center justify-between px-8">
                <div class="flex items-center flex-1">
                    <div class="mr-6 lg:hidden">
                        <button class="p-2 text-slate-600 hover:bg-slate-100 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        </button>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800 leading-tight"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                        <?php if(isset($breadcrumbs)): ?>
                        <nav class="flex text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-1">
                            <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!$loop->last): ?>
                                    <a href="<?php echo e($breadcrumb['url']); ?>" class="hover:text-indigo-600 transition-colors"><?php echo e($breadcrumb['title']); ?></a>
                                    <span class="mx-2 opacity-50">/</span>
                                <?php else: ?>
                                    <span class="text-indigo-500"><?php echo e($breadcrumb['title']); ?></span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </nav>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="flex items-center space-x-6">
                    <!-- Notifications -->
                    <button class="relative p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.07 2.82l-.03.03a1.5 1.5 0 00-.12.22l-.01.02a1.5 1.5 0 00-.04.28v.05a1.5 1.5 0 00.05.28l.01.02a1.5 1.5 0 00.12.22l.03.03a1.5 1.5 0 00.22.12l.02.01a1.5 1.5 0 00.28.04h.05a1.5 1.5 0 00.28-.04l.02-.01a1.5 1.5 0 00.22-.12l.03-.03a1.5 1.5 0 00.12-.22l.01-.02a1.5 1.5 0 00.04-.28v-.05a1.5 1.5 0 00-.05-.28l-.01-.02a1.5 1.5 0 00-.12-.22l-.03-.03a1.5 1.5 0 00-.22-.12l-.02-.01a1.5 1.5 0 00-.28-.04h-.05a1.5 1.5 0 00-.28.04l-.02.01a1.5 1.5 0 00-.22.12z"></path>
                        </svg>
                        <span class="absolute top-2.5 right-2.5 block h-2 w-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                    </button>

                    <!-- Logout -->
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Logout">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                <div class="container mx-auto px-8 py-10">
                    <?php if(session('success')): ?>
                        <div class="flex items-center p-4 mb-6 text-emerald-800 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-2xl premium-shadow" role="alert">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="flex items-center p-4 mb-6 text-rose-800 bg-rose-50 border-l-4 border-rose-500 rounded-r-2xl premium-shadow" role="alert">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            <span class="font-bold text-sm"><?php echo e(session('error')); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html><?php /**PATH C:\git\school.twb-demo.site\resources\views/layouts/admin.blade.php ENDPATH**/ ?>