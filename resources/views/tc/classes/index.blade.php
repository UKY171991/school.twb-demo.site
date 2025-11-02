@extends('layouts.tc')

@section('title', 'My Classes')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Classes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Classes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Class Statistics -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_classes'] ?? 0 }}</h3>
                        <p>Total Classes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['total_students'] ?? 0 }}</h3>
                        <p>Total Students</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['today_attendance'] ?? 0 }}</h3>
                        <p>Today's Attendance</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['recent_grades'] ?? 0 }}</h3>
                        <p>Recent Grades</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classes Grid -->
        <div class="row">
            @forelse($classes as $class)
                <div class="col-lg-4 col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-door-open mr-2"></i>
                                {{ $class->full_name }}
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-light">{{ $class->students_count }} students</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-success">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        <h5 class="description-header">{{ $class->students_count }}</h5>
                                        <span class="description-text">Students</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="description-block">
                                        <span class="description-percentage text-info">
                                            <i class="fas fa-book"></i>
                                        </span>
                                        <h5 class="description-header">{{ $class->subjects->count() }}</h5>
                                        <span class="description-text">Subjects</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($class->room_number)
                                <p class="text-muted">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    Room: {{ $class->room_number }}
                                </p>
                            @endif
                            
                            @if($class->capacity)
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" 
                                         style="width: {{ $class->capacity_percentage }}%"></div>
                                </div>
                                <small class="text-muted">
                                    Capacity: {{ $class->students_count }}/{{ $class->capacity }} 
                                    ({{ $class->capacity_percentage }}%)
                                </small>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{ route('teacher.classes.show', $class) }}" class="btn btn-primary btn-sm btn-block">
                                        <i class="fas fa-eye mr-1"></i>
                                        View Details
                                    </a>
                                </div>
                                <div class="col-6">
                                    <div class="btn-group btn-block">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <i class="fas fa-cog mr-1"></i>
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('teacher.attendance.create', ['class_id' => $class->id]) }}">
                                                <i class="fas fa-calendar-check mr-2"></i>
                                                Mark Attendance
                                            </a>
                                            <a class="dropdown-item" href="{{ route('teacher.grades.create', ['class_id' => $class->id]) }}">
                                                <i class="fas fa-star mr-2"></i>
                                                Add Grades
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" onclick="viewRoster({{ $class->id }})">
                                                <i class="fas fa-list mr-2"></i>
                                                View Roster
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="sendMessage({{ $class->id }})">
                                                <i class="fas fa-envelope mr-2"></i>
                                                Send Message
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="createLessonPlan({{ $class->id }})">
                                                <i class="fas fa-clipboard-list mr-2"></i>
                                                Lesson Plan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Classes Assigned</h4>
                            <p class="text-muted">You don't have any classes assigned yet. Please contact your administrator.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Class Roster Modal -->
    <div class="modal fade" id="rosterModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fas fa-list mr-2"></i>
                        Class Roster
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="rosterTable">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Student Name</th>
                                    <th>Student ID</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Parent</th>
                                    <th>Attendance %</th>
                                    <th>Avg Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="rosterTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Message Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fas fa-envelope mr-2"></i>
                        Send Message to Students
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="messageForm">
                    <div class="modal-body">
                        <input type="hidden" id="message_class_id" name="class_id">
                        
                        <div class="form-group">
                            <label>Recipients</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="send_to_all" name="send_to_all" value="1" checked>
                                <label class="form-check-label" for="send_to_all">
                                    Send to all students in class
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="5" 
                                      placeholder="Enter your message..." required></textarea>
                            <small class="form-text text-muted">Maximum 1000 characters</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lesson Plan Modal -->
    <div class="modal fade" id="lessonPlanModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fas fa-clipboard-list mr-2"></i>
                        Create Lesson Plan
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="lessonPlanForm">
                    <div class="modal-body">
                        <input type="hidden" id="lesson_class_id" name="class_id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id">Subject <span class="text-danger">*</span></label>
                                    <select class="form-control" id="subject_id" name="subject_id" required>
                                        <option value="">Choose a subject...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lesson_date">Lesson Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="lesson_date" name="lesson_date" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Lesson Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           placeholder="Enter lesson title..." required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="duration">Duration (minutes) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="duration" name="duration" 
                                           min="1" max="300" value="45" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Brief description of the lesson..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="objectives">Learning Objectives <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="objectives" name="objectives" rows="3" 
                                      placeholder="What students will learn..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="materials">Materials Needed</label>
                            <textarea class="form-control" id="materials" name="materials" rows="2" 
                                      placeholder="Books, equipment, resources..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="activities">Activities <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="activities" name="activities" rows="4" 
                                      placeholder="Lesson activities and timeline..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="assessment">Assessment</label>
                            <textarea class="form-control" id="assessment" name="assessment" rows="2" 
                                      placeholder="How will you assess student learning?"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="homework">Homework/Assignment</label>
                            <textarea class="form-control" id="homework" name="homework" rows="2" 
                                      placeholder="Any homework or follow-up activities..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Save Lesson Plan
                        </button>
                    </div>
                </form>
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
        
        .description-block {
            text-align: center;
        }
        
        .description-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 10px 0 5px 0;
        }
        
        .description-text {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .card {
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .progress-sm {
            height: 10px;
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Message form submission
    $('#messageForm').submit(function(e) {
        e.preventDefault();
        sendMessageToClass();
    });
    
    // Lesson plan form submission
    $('#lessonPlanForm').submit(function(e) {
        e.preventDefault();
        saveLessonPlan();
    });
});

function viewRoster(classId) {
    $('#rosterModal').modal('show');
    
    $.ajax({
        url: `/teacher/classes/${classId}/roster`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                displayRoster(response.data);
            }
        },
        error: function() {
            toastr.error('Failed to load class roster');
        }
    });
}

