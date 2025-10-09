<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Super Admin Routes
Route::middleware(['auth', 'role:super-admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    
    // Core Management
    Route::resource('schools', \App\Http\Controllers\SuperAdmin\SchoolController::class);
    Route::resource('users', \App\Http\Controllers\SuperAdmin\UserController::class);
    Route::resource('roles', \App\Http\Controllers\SuperAdmin\RoleController::class);
    
    // System Management
    Route::get('/settings', [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'update'])->name('settings.update');
    
    // Backup & Restore
    Route::get('/backup', [\App\Http\Controllers\SuperAdmin\BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [\App\Http\Controllers\SuperAdmin\BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/download/{file}', [\App\Http\Controllers\SuperAdmin\BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{file}', [\App\Http\Controllers\SuperAdmin\BackupController::class, 'destroy'])->name('backup.destroy');
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/schools', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'schools'])->name('reports.schools');
    Route::get('/reports/users', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'users'])->name('reports.users');
    
    // Activity Logs
    Route::get('/logs', [\App\Http\Controllers\SuperAdmin\ActivityLogController::class, 'index'])->name('logs.index');
    Route::delete('/logs/clear', [\App\Http\Controllers\SuperAdmin\ActivityLogController::class, 'clear'])->name('logs.clear');
});

// School Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Theme & Language
    Route::get('/theme', function() { return view('admin.theme.index'); })->name('theme.index');
    Route::get('/language', function() { return view('admin.language.index'); })->name('language.index');
    
    // Administrator
    Route::get('/users', function() { return view('admin.users.index'); })->name('users.index');
    Route::get('/roles', function() { return view('admin.roles.index'); })->name('roles.index');
    Route::get('/permissions', function() { return view('admin.permissions.index'); })->name('permissions.index');
    
    // Template
    Route::get('/templates/email', function() { return view('admin.templates.email'); })->name('templates.email');
    Route::get('/templates/sms', function() { return view('admin.templates.sms'); })->name('templates.sms');
    
    // Front Office
    Route::get('/front-office/visitors', function() { return view('admin.front-office.visitors'); })->name('front-office.visitors');
    Route::get('/front-office/calls', function() { return view('admin.front-office.calls'); })->name('front-office.calls');
    Route::get('/front-office/postal', function() { return view('admin.front-office.postal'); })->name('front-office.postal');
    
    // Human Resource
    Route::get('/human-resource', function() { return view('admin.human-resource.index'); })->name('human-resource.index');
    Route::get('/human-resource/departments', function() { return view('admin.human-resource.departments'); })->name('human-resource.departments');
    Route::get('/human-resource/designations', function() { return view('admin.human-resource.designations'); })->name('human-resource.designations');
    
    // Manage Leave
    Route::get('/leaves', function() { return view('admin.leaves.index'); })->name('leaves.index');
    Route::get('/leaves/types', function() { return view('admin.leaves.types'); })->name('leaves.types');
    
    // Core Academic Management
    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
    Route::get('/class-lectures', function() { return view('admin.class-lectures.index'); })->name('class-lectures.index');
    Route::get('/live-classes', function() { return view('admin.live-classes.index'); })->name('live-classes.index');
    Route::resource('classes', \App\Http\Controllers\Admin\ClassController::class);
    Route::resource('sections', \App\Http\Controllers\Admin\SectionController::class);
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
    Route::resource('syllabus', \App\Http\Controllers\Admin\SyllabusController::class);
    Route::get('/study-materials', function() { return view('admin.study-materials.index'); })->name('study-materials.index');
    Route::resource('class-routines', \App\Http\Controllers\Admin\ClassRoutineController::class);
    Route::resource('guardians', \App\Http\Controllers\Admin\GuardianController::class);
    
    // Manage Exam
    Route::resource('exams', \App\Http\Controllers\Admin\ExamController::class);
    Route::get('/exam-schedules', function() { return view('admin.exam-schedules.index'); })->name('exam-schedules.index');
    Route::get('/exam-attendance', function() { return view('admin.exam-attendance.index'); })->name('exam-attendance.index');
    Route::get('/exam-results', function() { return view('admin.exam-results.index'); })->name('exam-results.index');
    
    // Promotion & Certificate
    Route::get('/promotion', function() { return view('admin.promotion.index'); })->name('promotion.index');
    Route::get('/certificates', function() { return view('admin.certificates.index'); })->name('certificates.index');
    
    // Library
    Route::get('/library-books', function() { return view('admin.library-books.index'); })->name('library-books.index');
    Route::get('/book-issues', function() { return view('admin.book-issues.index'); })->name('book-issues.index');
    
    // Transport
    Route::get('/transport/vehicles', function() { return view('admin.transport.vehicles'); })->name('transport.vehicles');
    Route::get('/transport/routes', function() { return view('admin.transport.routes'); })->name('transport.routes');
    
    // Hostel
    Route::get('/hostel/rooms', function() { return view('admin.hostel.rooms'); })->name('hostel.rooms');
    Route::get('/hostel/members', function() { return view('admin.hostel.members'); })->name('hostel.members');
    
    // Communication
    Route::get('/messages', function() { return view('admin.messages.index'); })->name('messages.index');
    Route::get('/mail-sms', function() { return view('admin.mail-sms.index'); })->name('mail-sms.index');
    Route::get('/complains', function() { return view('admin.complains.index'); })->name('complains.index');
    Route::get('/announcements', function() { return view('admin.announcements.index'); })->name('announcements.index');
    Route::get('/events', function() { return view('admin.events.index'); })->name('events.index');
    
    // Financial
    Route::get('/payroll', function() { return view('admin.payroll.index'); })->name('payroll.index');
    Route::get('/accounting/income', function() { return view('admin.accounting.income'); })->name('accounting.income');
    Route::get('/accounting/expense', function() { return view('admin.accounting.expense'); })->name('accounting.expense');
    
    // Reports
    Route::get('/reports/students', function() { return view('admin.reports.students'); })->name('reports.students');
    Route::get('/reports/attendance', function() { return view('admin.reports.attendance'); })->name('reports.attendance');
    Route::get('/reports/financial', function() { return view('admin.reports.financial'); })->name('reports.financial');
    
    // Media & Frontend
    Route::get('/media-gallery', function() { return view('admin.media-gallery.index'); })->name('media-gallery.index');
    Route::get('/frontend/pages', function() { return view('admin.frontend.pages'); })->name('frontend.pages');
    Route::get('/frontend/menus', function() { return view('admin.frontend.menus'); })->name('frontend.menus');
});

// Teacher Routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
});

// Guardian Routes
Route::middleware(['auth', 'role:guardian'])->prefix('guardian')->name('guardian.')->group(function () {
    Route::get('/dashboard', function () {
        return view('guardian.dashboard');
    })->name('dashboard');
});

// Accountant Routes
Route::middleware(['auth', 'role:accountant'])->prefix('accountant')->name('accountant.')->group(function () {
    Route::get('/dashboard', function () {
        return view('accountant.dashboard');
    })->name('dashboard');
});

// Librarian Routes
Route::middleware(['auth', 'role:librarian'])->prefix('librarian')->name('librarian.')->group(function () {
    Route::get('/dashboard', function () {
        return view('librarian.dashboard');
    })->name('dashboard');
});

// Receptionist Routes
Route::middleware(['auth', 'role:receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
    Route::get('/dashboard', function () {
        return view('receptionist.dashboard');
    })->name('dashboard');
});

// Staff Routes
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
