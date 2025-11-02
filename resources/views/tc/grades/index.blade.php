@extends('layouts.tc')

@section('title', 'Grade Management')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Grade Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Grades</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Quick Actions Card -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-star mr-2"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="{{ route('teacher.grades.create') }}" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add Single Grade
                                </a>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-success btn-lg btn-block" id="bulkGradeBtn">
                                    <i class="fas fa-list mr-2"></i>
                                    Bulk Grade Entry
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-info btn-lg btn-block" id="viewAnalyticsBtn">
                                    <i class="fas fa-chart-bar mr-2"></i>
                                    View Analytics
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Statistics -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_grades'] ?? 0 }}</h3>
                        <p>Total Grades</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['recent_grades'] ?? 0 }}</h3>
                        <p>This Week</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['average_grade'] ?? 0 }}%</h3>
                        <p>Average Grade</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['pass_rate'] ?? 0 }}%</h3>
                        <p>Pass Rate</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-filter mr-2"></i>
                            Filters
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="filterForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="filter_subject">Subject</label>
                                        <select class="form-control" id="filter_subject" name="subject_id">
                                            <option value="">All Subjects</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="filter_class">Class</label>
                                        <select class="form-control" id="filter_class" name="class_id">
                                            <option value="">All Classes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="filter_exam_type">Exam Type</label>
                                        <select class="form-control" id="filter_exam_type" name="exam_type">
                                            <option value="">All Types</option>
                                            <option value="quiz">Quiz</option>
                                            <option value="midterm">Midterm</option>
                                            <option value="final">Final</option>
                                            <option value="assignment">Assignment</option>
                                            <option value="project">Project</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="button" class="btn btn-primary" id="applyFiltersBtn">
                                                <i class="fas fa-search mr-2"></i>
                                                Apply Filters
                                            </button>
                                            <button type="button" class="btn btn-secondary" id="clearFiltersBtn">
                                                <i class="fas fa-times mr-2"></i>
                                                Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grades List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-2"></i>
                            Recent Grades
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" id="gradesTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Subject</th>
                                    <th>Class</th>
                                    <th>Exam Type</th>
                                    <th>Marks</th>
                                    <th>Percentage</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="gradesTableBody">
                                @forelse($grades as $grade)
                                    <tr>
                                        <td>{{ $grade->exam_date?->format('M d, Y') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $grade->student->photo_url ?? asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" 
                                                     class="img-circle mr-2" width="30" height="30" alt="Student Photo">
                                                <div>
                                                    <strong>{{ $grade->student->full_name ?? 'Unknown' }}</strong>
                                                    <br><small class="text-muted">{{ $grade->student->student_id ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $grade->subject->name ?? 'Unknown' }}</td>
                                        <td>{{ $grade->class->full_name ?? 'Unknown' }}</td>
                                        <td>{!! $grade->exam_type_badge !!}</td>
                                        <td>{{ $grade->marks_obtained }}/{{ $grade->total_marks }}</td>
                                        <td>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar" 
                                                     style="width: {{ $grade->calculated_percentage }}%; background-color: {{ $grade->grade_color }}"></div>
                                            </div>
                                            <small>{{ $grade->calculated_percentage }}%</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-lg" style="background-color: {{ $grade->grade_color }}; color: white;">
                                                {{ $grade->grade_letter }}
                                            </span>
                                        </td>
                                        <td>{!! $grade->status_badge !!}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('teacher.grades.show', $grade) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('teacher.grades.edit', $grade) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger" onclick="deleteGrade({{ $grade->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            No grades found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($grades->hasPages())
                        <div class="card-footer">
                            {{ $grades->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Grade Entry Modal -->
    <div class="modal fade" id="bulkGradeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fas fa-list mr-2"></i>
                        Bulk Grade Entry
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="bulkGradeForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bulk_class">Class <span class="text-danger">*</span></label>
                                    <select class="form-control" id="bulk_class" name="class_id" required>
                                        <option value="">Choose a class...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bulk_subject">Subject <span class="text-danger">*</span></label>
                                    <select class="form-control" id="bulk_subject" name="subject_id" required>
                                        <option value="">Choose a subject...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bulk_exam_type">Exam Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="bulk_exam_type" name="exam_type" required>
                                        <option value="">Choose type...</option>
                                        <option value="quiz">Quiz</option>
                                        <option value="midterm">Midterm</option>
                                        <option value="final">Final</option>
                                        <option value="assignment">Assignment</option>
                                        <option value="project">Project</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bulk_exam_date">Exam Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="bulk_exam_date" name="exam_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bulk_total_marks">Total Marks <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="bulk_total_marks" name="total_marks" 
                                           min="1" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="button" class="btn btn-info" id="loadStudentsForGradingBtn">
                                            <i class="fas fa-users mr-2"></i>
                                            Load Students
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="studentsGradingSection" style="display: none;">
                            <hr>
                            <h5>Student Grades</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Marks Obtained</th>
                                            <th>Percentage</th>
                                            <th>Grade</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentsGradingTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveBulkGradesBtn" style="display: none;">
                        <i class="fas fa-save mr-2"></i>
                        Save Grades
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box .inner h3 {
            font-size: 2.2rem;
            font-weight: bold;
        }
        
        .progress-sm {
            height: 10px;
        }
        
        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }
        
        .grade-input {
            width: 100px;
        }
        
        .percentage-display {
            font-weight: bold;
            min-width: 60px;
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    loadFilterOptions();
    
    // Bulk grade entry
    $('#bulkGradeBtn').click(function() {
        $('#bulkGradeModal').modal('show');
        loadBulkGradeOptions();
    });
    
    // Load students for grading
    $('#loadStudentsForGradingBtn').click(function() {
        loadStudentsForGrading();
    });
    
    // Save bulk grades
    $('#saveBulkGradesBtn').click(function() {
        saveBulkGrades();
    });
    
    // Apply filters
    $('#applyFiltersBtn').click(function() {
        applyFilters();
    });
    
    // Clear filters
    $('#clearFiltersBtn').click(function() {
        $('#filterForm')[0].reset();
        applyFilters();
    });
    
    function loadFilterOptions() {
        // Load subjects
        $.ajax({
            url: '{{ route("ajax.teacher.classes") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const classSelect = $('#filter_class');
                    classSelect.empty().append('<option value="">All Classes</option>');
                    
                    response.data.forEach(function(cls) {
                        classSelect.append(`<option value="${cls.id}">${cls.full_name}</option>`);
                    });
                }
            }
        });
    }
    
    function loadBulkGradeOptions() {
        // Load classes and subjects for bulk grading
        $.ajax({
            url: '{{ route("ajax.teacher.classes") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const select = $('#bulk_class');
                    select.empty().append('<option value="">Choose a class...</option>');
                    
                    response.data.forEach(function(cls) {
                        select.append(`<option value="${cls.id}">${cls.full_name}</option>`);
                    });
                }
            }
        });
    }
    
    function loadStudentsForGrading() {
        const classId = $('#bulk_class').val();
        const subjectId = $('#bulk_subject').val();
        const totalMarks = $('#bulk_total_marks').val();
        
        if (!classId || !subjectId || !totalMarks) {
            toastr.warning('Please select class, subject, and enter total marks');
            return;
        }
        
        $('#loadStudentsForGradingBtn').html('<i class="fas fa-spinner fa-spin mr-2"></i>Loading...');
        $('#loadStudentsForGradingBtn').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("teacher.grades.students") }}',
            method: 'GET',
            data: { class_id: classId, subject_id: subjectId },
            success: function(response) {
                if (response.success) {
                    displayStudentsForGrading(response.data.students, totalMarks);
                    $('#studentsGradingSection').show();
                    $('#saveBulkGradesBtn').show();
                }
            },
            error: function() {
                toastr.error('Failed to load students');
            },
            complete: function() {
                $('#loadStudentsForGradingBtn').html('<i class="fas fa-users mr-2"></i>Load Students');
                $('#loadStudentsForGradingBtn').prop('disabled', false);
            }
        });
    }
    
    function displayStudentsForGrading(students, totalMarks) {
        const tbody = $('#studentsGradingTableBody');
        tbody.empty();
        
        students.forEach(function(student, index) {
            tbody.append(`
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${student.photo_url}" class="img-circle mr-2" width="30" height="30" alt="Student Photo">
                            <div>
                                <strong>${student.full_name}</strong>
                                <br><small class="text-muted">${student.student_id}</small>
                            </div>
                        </div>
                        <input type="hidden" name="grades_data[${index}][student_id]" value="${student.id}">
                    </td>
                    <td>
                        <input type="number" class="form-control grade-input" 
                               name="grades_data[${index}][marks_obtained]" 
                               min="0" max="${totalMarks}" step="0.01" 
                               onchange="calculatePercentage(this, ${totalMarks}, ${index})" required>
                    </td>
                    <td>
                        <span class="percentage-display" id="percentage_${index}">0%</span>
                    </td>
                    <td>
                        <span class="badge" id="grade_${index}">-</span>
                    </td>
                    <td>
                        <input type="text" class="form-control" 
                               name="grades_data[${index}][remarks]" 
                               placeholder="Optional remarks...">
                    </td>
                </tr>
            `);
        });
    }
    
    function saveBulkGrades() {
        const formData = new FormData($('#bulkGradeForm')[0]);
        
        // Convert FormData to regular object for easier manipulation
        const gradesData = [];
        const studentCount = $('#studentsGradingTableBody tr').length;
        
        for (let i = 0; i < studentCount; i++) {
            const marksObtained = $(`input[name="grades_data[${i}][marks_obtained]"]`).val();
            const studentId = $(`input[name="grades_data[${i}][student_id]"]`).val();
            const remarks = $(`input[name="grades_data[${i}][remarks]"]`).val();
            
            if (marksObtained && studentId) {
                gradesData.push({
                    student_id: studentId,
                    marks_obtained: parseFloat(marksObtained),
                    remarks: remarks || null
                });
            }
        }
        
        if (gradesData.length === 0) {
            toastr.warning('Please enter marks for at least one student');
            return;
        }
        
        $('#saveBulkGradesBtn').html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...');
        $('#saveBulkGradesBtn').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("teacher.grades.bulk-store") }}',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                class_id: $('#bulk_class').val(),
                subject_id: $('#bulk_subject').val(),
                exam_type: $('#bulk_exam_type').val(),
                exam_date: $('#bulk_exam_date').val(),
                total_marks: $('#bulk_total_marks').val(),
                grades_data: gradesData
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Grades saved successfully');
                    $('#bulkGradeModal').modal('hide');
                    
                    // Reload page after a short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Failed to save grades');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    Object.keys(response.errors).forEach(function(key) {
                        toastr.error(response.errors[key][0]);
                    });
                } else {
                    toastr.error(response?.message || 'Failed to save grades');
                }
            },
            complete: function() {
                $('#saveBulkGradesBtn').html('<i class="fas fa-save mr-2"></i>Save Grades');
                $('#saveBulkGradesBtn').prop('disabled', false);
            }
        });
    }
    
    function applyFilters() {
        // This would reload the table with filters applied
        // For now, just show a message
        toastr.info('Filter functionality will be implemented with DataTables');
    }
    
    // Global functions
    window.calculatePercentage = function(input, totalMarks, index) {
        const marksObtained = parseFloat(input.value) || 0;
        const percentage = (marksObtained / totalMarks) * 100;
        
        $(`#percentage_${index}`).text(percentage.toFixed(2) + '%');
        
        // Calculate grade letter
        let gradeLetter = 'F';
        let gradeColor = '#dc3545';
        
        if (percentage >= 90) { gradeLetter = 'A+'; gradeColor = '#28a745'; }
        else if (percentage >= 85) { gradeLetter = 'A'; gradeColor = '#28a745'; }
        else if (percentage >= 80) { gradeLetter = 'A-'; gradeColor = '#17a2b8'; }
        else if (percentage >= 75) { gradeLetter = 'B+'; gradeColor = '#17a2b8'; }
        else if (percentage >= 70) { gradeLetter = 'B'; gradeColor = '#007bff'; }
        else if (percentage >= 65) { gradeLetter = 'B-'; gradeColor = '#007bff'; }
        else if (percentage >= 60) { gradeLetter = 'C+'; gradeColor = '#ffc107'; }
        else if (percentage >= 55) { gradeLetter = 'C'; gradeColor = '#ffc107'; }
        else if (percentage >= 50) { gradeLetter = 'C-'; gradeColor = '#ffc107'; }
        else if (percentage >= 45) { gradeLetter = 'D'; gradeColor = '#fd7e14'; }
        
        $(`#grade_${index}`).text(gradeLetter).css('background-color', gradeColor).css('color', 'white');
    };
    
    window.deleteGrade = function(gradeId) {
        if (confirm('Are you sure you want to delete this grade?')) {
            $.ajax({
                url: `/teacher/grades/${gradeId}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Grade deleted successfully');
                        location.reload();
                    } else {
                        toastr.error('Failed to delete grade');
                    }
                },
                error: function() {
                    toastr.error('Failed to delete grade');
                }
            });
        }
    };
});
</script>
@stop