function displayRoster(students) {
    const tbody = $('#rosterTableBody');
    tbody.empty();
    
    if (students.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="9" class="text-center text-muted">
                    No students found in this class
                </td>
            </tr>
        `);
        return;
    }
    
    students.forEach(function(student) {
        const attendanceColor = student.attendance_percentage >= 75 ? 'success' : 'danger';
        const gradeColor = student.average_grade >= 60 ? 'success' : 'warning';
        
        tbody.append(`
            <tr>
                <td>
                    <img src="${student.photo_url}" class="img-circle" width="40" height="40" alt="Student Photo">
                </td>
                <td>${student.full_name}</td>
                <td>${student.student_id}</td>
                <td>${student.email || 'N/A'}</td>
                <td>${student.phone || 'N/A'}</td>
                <td>
                    ${student.parent_name}<br>
                    <small class="text-muted">${student.parent_phone}</small>
                </td>
                <td>
                    <span class="badge badge-${attendanceColor}">${student.attendance_percentage}%</span>
                </td>
                <td>
                    <span class="badge badge-${gradeColor}">${student.average_grade}</span>
                </td>
                <td>
                    <span class="badge badge-success">${student.status}</span>
                </td>
            </tr>
        `);
    });
}

function sendMessage(classId) {
    $('#message_class_id').val(classId);
    $('#messageModal').modal('show');
}

function sendMessageToClass() {
    const formData = $('#messageForm').serialize();
    const classId = $('#message_class_id').val();
    
    $.ajax({
        url: `/teacher/classes/${classId}/message`,
        method: 'POST',
        data: formData + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                $('#messageModal').modal('hide');
                $('#messageForm')[0].reset();
            } else {
                toastr.error(response.message || 'Failed to send message');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                Object.keys(response.errors).forEach(function(key) {
                    toastr.error(response.errors[key][0]);
                });
            } else {
                toastr.error('Failed to send message');
            }
        }
    });
}

function createLessonPlan(classId) {
    $('#lesson_class_id').val(classId);
    $('#lessonPlanModal').modal('show');
    loadSubjectsForLessonPlan();
}

function loadSubjectsForLessonPlan() {
    // This would load subjects for the teacher
    // For now, we'll add some placeholder options
    const subjectSelect = $('#subject_id');
    subjectSelect.empty().append('<option value="">Choose a subject...</option>');
    
    // In a real implementation, you'd load this via AJAX
    subjectSelect.append('<option value="1">Mathematics</option>');
    subjectSelect.append('<option value="2">English</option>');
    subjectSelect.append('<option value="3">Science</option>');
}

function saveLessonPlan() {
    const formData = $('#lessonPlanForm').serialize();
    const classId = $('#lesson_class_id').val();
    
    $.ajax({
        url: `/teacher/classes/${classId}/lesson-plan`,
        method: 'POST',
        data: formData + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                $('#lessonPlanModal').modal('hide');
                $('#lessonPlanForm')[0].reset();
            } else {
                toastr.error(response.message || 'Failed to save lesson plan');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                Object.keys(response.errors).forEach(function(key) {
                    toastr.error(response.errors[key][0]);
                });
            } else {
                toastr.error('Failed to save lesson plan');
            }
        }
    });
}
</script>
@stop