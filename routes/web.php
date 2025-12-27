<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('grades', App\Http\Controllers\GradeController::class);
    Route::resource('teachers', App\Http\Controllers\TeacherController::class);
    Route::resource('students', App\Http\Controllers\StudentController::class);
    Route::resource('subjects', App\Http\Controllers\SubjectController::class);
    Route::resource('attendances', App\Http\Controllers\AttendanceController::class);
    Route::resource('marks', App\Http\Controllers\MarkController::class);
    
    // Marksheet routes
    Route::resource('marksheets', App\Http\Controllers\MarksheetController::class);
    Route::get('marksheets/{marksheet}/print', [App\Http\Controllers\MarksheetController::class, 'print'])->name('marksheets.print');
    Route::get('search-result', [App\Http\Controllers\MarksheetController::class, 'searchByRoll'])->name('marksheets.search');
    Route::post('search-result', [App\Http\Controllers\MarksheetController::class, 'searchByRoll']);
    
    // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [App\Http\Controllers\SystemSettingController::class, 'index'])->name('index');
        Route::post('/update', [App\Http\Controllers\SystemSettingController::class, 'update'])->name('update');
        
        Route::get('/grading', [App\Http\Controllers\SystemSettingController::class, 'gradingSettings'])->name('grading');
        Route::post('/grading', [App\Http\Controllers\SystemSettingController::class, 'updateGradingSettings'])->name('grading.update');
        
        Route::get('/marking', [App\Http\Controllers\SystemSettingController::class, 'markingSettings'])->name('marking');
        Route::post('/marking', [App\Http\Controllers\SystemSettingController::class, 'updateMarkingSettings'])->name('marking.update');
    });
    
    // Grading System routes
    Route::resource('grading-systems', App\Http\Controllers\GradingSystemController::class);
    Route::get('grading-systems/{gradingSystem}/toggle-status', [App\Http\Controllers\GradingSystemController::class, 'toggleStatus'])->name('grading-systems.toggle-status');
});
