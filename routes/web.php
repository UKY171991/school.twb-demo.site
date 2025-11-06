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
        Route::get('/dashboard', 'App\Http\Controllers\SuperAdmin\DashboardController@index')->name('dashboard');
        
        // Dashboard AJAX endpoints
        Route::get('/dashboard/enrollment-trends', 'App\Http\Controllers\SuperAdmin\DashboardController@getEnrollmentTrends')->name('dashboard.enrollment-trends');
        Route::get('/dashboard/user-activity', 'App\Http\Controllers\SuperAdmin\DashboardController@getUserActivity')->name('dashboard.user-activity');
        Route::get('/dashboard/school-performance', 'App\Http\Controllers\SuperAdmin\DashboardController@getSchoolPerformance')->name('dashboard.school-performance');
        Route::get('/dashboard/system-health', 'App\Http\Controllers\SuperAdmin\DashboardController@getSystemHealth')->name('dashboard.system-health');
        Route::get('/dashboard/recent-activities', 'App\Http\Controllers\SuperAdmin\DashboardController@getRecentActivities')->name('dashboard.recent-activities');
        
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
        Route::resource('schools', 'App\Http\Controllers\SuperAdmin\SchoolController');
        Route::post('schools/{school}/toggle-status', 'App\Http\Controllers\SuperAdmin\SchoolController@toggleStatus')->name('schools.toggle-status');
        
        // Super Admin reporting system
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', 'App\Http\Controllers\SuperAdmin\ReportController@index')->name('index');
            Route::get('/system-overview', 'App\Http\Controllers\SuperAdmin\ReportController@getSystemOverview')->name('system-overview');
            Route::get('/school-performance', 'App\Http\Controllers\SuperAdmin\ReportController@getSchoolPerformance')->name('school-performance');
            Route::get('/user-analytics', 'App\Http\Controllers\SuperAdmin\ReportController@getUserAnalytics')->name('user-analytics');
            Route::get('/enrollment-trends', 'App\Http\Controllers\SuperAdmin\ReportController@getEnrollmentTrends')->name('enrollment-trends');
            
            // Export routes
            Route::get('/export/system-overview', 'App\Http\Controllers\SuperAdmin\ReportController@exportSystemOverview')->name('export.system-overview');
            Route::get('/export/school-performance', 'App\Http\Controllers\SuperAdmin\ReportController@exportSchoolPerformance')->name('export.school-performance');
            Route::get('/export/user-analytics', 'App\Http\Controllers\SuperAdmin\ReportController@exportUserAnalytics')->name('export.user-analytics');
            
            // Scheduled reports
            Route::post('/schedule', 'App\Http\Controllers\SuperAdmin\ReportController@scheduleReport')->name('schedule');
            Route::get('/scheduled', 'App\Http\Controllers\SuperAdmin\ReportController@getScheduledReports')->name('scheduled');
            Route::put('/scheduled/{schedule}', 'App\Http\Controllers\SuperAdmin\ReportController@updateScheduledReport')->name('scheduled.update');
            Route::delete('/scheduled/{schedule}', 'App\Http\Controllers\SuperAdmin\ReportController@deleteScheduledReport')->name('scheduled.delete');
        });
        
        // Route::resource('users', 'App\Http\Controllers\SuperAdmin\UserController'); // To be implemented in future tasks
    });

    // Admin Dashboard (School-specific)
    Route::middleware(['role:admin', 'school.active'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::get('/dashboard/enrollment-trends', [AdminDashboardController::class, 'getEnrollmentTrends'])->name('dashboard.enrollment-trends');
        Route::get('/dashboard/class-performance', [AdminDashboardController::class, 'getClassPerformance'])->name('dashboard.class-performance');
        Route::get('/dashboard/teacher-workload', [AdminDashboardController::class, 'getTeacherWorkload'])->name('dashboard.teacher-workload');
        Route::post('/dashboard/save-widgets', [AdminDashboardController::class, 'saveWidgetConfiguration'])->name('dashboard.save-widgets');
        Route::get('/dashboard/pending-payments', [AdminDashboardController::class, 'getPendingPayments'])->name('pending-payments');
        Route::get('/dashboard/latest-notifications', [AdminDashboardController::class, 'getLatestNotifications'])->name('latest-notifications');
        Route::get('/dashboard/activity-log', [AdminDashboardController::class, 'getActivityLogData'])->name('activity-log');

        
        // Schools Management
        Route::resource('schools', 'App\Http\Controllers\Admin\SchoolController');
        Route::post('schools/{school}/toggle-status', 'App\Http\Controllers\Admin\SchoolController@toggleStatus')->name('schools.toggle-status');
        
        // Teachers Management
        Route::resource('teachers', 'App\Http\Controllers\Admin\TeacherController');
        Route::get('teachers-data', 'App\Http\Controllers\Admin\TeacherController@getData')->name('teachers.data');
        Route::post('teachers/{teacher}/toggle-status', 'App\Http\Controllers\Admin\TeacherController@toggleStatus')->name('teachers.toggle-status');
        Route::post('teachers/bulk-action', 'App\Http\Controllers\Admin\TeacherController@bulkAction')->name('teachers.bulk-action');
        Route::get('teachers/{teacher}/performance', 'App\Http\Controllers\Admin\TeacherController@getPerformanceData')->name('teachers.performance');
        Route::post('teachers/check-schedule-conflicts', 'App\Http\Controllers\Admin\TeacherController@checkScheduleConflicts')->name('teachers.check-conflicts');
        
        // Students Management
        Route::resource('students', 'App\Http\Controllers\Admin\StudentController');
        Route::get('students-data', 'App\Http\Controllers\Admin\StudentController@getData')->name('students.data');
        Route::post('students/{student}/toggle-status', 'App\Http\Controllers\Admin\StudentController@toggleStatus')->name('students.toggle-status');
        Route::post('students/bulk-action', 'App\Http\Controllers\Admin\StudentController@bulkAction')->name('students.bulk-action');
        
        // Classes Management
        Route::resource('classes', 'App\Http\Controllers\Admin\ClassController');
        Route::get('classes-data', 'App\Http\Controllers\Admin\ClassController@getData')->name('classes.data');
        Route::post('classes/{class}/toggle-status', 'App\Http\Controllers\Admin\ClassController@toggleStatus')->name('classes.toggle-status');
        Route::post('classes/{class}/enroll-students', 'App\Http\Controllers\Admin\ClassController@enrollStudents')->name('classes.enroll-students');
        Route::post('classes/{class}/remove-students', 'App\Http\Controllers\Admin\ClassController@removeStudents')->name('classes.remove-students');
        Route::get('classes/{class}/performance', 'App\Http\Controllers\Admin\ClassController@getPerformanceData')->name('classes.performance');
        Route::post('classes/bulk-action', 'App\Http\Controllers\Admin\ClassController@bulkAction')->name('classes.bulk-action');
        
        // Subjects Management
        Route::resource('subjects', 'App\Http\Controllers\Admin\SubjectController');
        Route::get('subjects-data', 'App\Http\Controllers\Admin\SubjectController@getData')->name('subjects.data');
        Route::post('subjects/{subject}/toggle-status', 'App\Http\Controllers\Admin\SubjectController@toggleStatus')->name('subjects.toggle-status');
        Route::post('subjects/bulk-action', 'App\Http\Controllers\Admin\SubjectController@bulkAction')->name('subjects.bulk-action');
        
        // Class Scheduling
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\ScheduleController@index')->name('index');
            Route::get('/create', 'App\Http\Controllers\Admin\ScheduleController@create')->name('create');
            Route::post('/', 'App\Http\Controllers\Admin\ScheduleController@store')->name('store');
            Route::get('/{schedule}', 'App\Http\Controllers\Admin\ScheduleController@show')->name('show');
            Route::get('/{schedule}/edit', 'App\Http\Controllers\Admin\ScheduleController@edit')->name('edit');
            Route::put('/{schedule}', 'App\Http\Controllers\Admin\ScheduleController@update')->name('update');
            Route::delete('/{schedule}', 'App\Http\Controllers\Admin\ScheduleController@destroy')->name('destroy');
            Route::post('/check-conflicts', 'App\Http\Controllers\Admin\ScheduleController@checkConflicts')->name('check-conflicts');
            Route::get('/class/{class}', 'App\Http\Controllers\Admin\ScheduleController@getClassSchedule')->name('class');
        });
        
        // Academic Year and Semester Management
        Route::prefix('academic')->name('academic.')->group(function () {
            Route::get('/years', 'App\Http\Controllers\Admin\AcademicYearController@index')->name('years.index');
            Route::post('/years', 'App\Http\Controllers\Admin\AcademicYearController@store')->name('years.store');
            Route::get('/years/{year}', 'App\Http\Controllers\Admin\AcademicYearController@show')->name('years.show');
            Route::put('/years/{year}', 'App\Http\Controllers\Admin\AcademicYearController@update')->name('years.update');
            Route::delete('/years/{year}', 'App\Http\Controllers\Admin\AcademicYearController@destroy')->name('years.destroy');
            Route::post('/years/{year}/activate', 'App\Http\Controllers\Admin\AcademicYearController@activate')->name('years.activate');
            Route::post('/years/{year}/progress', 'App\Http\Controllers\Admin\AcademicYearController@progressSemester')->name('years.progress');
        });
        
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
        Route::get('reports/class-performance', 'App\Http\Controllers\Admin\ReportController@classPerformance')->name('reports.class-performance');
        Route::post('reports/send-progress-reports', 'App\Http\Controllers\Admin\ReportController@sendProgressReports')->name('reports.send-progress-reports');
    });
    
    // Teacher Dashboard
    Route::middleware(['user.type:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TCDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [TCDashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::post('/dashboard/quick-attendance', [TCDashboardController::class, 'quickAttendance'])->name('dashboard.quick-attendance');
        
        // Teacher specific routes
        Route::get('/classes', 'App\Http\Controllers\TC\ClassController@index')->name('classes');
        Route::get('/classes/{class}', 'App\Http\Controllers\TC\ClassController@show')->name('classes.show');
        Route::get('/classes/{class}/roster', 'App\Http\Controllers\TC\ClassController@getRoster')->name('classes.roster');
        Route::post('/classes/{class}/message', 'App\Http\Controllers\TC\ClassController@sendMessage')->name('classes.message');
        Route::get('/classes/{class}/performance', 'App\Http\Controllers\TC\ClassController@getPerformanceAnalytics')->name('classes.performance');
        Route::post('/classes/{class}/lesson-plan', 'App\Http\Controllers\TC\ClassController@createLessonPlan')->name('classes.lesson-plan');
        Route::get('/classes/{class}/lesson-plans', 'App\Http\Controllers\TC\ClassController@getLessonPlans')->name('classes.lesson-plans');
        
        // Teacher Reports
        Route::get('/reports', 'App\Http\Controllers\TC\ReportController@index')->name('reports');
        Route::get('/reports/class-performance', 'App\Http\Controllers\TC\ReportController@classPerformance')->name('reports.class-performance');
        Route::get('/reports/parent-communication', 'App\Http\Controllers\TC\ReportController@parentCommunication')->name('reports.parent-communication');
        Route::get('/reports/teaching-effectiveness', 'App\Http\Controllers\TC\ReportController@teachingEffectiveness')->name('reports.teaching-effectiveness');
        Route::post('/reports/export', 'App\Http\Controllers\TC\ReportController@export')->name('reports.export');
        
        Route::get('/students', 'App\Http\Controllers\TC\StudentController@index')->name('students');
        Route::get('/students/{student}', 'App\Http\Controllers\TC\StudentController@show')->name('students.show');
        Route::get('/subjects', 'App\Http\Controllers\TC\SubjectController@index')->name('subjects');
        Route::get('/subjects/{subject}', 'App\Http\Controllers\TC\SubjectController@show')->name('subjects.show');
        
        // Attendance
        Route::get('/attendance', 'App\Http\Controllers\TC\AttendanceController@index')->name('attendance.index');
        Route::get('/attendance/create', 'App\Http\Controllers\TC\AttendanceController@create')->name('attendance.create');
        Route::post('/attendance', 'App\Http\Controllers\TC\AttendanceController@store')->name('attendance.store');
        Route::get('/attendance/{class}', 'App\Http\Controllers\TC\AttendanceController@show')->name('attendance.show');
        Route::get('/attendance/data', 'App\Http\Controllers\TC\AttendanceController@getAttendanceData')->name('attendance.data');
        Route::get('/attendance/students', 'App\Http\Controllers\TC\AttendanceController@getStudentsForAttendance')->name('attendance.students');
        Route::get('/attendance/by-date', 'App\Http\Controllers\TC\AttendanceController@getAttendanceByDateForView')->name('attendance.by-date');
        Route::post('/attendance/report', 'App\Http\Controllers\TC\AttendanceController@generateReport')->name('attendance.report');
        Route::get('/attendance/analytics', 'App\Http\Controllers\TC\AttendanceController@getAnalytics')->name('attendance.analytics');
        Route::get('/attendance/reports', 'App\Http\Controllers\TC\AttendanceController@reports')->name('attendance.reports');
        
        // Grades
        Route::get('/grades', 'App\Http\Controllers\TC\GradeController@index')->name('grades');
        Route::get('/grades/create', 'App\Http\Controllers\TC\GradeController@create')->name('grades.create');
        Route::post('/grades', 'App\Http\Controllers\TC\GradeController@store')->name('grades.store');
        Route::get('/grades/{grade}', 'App\Http\Controllers\TC\GradeController@show')->name('grades.show');
        Route::get('/grades/{grade}/edit', 'App\Http\Controllers\TC\GradeController@edit')->name('grades.edit');
        Route::put('/grades/{grade}', 'App\Http\Controllers\TC\GradeController@update')->name('grades.update');
        Route::delete('/grades/{grade}', 'App\Http\Controllers\TC\GradeController@destroy')->name('grades.destroy');
        Route::get('/grades/data', 'App\Http\Controllers\TC\GradeController@getGradeData')->name('grades.data');
        Route::get('/grades/students', 'App\Http\Controllers\TC\GradeController@getStudentsForGrading')->name('grades.students');
        Route::post('/grades/bulk-store', 'App\Http\Controllers\TC\GradeController@bulkStore')->name('grades.bulk-store');
        Route::get('/grades/analytics', 'App\Http\Controllers\TC\GradeController@getAnalytics')->name('grades.analytics');
        
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
        
        // Academic Progress Routes
        Route::get('/academic', 'App\Http\Controllers\Student\AcademicController@index')->name('academic.index');
        Route::get('/academic/grades', 'App\Http\Controllers\Student\AcademicController@grades')->name('academic.grades');
        Route::get('/academic/attendance', 'App\Http\Controllers\Student\AcademicController@attendance')->name('academic.attendance');
        Route::get('/academic/progress-reports', 'App\Http\Controllers\Student\AcademicController@progressReports')->name('academic.progress-reports');
        Route::get('/academic/schedule', 'App\Http\Controllers\Student\AcademicController@schedule')->name('academic.schedule');
        Route::get('/academic/assignments', 'App\Http\Controllers\Student\AcademicController@assignments')->name('academic.assignments');
        Route::get('/academic/export-grades', 'App\Http\Controllers\Student\AcademicController@exportGrades')->name('academic.export-grades');
        
        // Communication Routes
        Route::get('/communication', 'App\Http\Controllers\Student\CommunicationController@index')->name('communication.index');
        Route::get('/communication/messages', 'App\Http\Controllers\Student\CommunicationController@messages')->name('communication.messages');
        Route::get('/communication/messages/{message}', 'App\Http\Controllers\Student\CommunicationController@showMessage')->name('communication.message');
        Route::post('/communication/send-message', 'App\Http\Controllers\Student\CommunicationController@sendMessage')->name('communication.send-message');
        Route::post('/communication/messages/{message}/reply', 'App\Http\Controllers\Student\CommunicationController@replyMessage')->name('communication.reply-message');
        Route::get('/communication/announcements', 'App\Http\Controllers\Student\CommunicationController@announcements')->name('communication.announcements');
        Route::get('/communication/announcements/{announcement}', 'App\Http\Controllers\Student\CommunicationController@showAnnouncement')->name('communication.announcement');
        Route::get('/communication/feedback', 'App\Http\Controllers\Student\CommunicationController@feedback')->name('communication.feedback');
        Route::post('/communication/submit-feedback', 'App\Http\Controllers\Student\CommunicationController@submitFeedback')->name('communication.submit-feedback');
        
        Route::get('/profile', 'App\Http\Controllers\Student\ProfileController@show')->name('profile.show');
        Route::get('/profile/edit', 'App\Http\Controllers\Student\ProfileController@edit')->name('profile.edit');
        Route::put('/profile', 'App\Http\Controllers\Student\ProfileController@update')->name('profile.update');
        Route::post('/profile/password', 'App\Http\Controllers\Student\ProfileController@updatePassword')->name('profile.update-password');
        Route::post('/profile/preferences', 'App\Http\Controllers\Student\ProfileController@updatePreferences')->name('profile.update-preferences');
        Route::get('/profile/academic-records', 'App\Http\Controllers\Student\ProfileController@academicRecords')->name('profile.academic-records');
        Route::get('/attendance', 'App\Http\Controllers\Student\AttendanceController@index')->name('attendance');
        Route::get('/grades', 'App\Http\Controllers\Student\GradeController@index')->name('grades');
        Route::get('/subjects', 'App\Http\Controllers\Student\SubjectController@index')->name('subjects');
    });
    
    // Parent Dashboard
    Route::middleware(['user.type:parent'])->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', 'App\Http\Controllers\Parent\DashboardController@index')->name('dashboard');
        Route::get('/children', 'App\Http\Controllers\Parent\ChildController@index')->name('children');
        Route::get('/children/{student}', 'App\Http\Controllers\Parent\ChildController@show')->name('children.show');
        Route::get('/children/{student}/attendance-analysis', 'App\Http\Controllers\Parent\ChildController@attendanceAnalysis')->name('children.attendance-analysis');
        Route::get('/children/{student}/grade-tracking', 'App\Http\Controllers\Parent\ChildController@gradeTracking')->name('children.grade-tracking');
        Route::get('/children/{student}/performance-trends', 'App\Http\Controllers\Parent\ChildController@performanceTrends')->name('children.performance-trends');
        Route::get('/children/{student}/attendance-data', 'App\Http\Controllers\Parent\ChildController@getChildAttendance')->name('children.attendance-data');
        Route::get('/children/{student}/grade-data', 'App\Http\Controllers\Parent\ChildController@getChildGrades')->name('children.grade-data');
        Route::get('/attendance', 'App\Http\Controllers\Parent\AttendanceController@index')->name('attendance');
        Route::get('/grades', 'App\Http\Controllers\Parent\GradeController@index')->name('grades');
        
        // Communication Routes
        Route::get('/communication', 'App\Http\Controllers\Parent\CommunicationController@index')->name('communication.index');
        Route::get('/communication/messages', 'App\Http\Controllers\Parent\CommunicationController@messages')->name('communication.messages');
        Route::post('/communication/send-message', 'App\Http\Controllers\Parent\CommunicationController@sendMessage')->name('communication.send-message');
        Route::get('/communication/meetings', 'App\Http\Controllers\Parent\CommunicationController@meetings')->name('communication.meetings');
        Route::post('/communication/request-meeting', 'App\Http\Controllers\Parent\CommunicationController@requestMeeting')->name('communication.request-meeting');
        Route::get('/communication/download-attachment/{message}', 'App\Http\Controllers\Parent\CommunicationController@downloadAttachment')->name('communication.download-attachment');
        Route::post('/communication/mark-as-read', 'App\Http\Controllers\Parent\CommunicationController@markAsRead')->name('communication.mark-as-read');
        
        // Family Management Routes
        Route::get('/family', 'App\Http\Controllers\Parent\FamilyController@index')->name('family.index');
        Route::get('/family/profile', 'App\Http\Controllers\Parent\FamilyController@profile')->name('family.profile');
        Route::post('/family/profile', 'App\Http\Controllers\Parent\FamilyController@updateProfile')->name('family.update-profile');
        Route::get('/family/permissions', 'App\Http\Controllers\Parent\FamilyController@permissions')->name('family.permissions');
        Route::put('/family/permissions/{permission}', 'App\Http\Controllers\Parent\FamilyController@updatePermission')->name('family.update-permission');
        Route::get('/family/emergency-contacts', 'App\Http\Controllers\Parent\FamilyController@emergencyContacts')->name('family.emergency-contacts');
        Route::post('/family/emergency-contacts', 'App\Http\Controllers\Parent\FamilyController@storeEmergencyContact')->name('family.store-emergency-contact');
        Route::put('/family/emergency-contacts/{contact}', 'App\Http\Controllers\Parent\FamilyController@updateEmergencyContact')->name('family.update-emergency-contact');
        Route::delete('/family/emergency-contacts/{contact}', 'App\Http\Controllers\Parent\FamilyController@deleteEmergencyContact')->name('family.delete-emergency-contact');
        Route::get('/family/preferences', 'App\Http\Controllers\Parent\FamilyController@preferences')->name('family.preferences');
        Route::post('/family/preferences', 'App\Http\Controllers\Parent\FamilyController@updatePreferences')->name('family.update-preferences');
        Route::get('/family/children-overview', 'App\Http\Controllers\Parent\FamilyController@childrenOverview')->name('family.children-overview');
    });
    
    // Common Dashboard Route (redirects based on user type)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->isAdmin()) {
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
        Route::post('/attendance/update', 'App\Http\Controllers\Ajax\TC\AttendanceController@update')->name('attendance.update');
        Route::get('/classes', 'App\Http\Controllers\Ajax\TC\ClassController@index')->name('classes');
        Route::post('/grades/save', 'App\Http\Controllers\Ajax\TC\GradeController@save')->name('grades.save');
    });
});
