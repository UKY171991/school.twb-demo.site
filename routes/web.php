<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    // Test route for debugging images
    Route::get('/test-images', function() {
        return view('test-images');
    });
    
    // API routes for dynamic loading
    Route::get('/api/schools/{school}/grades', [App\Http\Controllers\GradeController::class, 'getBySchool'])->name('api.schools.grades');
    Route::get('/api/grades/{grade}/subjects', [App\Http\Controllers\SubjectController::class, 'getByGrade'])->name('api.grades.subjects');
    Route::get('/api/students/{student}/exam-data', [App\Http\Controllers\MarksheetController::class, 'getStudentExamData'])->name('api.students.exam-data');
    
    // School routes
    Route::resource('schools', App\Http\Controllers\SchoolController::class);
    Route::post('schools/{school}/switch', [App\Http\Controllers\SchoolController::class, 'switchSchool'])->name('schools.switch');
    Route::get('schools/{school}/remove-logo', [App\Http\Controllers\SchoolController::class, 'removeLogo'])->name('schools.remove-logo');
    
    Route::resource('grades', App\Http\Controllers\GradeController::class);
    Route::resource('teachers', App\Http\Controllers\TeacherController::class);
    Route::get('teachers/{teacher}/remove-image', [App\Http\Controllers\TeacherController::class, 'removeImage'])->name('teachers.remove-image');
    Route::resource('students', App\Http\Controllers\StudentController::class);
    Route::get('students/{student}/remove-image', [App\Http\Controllers\StudentController::class, 'removeImage'])->name('students.remove-image');
    Route::resource('subjects', App\Http\Controllers\SubjectController::class);
    Route::resource('attendances', App\Http\Controllers\AttendanceController::class);
    Route::resource('marks', App\Http\Controllers\MarkController::class);
    
    // Marksheet routes
    Route::resource('marksheets', App\Http\Controllers\MarksheetController::class);
    Route::get('marksheets/{marksheet}/print', [App\Http\Controllers\MarksheetController::class, 'print'])->name('marksheets.print');
    Route::get('marksheets/{marksheet}/print-single', [App\Http\Controllers\MarksheetController::class, 'printSingle'])->name('marksheets.print-single');
    Route::get('search-result', [App\Http\Controllers\MarksheetController::class, 'searchByRoll'])->name('marksheets.search');
    Route::post('search-result', [App\Http\Controllers\MarksheetController::class, 'searchByRoll']);
    Route::get('marksheets-recalculate-positions', [App\Http\Controllers\MarksheetController::class, 'recalculatePositions'])->name('marksheets.recalculate-positions');
    
    // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [App\Http\Controllers\SystemSettingController::class, 'index'])->name('index');
        Route::post('/update', [App\Http\Controllers\SystemSettingController::class, 'update'])->name('update');
        Route::post('/reset', [App\Http\Controllers\SystemSettingController::class, 'resetSetting'])->name('reset');
        
        Route::get('/grading', [App\Http\Controllers\SystemSettingController::class, 'gradingSettings'])->name('grading');
        Route::post('/grading', [App\Http\Controllers\SystemSettingController::class, 'updateGradingSettings'])->name('grading.update');
        
        Route::get('/marking', [App\Http\Controllers\SystemSettingController::class, 'markingSettings'])->name('marking');
        Route::post('/marking', [App\Http\Controllers\SystemSettingController::class, 'updateMarkingSettings'])->name('marking.update');
    });
    
    // Grading System routes
    Route::resource('grading-systems', App\Http\Controllers\GradingSystemController::class);
    Route::get('grading-systems/{gradingSystem}/toggle-status', [App\Http\Controllers\GradingSystemController::class, 'toggleStatus'])->name('grading-systems.toggle-status');
    
    // Exam Type routes
    Route::resource('exam-types', App\Http\Controllers\ExamTypeController::class);
    Route::get('exam-types/{examType}/toggle-status', [App\Http\Controllers\ExamTypeController::class, 'toggleStatus'])->name('exam-types.toggle-status');
    
    // Admit Card routes
    Route::prefix('admit-cards')->name('admit-cards.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdmitCardController::class, 'index'])->name('index');
        Route::get('/search', [App\Http\Controllers\AdmitCardController::class, 'search'])->name('search');
        Route::post('/generate', [App\Http\Controllers\AdmitCardController::class, 'generate'])->name('generate');
        Route::post('/bulk-generate', [App\Http\Controllers\AdmitCardController::class, 'bulkGenerate'])->name('bulk-generate');
    });
    
    // Exam Timetable routes
    Route::resource('exam-timetables', App\Http\Controllers\ExamTimetableController::class);
    Route::get('exam-timetables-bulk-create', [App\Http\Controllers\ExamTimetableController::class, 'bulkCreate'])->name('exam-timetables.bulk-create');
    Route::post('exam-timetables-bulk-store', [App\Http\Controllers\ExamTimetableController::class, 'bulkStore'])->name('exam-timetables.bulk-store');
    Route::get('api/exam-timetables/class-exam', [App\Http\Controllers\ExamTimetableController::class, 'getByClassAndExam'])->name('api.exam-timetables.class-exam');
});
