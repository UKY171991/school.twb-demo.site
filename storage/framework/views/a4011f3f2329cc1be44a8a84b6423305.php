<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Forgot Password - <?php echo e(config('app.name', 'School Management System')); ?></title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-purple-50 via-white to-blue-50">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <a href="/" class="inline-block">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">🏫</span>
                    </div>
                </a>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">Forgot Password?</h2>
                <p class="mt-2 text-sm text-gray-600 max-w-sm mx-auto">
                    No problem! Enter your email address and we'll send you a password reset link.
                </p>
            </div>

            <!-- Session Status -->
            <?php if(session('status')): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <?php echo e(session('status')); ?>

                    </div>
                </div>
            <?php endif; ?>

            <!-- Reset Form -->
            <form method="POST" action="<?php echo e(route('password.email')); ?>" class="mt-8 space-y-6">
                <?php echo csrf_field(); ?>
                
                <div>
                    <!-- Email Address -->
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
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Enter your email address">
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

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 transform hover:scale-105">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-purple-300 group-hover:text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        Send Reset Link
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Remember your password? 
                        <a href="<?php echo e(route('login')); ?>" class="font-medium text-purple-600 hover:text-purple-500 transition duration-200">
                            Back to login
                        </a>
                    </p>
                </div>
            </form>

            <!-- Help Section -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Need Help?</h3>
                <div class="text-xs text-gray-600 space-y-1">
                    <p>• Check your spam/junk folder for the reset email</p>
                    <p>• The reset link expires after 60 minutes</p>
                    <p>• Contact support if you don't receive the email</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\git\school.twb-demo.site\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>