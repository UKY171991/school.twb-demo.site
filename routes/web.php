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
    Route::resource('schools', \App\Http\Controllers\SuperAdmin\SchoolController::class);
    Route::resource('users', \App\Http\Controllers\SuperAdmin\UserController::class);
});

// School Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);
    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
    Route::resource('classes', \App\Http\Controllers\Admin\ClassController::class);
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
    Route::resource('fees', \App\Http\Controllers\Admin\FeeController::class);
    Route::resource('exams', \App\Http\Controllers\Admin\ExamController::class);
    Route::resource('library', \App\Http\Controllers\Admin\LibraryController::class);
    
    // Attendance Routes
    Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [\App\Http\Controllers\Admin\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'store'])->name('attendance.store');
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
