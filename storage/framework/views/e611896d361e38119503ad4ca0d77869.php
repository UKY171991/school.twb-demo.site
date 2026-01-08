<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'School Management System')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="text-xl font-bold text-gray-800">
                            <?php echo e(config('app.name', 'School Management System')); ?>

                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="<?php echo e(route('about')); ?>" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">About</a>
                        <a href="<?php echo e(route('contact')); ?>" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(url('/dashboard')); ?>" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                            <?php if(Route::has('register')): ?>
                                <a href="<?php echo e(route('register')); ?>" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium">Register</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <?php if(isset($header)): ?>
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?>

        <!-- Page Content -->
        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'School Management System')); ?>. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html><?php /**PATH C:\git\school.twb-demo.site\resources\views/layouts/public.blade.php ENDPATH**/ ?>