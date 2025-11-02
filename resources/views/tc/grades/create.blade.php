@extends('layouts.tc')

@section('title', 'Add Grade')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Grade</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.grades') }}">Grades</a></li>
                        <li class="breadcrumb-item active">Add Grade</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-plus mr-2"></i>
                            Grade Entry Form
                        </h3>
                    </div>
                    <form id="gradeForm" action="{{ route('teacher.grades.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_id">Class <span class="text-danger">*</span></label>
                                        <select class="form-control @error('class_id') is-invalid @enderror" 
                                                id="class_id" name="class_id" required>
                                            <option value="">Choose a class...</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                    {{ $class->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subject_id">Subject <span class="text-danger">*</span></label>
                                        <select class="form-control @error('subject_id') is-invalid @enderror" 
                                                id="subject_id" name="subject_id" required>
                                            <option value="">Choose a subject...</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->display_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="student_id">Student <span class="text-danger">*</span></label>
                                        <select class="form-control @error('student_id') is-invalid @enderror" 
                                                id="student_id" name="student_id" required>
                                            <option value="">Choose a student...</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->full_name }} ({{ $student->student_id }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('student_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exam_type">Exam Type <span class="text-danger">*</span></label>
                                        <select class="form-control @error('exam_type') is-invalid @enderror" 
                                                id="exam_type" name="exam_type" required>
                                            <option value="">Choose exam type...</option>
                                            <option value="quiz" {{ old('exam_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                            <option value="midterm" {{ old('exam_type') == 'midterm' ? 'selected' : '' }}>Midterm</option>
                                            <option value="final" {{ old('exam_type') == 'final' ? 'selected' : '' }}>Final</option>
                                            <option value="assignment" {{ old('exam_type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                                            <option value="project" {{ old('exam_type') == 'project' ? 'selected' : '' }}>Project</option>
                                        </select>
                                        @error('exam_type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="marks_obtained">Marks Obtained <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('marks_obtained') is-invalid @enderror" 
                                               id="marks_obtained" name="marks_obtained" 
                                               min="0" step="0.01" value="{{ old('marks_obtained') }}" 
                                               onchange="calculateGrade()" required>
                                        @error('marks_obtained')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total_marks">Total Marks <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('total_marks') is-invalid @enderror" 
                                               id="total_marks" name="total_marks" 
                                               min="1" step="0.01" value="{{ old('total_marks') }}" 
                                               onchange="calculateGrade()" required>
                                        @error('total_marks')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exam_date">Exam Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('exam_date') is-invalid @enderror" 
                                               id="exam_date" name="exam_date" 
                                               value="{{ old('exam_date', date('Y-m-d')) }}" required>
                                        @error('exam_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Grade Calculation Display -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-calculator mr-2"></i>
                                                Grade Calculation
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-md-3">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Percentage</span>
                                                            <span class="info-box-number" id="calculated_percentage">0%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Grade Letter</span>
                                                            <span class="info-box-number" id="calculated_grade">-</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-warning">
                                                        <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Performance</span>
                                                            <span class="info-box-number" id="performance_level">-</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box" id="status_box">
                                                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Status</span>
                                                            <span class="info-box-number" id="pass_status">-</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                                  id="remarks" name="remarks" rows="3" 
                                                  placeholder="Optional remarks about the grade...">{{ old('remarks') }}</textarea>
                                        @error('remarks')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('teacher.grades') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Back to List
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i>
                                        Save Grade
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .info-box-number {
            font-size: 1.5rem !important;
            font-weight: bold;
        }
        
        .grade-display {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin: 10px 0;
        }
        
        .grade-a { background-color: #d4edda; color: #155724; }
        .grade-b { background-color: #d1ecf1; color: #0c5460; }
        .grade-c { background-color: #fff3cd; color: #856404; }
        .grade-d { background-color: #f8d7da; color: #721c24; }
        .grade-f { background-color: #f8d7da; color: #721c24; }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Calculate grade on page load if values exist
    calculateGrade();
    
    // Filter students by class
    $('#class_id').change(function() {
        const classId = $(this).val();
        filterStudentsByClass(classId);
    });
});

function calculateGrade() {
    const marksObtained = parseFloat($('#marks_obtained').val()) || 0;
    const totalMarks = parseFloat($('#total_marks').val()) || 0;
    
    if (totalMarks === 0) {
        resetGradeDisplay();
        return;
    }
    
    const percentage = (marksObtained / totalMarks) * 100;
    
    // Update percentage
    $('#calculated_percentage').text(percentage.toFixed(2) + '%');
    
    // Calculate grade letter
    let gradeLetter = 'F';
    let performanceLevel = 'Poor';
    let statusColor = 'bg-danger';
    let passStatus = 'Fail';
    
    if (percentage >= 90) {
        gradeLetter = 'A+';
        performanceLevel = 'Excellent';
        statusColor = 'bg-success';
        passStatus = 'Pass';
    } else if (percentage >= 85) {
        gradeLetter = 'A';
        performanceLevel = 'Excellent';
        statusColor = 'bg-success';
        passStatus = 'Pass';
    } else if (percentage >= 80) {
        gradeLetter = 'A-';
        performanceLevel = 'Good';
        statusColor = 'bg-info';
        passStatus = 'Pass';
    } else if (percentage >= 75) {
        gradeLetter = 'B+';
        performanceLevel = 'Good';
        statusColor = 'bg-info';
        passStatus = 'Pass';
    } else if (percentage >= 70) {
        gradeLetter = 'B';
        performanceLevel = 'Average';
        statusColor = 'bg-primary';
        passStatus = 'Pass';
    } else if (percentage >= 65) {
        gradeLetter = 'B-';
        performanceLevel = 'Average';
        statusColor = 'bg-primary';
        passStatus = 'Pass';
    } else if (percentage >= 60) {
        gradeLetter = 'C+';
        performanceLevel = 'Below Average';
        statusColor = 'bg-warning';
        passStatus = 'Pass';
    } else if (percentage >= 55) {
        gradeLetter = 'C';
        performanceLevel = 'Below Average';
        statusColor = 'bg-warning';
        passStatus = 'Pass';
    } else if (percentage >= 50) {
        gradeLetter = 'C-';
        performanceLevel = 'Below Average';
        statusColor = 'bg-warning';
        passStatus = 'Pass';
    } else if (percentage >= 45) {
        gradeLetter = 'D';
        performanceLevel = 'Poor';
        statusColor = 'bg-danger';
        passStatus = 'Pass';
    }
    
    // Update display
    $('#calculated_grade').text(gradeLetter);
    $('#performance_level').text(performanceLevel);
    $('#pass_status').text(passStatus);
    
    // Update status box color
    $('#status_box').removeClass('bg-success bg-info bg-primary bg-warning bg-danger').addClass(statusColor);
}

function resetGradeDisplay() {
    $('#calculated_percentage').text('0%');
    $('#calculated_grade').text('-');
    $('#performance_level').text('-');
    $('#pass_status').text('-');
    $('#status_box').removeClass('bg-success bg-info bg-primary bg-warning bg-danger');
}

function filterStudentsByClass(classId) {
    const studentSelect = $('#student_id');
    
    if (!classId) {
        // Show all students
        studentSelect.find('option').show();
        return;
    }
    
    // This would typically be an AJAX call to get students for the specific class
    // For now, we'll just show all students
    studentSelect.find('option').show();
}

// Form submission with AJAX
$('#gradeForm').submit(function(e) {
    e.preventDefault();
    
    const formData = $(this).serialize();
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                toastr.success(response.message || 'Grade saved successfully');
                
                // Redirect after a short delay
                setTimeout(function() {
                    window.location.href = '{{ route("teacher.grades") }}';
                }, 1500);
            } else {
                toastr.error(response.message || 'Failed to save grade');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                // Display validation errors
                Object.keys(response.errors).forEach(function(key) {
                    toastr.error(response.errors[key][0]);
                });
            } else {
                toastr.error(response?.message || 'Failed to save grade');
            }
        }
    });
});
</script>
@stop