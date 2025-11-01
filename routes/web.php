<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\TC\DashboardController as TCDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Routes
Route::middleware(['auth', 'school.context'])->group(function () {
    
    // Super Admin specific routes
    Route::middleware(['role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/switch-school', function(\Illuminate\Http\Request $request) {
            $schoolId = $request->input('school_id');
            $success = \App\Services\SchoolContextService::switchSchool($schoolId);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => $success,
                    'message' => $success ? 'School context switched successfully' : 'Failed to switch school context'
                ]);
            }
            
            return redirect()->back()->with($success ? 'success' : 'error', 
                $success ? 'School context switched successfully' : 'Failed to switch school context');
        })->name('switch-school');
        
        // Super Admin school management
        Route::resource('schools', 'App\Http\Controllers\Admin\SchoolController');
        Route::resource('users', 'App\Http\Controllers\SuperAdmin\UserController');
    });

    // Admin Dashboard (School-specific)
    Route::middleware(['role:admin', 'school.active'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::get('/dashboard/pending-payments', [AdminDashboardController::class, 'getPendingPayments'])->name('pending-payments');
        Route::get('/dashboard/latest-notifications', [AdminDashboardController::class, 'getLatestNotifications'])->name('latest-notifications');
        Route::get('/dashboard/activity-log', [AdminDashboardController::class, 'getActivityLogData'])->name('activity-log');

        
        // Schools Management
        Route::resource('schools', 'App\Http\Controllers\Admin\SchoolController');
        Route::post('schools/{school}/toggle-status', 'App\Http\Controllers\Admin\SchoolController@toggleStatus')->name('schools.toggle-status');
        
        // Teachers Management
        Route::resource('teachers', 'App\Http\Controllers\Admin\TeacherController');
        Route::post('teachers/{teacher}/toggle-status', 'App\Http\Controllers\Admin\TeacherController@toggleStatus')->name('teachers.toggle-status');
        
        // Students Management
        Route::resource('students', 'App\Http\Controllers\Admin\StudentController');
        Route::post('students/{student}/toggle-status', 'App\Http\Controllers\Admin\StudentController@toggleStatus')->name('students.toggle-status');
        
        // Classes Management
        Route::resource('classes', 'App\Http\Controllers\Admin\ClassController');
        Route::post('classes/{class}/toggle-status', 'App\Http\Controllers\Admin\ClassController@toggleStatus')->name('classes.toggle-status');
        
        // Subjects Management
        Route::resource('subjects', 'App\Http\Controllers\Admin\SubjectController');
        Route::post('subjects/{subject}/toggle-status', 'App\Http\Controllers\Admin\SubjectController@toggleStatus')->name('subjects.toggle-status');
        
        // Attendance Management
        Route::resource('attendance', 'App\Http\Controllers\Admin\AttendanceController');
        Route::get('attendance/reports', 'App\Http\Controllers\Admin\AttendanceController@reports')->name('attendance.reports');
        
        // Grades Management
        Route::resource('grades', 'App\Http\Controllers\Admin\GradeController');
        Route::get('grades/reports', 'App\Http\Controllers\Admin\GradeController@reports')->name('grades.reports');
        
        // Parents Management
        Route::resource('parents', 'App\Http\Controllers\Admin\ParentController');
        
        // Reports
        Route::get('reports', 'App\Http\Controllers\Admin\ReportController@index')->name('reports.index');
        Route::get('reports/students', 'App\Http\Controllers\Admin\ReportController@students')->name('reports.students');
        Route::get('reports/teachers', 'App\Http\Controllers\Admin\ReportController@teachers')->name('reports.teachers');
        Route::get('reports/attendance', 'App\Http\Controllers\Admin\ReportController@attendance')->name('reports.attendance');
        Route::get('reports/grades', 'App\Http\Controllers\Admin\ReportController@grades')->name('reports.grades');
    });
    
    // Teacher Dashboard
    Route::middleware(['user.type:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TCDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [TCDashboardController::class, 'getStats'])->name('teacher.dashboard.stats');
        
        // Teacher specific routes
        Route::get('/classes', 'App\Http\Controllers\TC\ClassController@index')->name('classes');
        Route::get('/classes/{class}', 'App\Http\Controllers\TC\ClassController@show')->name('classes.show');
        Route::get('/students', 'App\Http\Controllers\TC\StudentController@index')->name('students');
        Route::get('/students/{student}', 'App\Http\Controllers\TC\StudentController@show')->name('students.show');
        Route::get('/subjects', 'App\Http\Controllers\TC\SubjectController@index')->name('subjects');
        Route::get('/subjects/{subject}', 'App\Http\Controllers\TC\SubjectController@show')->name('subjects.show');
        
        // Attendance
        Route::get('/attendance', 'App\Http\Controllers\TC\AttendanceController@index')->name('attendance');
        Route::get('/attendance/create', 'App\Http\Controllers\TC\AttendanceController@create')->name('attendance.create');
        Route::post('/attendance', 'App\Http\Controllers\TC\AttendanceController@store')->name('attendance.store');
        Route::get('/attendance/{class}/date/{date}', 'App\Http\Controllers\TC\AttendanceController@show')->name('attendance.show');
        
        // Grades
        Route::get('/grades', 'App\Http\Controllers\TC\GradeController@index')->name('grades');
        Route::get('/grades/create', 'App\Http\Controllers\TC\GradeController@create')->name('grades.create');
        Route::post('/grades', 'App\Http\Controllers\TC\GradeController@store')->name('grades.store');
        Route::get('/grades/{grade}', 'App\Http\Controllers\TC\GradeController@show')->name('grades.show');
        
        // Profile
        Route::get('/profile', 'App\Http\Controllers\TC\ProfileController@show')->name('profile');
        Route::get('/profile/edit', 'App\Http\Controllers\TC\ProfileController@edit')->name('profile.edit');
        Route::put('/profile', 'App\Http\Controllers\TC\ProfileController@update')->name('profile.update');
        
        // Schedule
        Route::get('/schedule', 'App\Http\Controllers\TC\ScheduleController@index')->name('schedule');
    });
    
    // Student Dashboard
    Route::middleware(['user.type:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', 'App\Http\Controllers\Student\DashboardController@index')->name('dashboard');
        Route::get('/profile', 'App\Http\Controllers\Student\ProfileController@show')->name('profile');
        Route::get('/attendance', 'App\Http\Controllers\Student\AttendanceController@index')->name('attendance');
        Route::get('/grades', 'App\Http\Controllers\Student\GradeController@index')->name('grades');
        Route::get('/subjects', 'App\Http\Controllers\Student\SubjectController@index')->name('subjects');
    });
    
    // Parent Dashboard
    Route::middleware(['user.type:parent'])->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', 'App\Http\Controllers\Parent\DashboardController@index')->name('dashboard');
        Route::get('/children', 'App\Http\Controllers\Parent\ChildController@index')->name('children');
        Route::get('/children/{student}', 'App\Http\Controllers\Parent\ChildController@show')->name('children.show');
        Route::get('/attendance', 'App\Http\Controllers\Parent\AttendanceController@index')->name('attendance');
        Route::get('/grades', 'App\Http\Controllers\Parent\GradeController@index')->name('grades');
    });
    
    // Common Dashboard Route (redirects based on user type)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        } elseif ($user->isParent()) {
            return redirect()->route('parent.dashboard');
        }
        
        return redirect()->route('login');
    })->name('dashboard');
});

