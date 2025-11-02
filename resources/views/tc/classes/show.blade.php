@extends('layouts.tc')

@section('title', 'Class Details - ' . $class->full_name)

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $class->full_name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.classes') }}">Classes</a></li>
                        <li class="breadcrumb-item active">{{ $class->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Class Information -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>
                            Class Information
                        </h3>
                        <div class="card-tools">
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm" onclick="markAttendance()">
                                    <i class="fas fa-calendar-check mr-1"></i>
                                    Mark Attendance
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="addGrades()">
                                    <i class="fas fa-star mr-1"></i>
                                    Add Grades
                                </button>
                                <button type="button" class="btn btn-info btn-sm" onclick="sendClassMessage()">
                                    <i class="fas fa-envelope mr-1"></i>
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Class Name:</strong><br>
                                <span class="text-muted">{{ $class->full_name }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>School:</strong><br>
                                <span class="text-muted">{{ $class->school->name ?? 'Unknown' }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Room Number:</strong><br>
                                <span class="text-muted">{{ $class->room_number ?? 'Not assigned' }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Capacity:</strong><br>
                                <span class="text-muted">{{ $class->capacity ?? 'Not set' }}</span>
                            </div>
                        </div>
                        
                        @if($class->description)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <strong>Description:</strong><br>
                                    <span class="text-muted">{{ $class->description }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $attendanceStats['total_students'] ?? 0 }}</h3>
                        <p>Total Students</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $attendanceStats['present_today'] ?? 0 }}</h3>
                        <p>Present Today</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $attendanceStats['absent_today'] ?? 0 }}</h3>
                        <p>Absent Today</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $class->subjects->count() }}</h3>
                        <p>Subjects</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs for different sections -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="students-tab" data-toggle="pill" href="#students" role="tab">
                                    <i class="fas fa-users mr-2"></i>Students
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="subjects-tab" data-toggle="pill" href="#subjects" role="tab">
                                    <i class="fas fa-book mr-2"></i>Subjects
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="performance-tab" data-toggle="pill" href="#performance" role="tab">
                                    <i class="fas fa-chart-line mr-2"></i>Performance
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="lessons-tab" data-toggle="pill" href="#lessons" role="tab">
                                    <i class="fas fa-clipboard-list mr-2"></i>Lesson Plans
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <!-- Students Tab -->
                            <div class="tab-pane fade show active" id="students" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="studentsTable">
                                        <thead>
                                            <tr>
                                                <th>Photo</th>
                                                <th>Name</th>
                                                <th>Student ID</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($class->students as $student)
                                                <tr>
                                                    <td>
                                                        <img src="{{ $student->photo_url }}" 
                                                             class="img-circle" width="40" height="40" alt="Student Photo">
                                                    </td>
                                                    <td>
                                                        <strong>{{ $student->full_name }}</strong>
                                                        @if($student->user)
                                                            <br><small class="text-muted">{{ $student->user->email }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $student->student_id }}</td>
                                                    <td>{{ $student->email ?? 'N/A' }}</td>
                                                    <td>{{ $student->phone ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-success">{{ ucfirst($student->status) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-info" onclick="viewStudentDetails({{ $student->id }})">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-warning" onclick="sendStudentMessage({{ $student->id }})">
                                                                <i class="fas fa-envelope"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        No students enrolled in this class
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Subjects Tab -->
                            <div class="tab-pane fade" id="subjects" role="tabpanel">
                                <div class="row">
                                    @forelse($class->subjects as $subject)
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $subject->name }}</h5>
                                                    @if($subject->code)
                                                        <p class="card-text">
                                                            <strong>Code:</strong> {{ $subject->code }}
                                                        </p>
                                                    @endif
                                                    @if($subject->credits)
                                                        <p class="card-text">
                                                            <strong>Credits:</strong> {{ $subject->credits }}
                                                        </p>
                                                    @endif
                                                    @if($subject->description)
                                                        <p class="card-text">{{ $subject->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                No subjects assigned to this class yet.
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Performance Tab -->
                            <div class="tab-pane fade" id="performance" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Attendance Trends</h3>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="attendanceChart" height="200"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Grade Distribution</h3>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="gradeChart" height="200"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Students Needing Attention</h3>
                                            </div>
                                            <div class="card-body">
                                                <div id="attentionStudents">
                                                    <p class="text-muted">Loading performance data...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lesson Plans Tab -->
                            <div class="tab-pane fade" id="lessons" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <button class="btn btn-primary" onclick="createNewLessonPlan()">
                                            <i class="fas fa-plus mr-2"></i>
                                            Create New Lesson Plan
                                        </button>
                                    </div>
                                </div>
                                
                                <div id="lessonPlansList">
                                    <p class="text-muted">Loading lesson plans...</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
        
        .nav-tabs .nav-link {
            color: #495057;
        }
        
        .nav-tabs .nav-link.active {
            color: #007bff;
            font-weight: bold;
        }
        
        .card {
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Load performance data when performance tab is clicked
    $('#performance-tab').click(function() {
        loadPerformanceData();
    });
    
    // Load lesson plans when lessons tab is clicked
    $('#lessons-tab').click(function() {
        loadLessonPlans();
    });
});

function markAttendance() {
    window.location.href = '{{ route("teacher.attendance.create", ["class_id" => $class->id]) }}';
}

function addGrades() {
    window.location.href = '{{ route("teacher.grades.create", ["class_id" => $class->id]) }}';
}

function sendClassMessage() {
    // This would open a modal to send message to all students
    toastr.info('Class messaging feature will be implemented');
}

function viewStudentDetails(studentId) {
    // This would show detailed student information
    toastr.info('Student details view will be implemented');
}

function sendStudentMessage(studentId) {
    // This would open a modal to send message to specific student
    toastr.info('Individual student messaging will be implemented');
}

function loadPerformanceData() {
    $.ajax({
        url: '{{ route("teacher.classes.performance", $class) }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                displayPerformanceCharts(response.data);
                displayAttentionStudents(response.data.student_performance);
            }
        },
        error: function() {
            $('#attentionStudents').html('<p class="text-danger">Failed to load performance data</p>');
        }
    });
}

