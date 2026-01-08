<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    } elseif ($user->role === 'student') {
        return redirect()->route('student.dashboard');
    }
    
    // Fallback for users without specific roles
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    // Students
    Route::get('/students', [App\Http\Controllers\AdminController::class, 'students'])->name('students');
    Route::get('/students/create', [App\Http\Controllers\AdminController::class, 'createStudent'])->name('students.create');
    Route::post('/students', [App\Http\Controllers\AdminController::class, 'storeStudent'])->name('students.store');
    Route::get('/students/{student}/edit', [App\Http\Controllers\AdminController::class, 'editStudent'])->name('students.edit');
    Route::put('/students/{student}', [App\Http\Controllers\AdminController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{student}', [App\Http\Controllers\AdminController::class, 'destroyStudent'])->name('students.destroy');

    // Teachers
    Route::get('/teachers', [App\Http\Controllers\AdminController::class, 'teachers'])->name('teachers');
    Route::get('/teachers/create', [App\Http\Controllers\AdminController::class, 'createTeacher'])->name('teachers.create');
    Route::post('/teachers', [App\Http\Controllers\AdminController::class, 'storeTeacher'])->name('teachers.store');
    Route::get('/teachers/{teacher}/edit', [App\Http\Controllers\AdminController::class, 'editTeacher'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [App\Http\Controllers\AdminController::class, 'updateTeacher'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [App\Http\Controllers\AdminController::class, 'destroyTeacher'])->name('teachers.destroy');

    // Subjects
    Route::get('/subjects', [App\Http\Controllers\AdminController::class, 'subjects'])->name('subjects');
    Route::get('/subjects/create', [App\Http\Controllers\AdminController::class, 'createSubject'])->name('subjects.create');
    Route::post('/subjects', [App\Http\Controllers\AdminController::class, 'storeSubject'])->name('subjects.store');
    Route::get('/subjects/{subject}/edit', [App\Http\Controllers\AdminController::class, 'editSubject'])->name('subjects.edit');
    Route::put('/subjects/{subject}', [App\Http\Controllers\AdminController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [App\Http\Controllers\AdminController::class, 'destroySubject'])->name('subjects.destroy');

    // Classrooms
    Route::get('/classrooms', [App\Http\Controllers\AdminController::class, 'classrooms'])->name('classrooms');
    Route::get('/classrooms/create', [App\Http\Controllers\AdminController::class, 'createClassroom'])->name('classrooms.create');
    Route::post('/classrooms', [App\Http\Controllers\AdminController::class, 'storeClassroom'])->name('classrooms.store');
    Route::get('/classrooms/{classroom}/edit', [App\Http\Controllers\AdminController::class, 'editClassroom'])->name('classrooms.edit');
    Route::put('/classrooms/{classroom}', [App\Http\Controllers\AdminController::class, 'updateClassroom'])->name('classrooms.update');
    Route::delete('/classrooms/{classroom}', [App\Http\Controllers\AdminController::class, 'destroyClassroom'])->name('classrooms.destroy');

    // Grades
    Route::get('/grades', [App\Http\Controllers\AdminController::class, 'grades'])->name('grades');
    Route::get('/grades/create', [App\Http\Controllers\AdminController::class, 'createGrade'])->name('grades.create');
    Route::post('/grades', [App\Http\Controllers\AdminController::class, 'storeGrade'])->name('grades.store');
    Route::get('/grades/{grade}/edit', [App\Http\Controllers\AdminController::class, 'editGrade'])->name('grades.edit');
    Route::put('/grades/{grade}', [App\Http\Controllers\AdminController::class, 'updateGrade'])->name('grades.update');
    Route::delete('/grades/{grade}', [App\Http\Controllers\AdminController::class, 'destroyGrade'])->name('grades.destroy');

    // Schools
    Route::get('/schools', [App\Http\Controllers\AdminController::class, 'schools'])->name('schools');
    Route::get('/schools/create', [App\Http\Controllers\AdminController::class, 'createSchool'])->name('schools.create');
    Route::post('/schools', [App\Http\Controllers\AdminController::class, 'storeSchool'])->name('schools.store');
    Route::get('/schools/{school}/edit', [App\Http\Controllers\AdminController::class, 'editSchool'])->name('schools.edit');
    Route::put('/schools/{school}', [App\Http\Controllers\AdminController::class, 'updateSchool'])->name('schools.update');
    // Salaries
    Route::get('/salaries', [App\Http\Controllers\AdminController::class, 'salaries'])->name('salaries');
    Route::get('/salaries/create', [App\Http\Controllers\AdminController::class, 'createSalary'])->name('salaries.create');
    Route::post('/salaries', [App\Http\Controllers\AdminController::class, 'storeSalary'])->name('salaries.store');
    Route::delete('/salaries/{salary}', [App\Http\Controllers\AdminController::class, 'destroySalary'])->name('salaries.destroy');

    // Fees
    Route::get('/fees', [App\Http\Controllers\AdminController::class, 'fees'])->name('fees');
    Route::get('/fees/create', [App\Http\Controllers\AdminController::class, 'createFee'])->name('fees.create');
    Route::post('/fees', [App\Http\Controllers\AdminController::class, 'storeFee'])->name('fees.store');
    Route::delete('/fees/{fee}', [App\Http\Controllers\AdminController::class, 'destroyFee'])->name('fees.destroy');

    // Timetables
    Route::get('/timetables/exam', [App\Http\Controllers\AdminController::class, 'examTimetables'])->name('timetables.exam');
    Route::get('/timetables/class', [App\Http\Controllers\AdminController::class, 'classTimetables'])->name('timetables.class');
    Route::post('/timetables/exam', [App\Http\Controllers\AdminController::class, 'storeExamTimetable'])->name('timetables.exam.store');
    Route::post('/timetables/class', [App\Http\Controllers\AdminController::class, 'storeClassTimetable'])->name('timetables.class.store');

    // Marksheets & Admin Cards
    Route::get('/marksheets', [App\Http\Controllers\AdminController::class, 'marksheets'])->name('marksheets');
    Route::get('/marksheets/{student}', [App\Http\Controllers\AdminController::class, 'viewMarksheet'])->name('marksheets.show');
    Route::get('/marksheets/{student}/print/{exam}', [App\Http\Controllers\AdminController::class, 'printMarksheet'])->name('marksheets.print');
    Route::get('/id-cards', [App\Http\Controllers\AdminController::class, 'idCards'])->name('idcards');
    Route::get('/search', [App\Http\Controllers\AdminController::class, 'search'])->name('search');

    Route::delete('/schools/{school}', [App\Http\Controllers\AdminController::class, 'destroySchool'])->name('schools.destroy');
});

// Student routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\StudentController::class, 'dashboard'])->name('dashboard');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/classrooms/{classroom}', [App\Http\Controllers\TeacherController::class, 'classroom'])->name('classroom');
    Route::post('/enrollments/{enrollment}/grades', [App\Http\Controllers\TeacherController::class, 'addGrade'])->name('add.grade');
});

// Public routes
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