// AJAX Routes for all user types
Route::middleware(['auth', 'school.context'])->prefix('ajax')->name('ajax.')->group(function () {
    // Common AJAX routes using our new CommonController
    Route::get('/schools', 'App\Http\Controllers\Ajax\CommonController@getSchools')->name('schools');
    Route::get('/users', 'App\Http\Controllers\Ajax\CommonController@getUsers')->name('users');
    Route::get('/students', 'App\Http\Controllers\Ajax\CommonController@getStudents')->name('students');
    Route::get('/teachers', 'App\Http\Controllers\Ajax\CommonController@getTeachers')->name('teachers');
    Route::get('/classes', 'App\Http\Controllers\Ajax\CommonController@getClasses')->name('classes');
    Route::get('/subjects', 'App\Http\Controllers\Ajax\CommonController@getSubjects')->name('subjects');
    Route::post('/upload-file', 'App\Http\Controllers\Ajax\CommonController@uploadFile')->name('upload-file');
    Route::delete('/delete-file', 'App\Http\Controllers\Ajax\CommonController@deleteFile')->name('delete-file');
    Route::get('/notifications', 'App\Http\Controllers\Ajax\CommonController@getNotifications')->name('notifications');
    Route::get('/notifications/count', 'App\Http\Controllers\Ajax\CommonController@getNotificationCount')->name('notifications.count');
    Route::post('/notifications/{notification}/read', 'App\Http\Controllers\Ajax\CommonController@markNotificationRead')->name('notifications.read');
    Route::post('/notifications/mark-all-read', 'App\Http\Controllers\Ajax\CommonController@markAllNotificationsRead')->name('notifications.mark-all-read');
    Route::get('/widget-data', 'App\Http\Controllers\Ajax\CommonController@getWidgetData')->name('widget-data');
    
    // Legacy routes (to be updated)
    Route::post('/upload-image', 'App\Http\Controllers\Ajax\UploadController@uploadImage')->name('upload-image');
    Route::get('/search-users', 'App\Http\Controllers\Ajax\SearchController@searchUsers')->name('search-users');
    
    // Admin AJAX routes
    Route::middleware(['user.type:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/schools/select', 'App\Http\Controllers\Ajax\Admin\SchoolController@select')->name('schools.select');
        Route::get('/teachers/select', 'App\Http\Controllers\Ajax\Admin\TeacherController@select')->name('teachers.select');
        Route::get('/students/select', 'App\Http\Controllers\Ajax\Admin\StudentController@select')->name('students.select');
        Route::get('/classes/select', 'App\Http\Controllers\Ajax\Admin\ClassController@select')->name('classes.select');
        Route::get('/subjects/select', 'App\Http\Controllers\Ajax\Admin\SubjectController@select')->name('subjects.select');
    });
    
    // Teacher AJAX routes
    Route::middleware(['user.type:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::post('/attendance/mark', 'App\Http\Controllers\Ajax\TC\AttendanceController@mark')->name('attendance.mark');
        Route::post('/grades/save', 'App\Http\Controllers\Ajax\TC\GradeController@save')->name('grades.save');
    });
});
