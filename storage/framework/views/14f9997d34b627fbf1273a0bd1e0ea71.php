<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login - <?php echo e(config('app.name', 'School Management System')); ?></title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Login Form -->
        <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <!-- Header -->
                <div class="text-center">
                    <a href="/" class="inline-block">
                        <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">🏫</span>
                        </div>
                    </a>
                    <h2 class="mt-6 text-3xl font-bold text-gray-900">Welcome back!</h2>
                    <p class="mt-2 text-sm text-gray-600">Sign in to your School Management account</p>
                </div>

                <!-- Session Status -->
                <?php if(session('status')): ?>
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="<?php echo e(route('login')); ?>" class="mt-8 space-y-6">
                    <?php echo csrf_field(); ?>
                    
                    <div class="space-y-4">
                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" required autofocus
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Enter your email">
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" name="password" type="password" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Enter your password">
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                Remember me
                            </label>
                        </div>
                        <?php if(Route::has('password.request')): ?>
                            <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-blue-600 hover:text-blue-500 font-medium">
                                Forgot password?
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 transform hover:scale-105">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-blue-300 group-hover:text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Sign In
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account? 
                            <a href="<?php echo e(route('register')); ?>" class="font-medium text-blue-600 hover:text-blue-500 transition duration-200">
                                Create one here
                            </a>
                        </p>
                    </div>
                </form>

                <!-- Demo Accounts -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Demo Accounts:</h3>
                    <div class="text-xs text-gray-600 space-y-1">
                        <p><strong>Admin:</strong> admin@example.com</p>
                        <p><strong>Teacher:</strong> teacher1@example.com</p>
                        <p><strong>Student:</strong> student1@example.com</p>
                        <p class="text-gray-500">Password: <code>password</code></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Image/Info -->
        <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-center bg-gradient-to-br from-blue-600 to-purple-700">
            <div class="max-w-md text-center text-white px-8">
                <div class="mb-8">
                    <div class="text-6xl mb-4">📚</div>
                    <h2 class="text-3xl font-bold mb-4">School Management System</h2>
                    <p class="text-blue-100 text-lg leading-relaxed">
                        Streamline your educational institution with our comprehensive management platform. 
                        Manage students, teachers, courses, and grades all in one place.
                    </p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-white bg-opacity-10 rounded-lg p-3">
                        <div class="text-2xl mb-1">👨‍🎓</div>
                        <div class="font-semibold">Student Portal</div>
                    </div>
                    <div class="bg-white bg-opacity-10 rounded-lg p-3">
                        <div class="text-2xl mb-1">👩‍🏫</div>
                        <div class="font-semibold">Teacher Dashboard</div>
                    </div>
                    <div class="bg-white bg-opacity-10 rounded-lg p-3">
                        <div class="text-2xl mb-1">📊</div>
                        <div class="font-semibold">Admin Panel</div>
                    </div>
                    <div class="bg-white bg-opacity-10 rounded-lg p-3">
                        <div class="text-2xl mb-1">📈</div>
                        <div class="font-semibold">Grade Tracking</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\git\school.twb-demo.site\resources\views/auth/login.blade.php ENDPATH**/ ?>