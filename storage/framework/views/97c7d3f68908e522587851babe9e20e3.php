

<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Students Card -->
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Students</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2"><?php echo e($stats['students']); ?></p>
                <p class="text-sm text-gray-500 mt-1">Total enrolled students</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <a href="<?php echo e(route('admin.students')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors">
                View All
            </a>
            <a href="<?php echo e(route('admin.students.create')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-300 transition-colors">
                Add New
            </a>
        </div>
    </div>

    <!-- Teachers Card -->
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Teachers</h3>
                <p class="text-3xl font-bold text-green-600 mt-2"><?php echo e($stats['teachers']); ?></p>
                <p class="text-sm text-gray-500 mt-1">Active teaching staff</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <a href="<?php echo e(route('admin.teachers')); ?>" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700 transition-colors">
                View All
            </a>
            <a href="<?php echo e(route('admin.teachers.create')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-300 transition-colors">
                Add New
            </a>
        </div>
    </div>

    <!-- Subjects Card -->
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Subjects</h3>
                <p class="text-3xl font-bold text-purple-600 mt-2"><?php echo e($stats['subjects']); ?></p>
                <p class="text-sm text-gray-500 mt-1">Available subjects</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <a href="<?php echo e(route('admin.subjects')); ?>" class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm hover:bg-purple-700 transition-colors">
                View All
            </a>
            <a href="<?php echo e(route('admin.subjects.create')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-300 transition-colors">
                Add New
            </a>
        </div>
    </div>

    <!-- Classrooms Card -->
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Classrooms</h3>
                <p class="text-3xl font-bold text-orange-600 mt-2"><?php echo e($stats['classrooms']); ?></p>
                <p class="text-sm text-gray-500 mt-1">Active classrooms</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <a href="<?php echo e(route('admin.classrooms')); ?>" class="bg-orange-600 text-white px-4 py-2 rounded-md text-sm hover:bg-orange-700 transition-colors">
                View All
            </a>
            <a href="<?php echo e(route('admin.classrooms.create')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-300 transition-colors">
                Add New
            </a>
        </div>
    </div>

    <!-- Enrollments Card -->
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Enrollments</h3>
                <p class="text-3xl font-bold text-indigo-600 mt-2"><?php echo e($stats['enrollments']); ?></p>
                <p class="text-sm text-gray-500 mt-1">Student enrollments</p>
            </div>
            <div class="bg-indigo-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <span class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm">View Only</span>
        </div>
    </div>

    <!-- Grades Card -->
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Grades</h3>
                <p class="text-3xl font-bold text-red-600 mt-2"><?php echo e($stats['grades']); ?></p>
                <p class="text-sm text-gray-500 mt-1">Total grades recorded</p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <a href="<?php echo e(route('admin.grades')); ?>" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition-colors">
                View All
            </a>
            <a href="<?php echo e(route('admin.grades.create')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-300 transition-colors">
                Add New
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Students -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Recent Students</h3>
        </div>
        <div class="p-6">
            <?php
                $recentStudents = \App\Models\Student::with('user')->latest()->take(5)->get();
            ?>
            <?php if($recentStudents->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $recentStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-600"><?php echo e(substr($student->user->name, 0, 1)); ?></span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800"><?php echo e($student->user->name); ?></p>
                                <p class="text-sm text-gray-500">ID: <?php echo e($student->student_id); ?></p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('admin.students.edit', $student)); ?>" class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No students found</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Teachers -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Recent Teachers</h3>
        </div>
        <div class="p-6">
            <?php
                $recentTeachers = \App\Models\Teacher::with('user')->latest()->take(5)->get();
            ?>
            <?php if($recentTeachers->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $recentTeachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-green-600"><?php echo e(substr($teacher->user->name, 0, 1)); ?></span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800"><?php echo e($teacher->user->name); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e($teacher->department); ?></p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('admin.teachers.edit', $teacher)); ?>" class="text-green-600 hover:text-green-800 text-sm">Edit</a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No teachers found</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>