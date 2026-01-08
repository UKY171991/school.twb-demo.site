<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?> - <?php echo e(config('app.name', 'School Management System')); ?></title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 shadow-lg">
            <div class="flex items-center justify-center h-16 bg-gray-900">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">🏫</span>
                    <span class="text-white font-bold text-lg">Admin Panel</span>
                </div>
            </div>
            
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="<?php echo e(route('admin.dashboard')); ?>" 
                       class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Students -->
                    <div class="space-y-1">
                        <a href="<?php echo e(route('admin.students')); ?>" 
                           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.students*') ? 'bg-gray-700 text-white' : ''); ?>">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Students
                        </a>
                        <?php if(request()->routeIs('admin.students*')): ?>
                        <div class="ml-8 space-y-1">
                            <a href="<?php echo e(route('admin.students')); ?>" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">All Students</a>
                            <a href="<?php echo e(route('admin.students.create')); ?>" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">Add Student</a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Teachers -->
                    <div class="space-y-1">
                        <a href="<?php echo e(route('admin.teachers')); ?>" 
                           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.teachers*') ? 'bg-gray-700 text-white' : ''); ?>">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Teachers
                        </a>
                        <?php if(request()->routeIs('admin.teachers*')): ?>
                        <div class="ml-8 space-y-1">
                            <a href="<?php echo e(route('admin.teachers')); ?>" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">All Teachers</a>
                            <a href="<?php echo e(route('admin.teachers.create')); ?>" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">Add Teacher</a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Subjects -->
                    <div class="space-y-1">
                        <a href="<?php echo e(route('admin.subjects')); ?>" 
                           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.subjects*') ? 'bg-gray-700 text-white' : ''); ?>">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Subjects
                        </a>
                        <?php if(request()->routeIs('admin.subjects*')): ?>
                        <div class="ml-8 space-y-1">
                            <a href="<?php echo e(route('admin.subjects')); ?>" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">All Subjects</a>
                            <a href="<?php echo e(route('admin.subjects.create')); ?>" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">Add Subject</a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Classrooms -->
                    <div class="space-y-1">
                        <a href="<?php echo e(route('admin.classrooms')); ?>" 
                           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.classrooms*') ? 'bg-gray-700 text-white' : ''); ?>">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Classrooms
                        </a>
                        <?php if(request()->routeIs('admin.classrooms*')): ?>
                        <div class="ml-8 space-y-1">
                            <a href="<?php echo e(route('admin.classrooms')); ?>" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">All Classrooms</a>
                            <a href="<?php echo e(route('admin.classrooms.create')); ?>" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">Add Classroom</a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Grades -->
                    <div class="space-y-1">
                        <a href="<?php echo e(route('admin.grades')); ?>" 
                           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.grades*') ? 'bg-gray-700 text-white' : ''); ?>">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Grades
                        </a>
                        <?php if(request()->routeIs('admin.grades*')): ?>
                        <div class="ml-8 space-y-1">
                            <a href="<?php echo e(route('admin.grades')); ?>" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">All Grades</a>
                            <a href="<?php echo e(route('admin.grades.create')); ?>" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">Add Grade</a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Reports -->
                    <a href="#" 
                       class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Reports
                    </a>

                    <!-- Settings -->
                    <a href="#" 
                       class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                        <?php if(isset($breadcrumbs)): ?>
                        <nav class="text-sm text-gray-500 mt-1">
                            <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!$loop->last): ?>
                                    <a href="<?php echo e($breadcrumb['url']); ?>" class="hover:text-gray-700"><?php echo e($breadcrumb['title']); ?></a>
                                    <span class="mx-2">/</span>
                                <?php else: ?>
                                    <span><?php echo e($breadcrumb['title']); ?></span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </nav>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.07 2.82l-.03.03a1.5 1.5 0 00-.12.22l-.01.02a1.5 1.5 0 00-.04.28v.05a1.5 1.5 0 00.05.28l.01.02a1.5 1.5 0 00.12.22l.03.03a1.5 1.5 0 00.22.12l.02.01a1.5 1.5 0 00.28.04h.05a1.5 1.5 0 00.28-.04l.02-.01a1.5 1.5 0 00.22-.12l.03-.03a1.5 1.5 0 00.12-.22l.01-.02a1.5 1.5 0 00.04-.28v-.05a1.5 1.5 0 00-.05-.28l-.01-.02a1.5 1.5 0 00-.12-.22l-.03-.03a1.5 1.5 0 00-.22-.12l-.02-.01a1.5 1.5 0 00-.28-.04h-.05a1.5 1.5 0 00-.28.04l-.02.01a1.5 1.5 0 00-.22.12z"></path>
                            </svg>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
                        </button>

                        <!-- User Menu -->
                        <div class="relative">
                            <div class="flex items-center space-x-3">
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-700"><?php echo e(auth()->user()->name); ?></div>
                                    <div class="text-xs text-gray-500">Administrator</div>
                                </div>
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-600"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Logout -->
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-gray-400 hover:text-gray-600 p-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    <?php if(session('success')): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html><?php /**PATH C:\git\school.twb-demo.site\resources\views/layouts/admin.blade.php ENDPATH**/ ?>