function displayPerformanceCharts(data) {
    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'line',
        data: {
            labels: Object.keys(data.attendance_trends || {}),
            datasets: [{
                label: 'Present',
                data: Object.values(data.attendance_trends || {}).map(day => 
                    day.find(d => d.status === 'present')?.count || 0
                ),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Grade Chart
    const gradeCtx = document.getElementById('gradeChart').getContext('2d');
    const gradeData = data.grade_summary || {};
    new Chart(gradeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pass', 'Fail'],
            datasets: [{
                data: [
                    gradeData.passing_grades || 0,
                    (gradeData.total_grades || 0) - (gradeData.passing_grades || 0)
                ],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function displayAttentionStudents(students) {
    const container = $('#attentionStudents');
    const needsAttention = students.filter(s => s.needs_attention);
    
    if (needsAttention.length === 0) {
        container.html('<div class="alert alert-success"><i class="fas fa-check-circle mr-2"></i>All students are performing well!</div>');
        return;
    }
    
    let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Student</th><th>Attendance %</th><th>Avg Grade</th><th>Status</th></tr></thead><tbody>';
    
    needsAttention.forEach(student => {
        html += `
            <tr>
                <td>${student.student_name}</td>
                <td><span class="badge badge-${student.attendance_percentage < 75 ? 'danger' : 'warning'}">${student.attendance_percentage}%</span></td>
                <td><span class="badge badge-${student.average_grade < 60 ? 'danger' : 'warning'}">${student.average_grade}</span></td>
                <td><span class="badge badge-warning">Needs Attention</span></td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    container.html(html);
}

function loadLessonPlans() {
    $.ajax({
        url: '{{ route("teacher.classes.lesson-plans", $class) }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                displayLessonPlans(response.data);
            }
        },
        error: function() {
            $('#lessonPlansList').html('<p class="text-danger">Failed to load lesson plans</p>');
        }
    });
}

function displayLessonPlans(lessonPlans) {
    const container = $('#lessonPlansList');
    
    if (lessonPlans.length === 0) {
        container.html('<div class="alert alert-info"><i class="fas fa-info-circle mr-2"></i>No lesson plans created yet.</div>');
        return;
    }
    
    let html = '';
    lessonPlans.forEach(plan => {
        html += `
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">${plan.title}</h5>
                    <small class="text-muted">Date: ${plan.lesson_date} | Duration: ${plan.duration} minutes</small>
                </div>
                <div class="card-body">
                    <p><strong>Description:</strong> ${plan.description}</p>
                    <p><strong>Objectives:</strong> ${plan.objectives}</p>
                    ${plan.materials ? `<p><strong>Materials:</strong> ${plan.materials}</p>` : ''}
                    <p><strong>Activities:</strong> ${plan.activities}</p>
                    ${plan.assessment ? `<p><strong>Assessment:</strong> ${plan.assessment}</p>` : ''}
                    ${plan.homework ? `<p><strong>Homework:</strong> ${plan.homework}</p>` : ''}
                </div>
            </div>
        `;
    });
    
    container.html(html);
}

function createNewLessonPlan() {
    // This would open the lesson plan creation modal
    toastr.info('Lesson plan creation will be implemented');
}
</script>
@stop