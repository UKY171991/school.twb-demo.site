<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold">🏫 School Management System</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if(auth()->guard()->check()): ?>
                        <span class="text-blue-200">Welcome, <?php echo e(auth()->user()->name); ?>!</span>
                        <?php if(auth()->user()->role === 'admin'): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded">Admin Dashboard</a>
                        <?php elseif(auth()->user()->role === 'teacher'): ?>
                            <a href="<?php echo e(route('teacher.dashboard')); ?>" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded">Teacher Dashboard</a>
                        <?php elseif(auth()->user()->role === 'student'): ?>
                            <a href="<?php echo e(route('student.dashboard')); ?>" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded">Student Dashboard</a>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded">Logout</button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded">Login</a>
                        <a href="<?php echo e(route('register')); ?>" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-5xl font-bold mb-6">Welcome to Our School Management System</h2>
            <p class="text-xl mb-8">Streamline your educational institution with our comprehensive management platform</p>
            <?php if(auth()->guard()->guest()): ?>
                <div class="space-x-4">
                    <a href="<?php echo e(route('login')); ?>" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">Get Started</a>
                    <a href="<?php echo e(route('about')); ?>" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">Learn More</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">System Features</h3>
                <p class="text-gray-600">Everything you need to manage your educational institution</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="text-4xl mb-4">👨‍🎓</div>
                    <h4 class="text-xl font-semibold mb-2">Student Management</h4>
                    <p class="text-gray-600">Comprehensive student profiles, enrollment tracking, and academic records</p>
                </div>
                
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="text-4xl mb-4">👩‍🏫</div>
                    <h4 class="text-xl font-semibold mb-2">Teacher Portal</h4>
                    <p class="text-gray-600">Manage classes, track student progress, and input grades efficiently</p>
                </div>
                
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="text-4xl mb-4">📊</div>
                    <h4 class="text-xl font-semibold mb-2">Admin Dashboard</h4>
                    <p class="text-gray-600">Complete oversight with analytics, reports, and system management</p>
                </div>
                
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="text-4xl mb-4">📚</div>
                    <h4 class="text-xl font-semibold mb-2">Course Management</h4>
                    <p class="text-gray-600">Organize subjects, classrooms, and curriculum planning</p>
                </div>
                
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="text-4xl mb-4">📈</div>
                    <h4 class="text-xl font-semibold mb-2">Grade Tracking</h4>
                    <p class="text-gray-600">Real-time grade management and academic performance monitoring</p>
                </div>
                
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="text-4xl mb-4">🔐</div>
                    <h4 class="text-xl font-semibold mb-2">Secure Access</h4>
                    <p class="text-gray-600">Role-based permissions ensuring data security and privacy</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Section -->
    <div class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">System Overview</h3>
            </div>
            
            <div class="grid md:grid-cols-4 gap-6 text-center">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-3xl font-bold text-blue-600 mb-2"><?php echo e(\App\Models\Student::count()); ?></div>
                    <div class="text-gray-600">Students Enrolled</div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-3xl font-bold text-green-600 mb-2"><?php echo e(\App\Models\Teacher::count()); ?></div>
                    <div class="text-gray-600">Teachers</div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-3xl font-bold text-purple-600 mb-2"><?php echo e(\App\Models\Subject::count()); ?></div>
                    <div class="text-gray-600">Subjects</div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-3xl font-bold text-orange-600 mb-2"><?php echo e(\App\Models\Classroom::count()); ?></div>
                    <div class="text-gray-600">Active Classes</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h5 class="text-lg font-semibold mb-4">School Management System</h5>
                    <p class="text-gray-400">Empowering education through technology and efficient management.</p>
                </div>
                
                <div>
                    <h5 class="text-lg font-semibold mb-4">Quick Links</h5>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="<?php echo e(route('about')); ?>" class="hover:text-white">About Us</a></li>
                        <li><a href="<?php echo e(route('contact')); ?>" class="hover:text-white">Contact</a></li>
                        <?php if(auth()->guard()->guest()): ?>
                            <li><a href="<?php echo e(route('login')); ?>" class="hover:text-white">Login</a></li>
                            <li><a href="<?php echo e(route('register')); ?>" class="hover:text-white">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div>
                    <h5 class="text-lg font-semibold mb-4">Contact Info</h5>
                    <div class="text-gray-400 space-y-2">
                        <p>📧 info@schoolsystem.com</p>
                        <p>📞 (555) 123-4567</p>
                        <p>📍 123 Education St, Learning City</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo e(date('Y')); ?> School Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\git\school.twb-demo.site\resources\views/welcome.blade.php ENDPATH**/ ?>