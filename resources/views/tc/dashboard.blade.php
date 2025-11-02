@extends('layouts.tc')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumbs')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $statistics['total_classes'] }}</h3>
                <p>My Classes</p>
            </div>
            <div class="icon">
                <i class="fas fa-door-open"></i>
            </div>
            <a href="{{ route('teacher.classes') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $statistics['total_students'] }}</h3>
                <p>My Students</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('teacher.students') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $statistics['total_subjects'] }}</h3>
                <p>My Subjects</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{ route('teacher.subjects') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $statistics['pending_attendance'] }}</h3>
                <p>Pending Attendance</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('teacher.attendance.create') }}" class="small-box-footer">
                Mark Now <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Today's Classes and Quick Actions -->
<div class="row">
    <!-- Today's Classes -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-day"></i> Today's Classes
                </h3>
                <div class="card-tools">
                    <span class="badge badge-info">{{ count($todayClasses) }} classes</span>
                </div>
            </div>
            <div class="card-body">
                @if(count($todayClasses) > 0)
                    <div class="timeline">
                        @foreach($todayClasses as $classData)
                        <div class="time-label">
                            <span class="bg-blue">{{ $classData['time_slot'] }}</span>
                        </div>
                        <div>
                            <i class="fas fa-door-open bg-{{ $classData['attendance_marked'] ? 'success' : 'warning' }}"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> {{ $classData['time_slot'] }}
                                    @if($classData['is_current'])
                                        <span class="badge badge-success ml-2">Current</span>
                                    @elseif($classData['is_upcoming'])
                                        <span class="badge badge-info ml-2">Upcoming</span>
                                    @endif
                                </span>
                                <h3 class="timeline-header">
                                    {{ $classData['class']->full_name }} - {{ $classData['subject']->name }}
                                </h3>
                                <div class="timeline-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Students:</strong> {{ $classData['student_count'] }}<br>
                                            <strong>Room:</strong> {{ $classData['room'] ?? 'Not assigned' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Attendance:</strong> 
                                            @if($classData['attendance_marked'])
                                                <span class="badge badge-success">Marked</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="timeline-footer">
                                    @if(!$classData['attendance_marked'])
                                        <button class="btn btn-success btn-sm mark-attendance-btn" 
                                                data-class-id="{{ $classData['class']->id }}"
                                                data-class-name="{{ $classData['class']->full_name }}">
                                            <i class="fas fa-check"></i> Mark Attendance
                                        </button>
                                    @endif
                                    <a href="{{ route('teacher.classes.show', $classData['class']->id) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> View Class
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No classes scheduled for today.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions and Alerts -->
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <a href="{{ route('teacher.attendance.create') }}" class="btn btn-success btn-block mb-2">
                    <i class="fas fa-calendar-check"></i> Mark Attendance
                </a>
                <a href="{{ route('teacher.grades.create') }}" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-star"></i> Record Grades
                </a>
                <a href="{{ route('teacher.schedule') }}" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-calendar-alt"></i> View Schedule
                </a>
                <a href="{{ route('teacher.profile') }}" class="btn btn-warning btn-block">
                    <i class="fas fa-user"></i> Update Profile
                </a>
            </div>
        </div>

        <!-- Student Alerts -->
        @if(count($studentAlerts) > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle"></i> Student Alerts
                </h3>
            </div>
            <div class="card-body">
                @foreach($studentAlerts as $alert)
                <div class="alert alert-{{ $alert['severity'] === 'danger' ? 'danger' : 'warning' }} alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h6><i class="icon fas fa-{{ $alert['type'] === 'attendance' ? 'calendar-times' : 'chart-line' }}"></i> {{ $alert['title'] }}</h6>
                    <strong>{{ $alert['student_name'] }}</strong> ({{ $alert['class_name'] }})<br>
                    <small>{{ $alert['description'] }}</small>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Pending Tasks and Recent Activity -->
<div class="row">
    <!-- Pending Tasks -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks"></i> Pending Tasks
                </h3>
                <div class="card-tools">
                    <span class="badge badge-warning">{{ count($pendingTasks) }}</span>
                </div>
            </div>
            <div class="card-body">
                @if(count($pendingTasks) > 0)
                    @foreach($pendingTasks as $task)
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-{{ $task['color'] }}">
                            <i class="{{ $task['icon'] }}"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $task['title'] }}</span>
                            <span class="info-box-number">{{ $task['count'] }}</span>
                            <div class="progress">
                                <div class="progress-bar bg-{{ $task['color'] }}" 
                                     style="width: {{ $task['priority'] === 'high' ? '100' : ($task['priority'] === 'medium' ? '70' : '40') }}%"></div>
                            </div>
                            <span class="progress-description">
                                {{ $task['description'] }}
                                <a href="{{ $task['action_url'] }}" class="float-right">
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> All tasks completed! Great job!
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Grades -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star"></i> Recent Grades
                </h3>
                <div class="card-tools">
                    <a href="{{ route('teacher.grades') }}" class="btn btn-tool">
                        <i class="fas fa-eye"></i> View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(count($recentGrades) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Subject</th>
                                    <th>Grade</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_slice($recentGrades, 0, 5) as $grade)
                                <tr>
                                    <td>{{ $grade['student_name'] }}</td>
                                    <td>{{ $grade['subject'] }}</td>
                                    <td>
                                        <span class="badge badge-{{ $grade['percentage'] >= 80 ? 'success' : ($grade['percentage'] >= 60 ? 'warning' : 'danger') }}">
                                            {{ $grade['percentage'] }}%
                                        </span>
                                    </td>
                                    <td>{{ $grade['exam_date'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No recent grades recorded.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tomorrow's Schedule -->
@if(count($upcomingSchedule) > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-plus"></i> Tomorrow's Schedule
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($upcomingSchedule as $schedule)
                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-clock"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ $schedule['time_slot'] }}</span>
                                <span class="info-box-number">{{ $schedule['class'] }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-info" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">
                                    {{ $schedule['subject'] }} | Room: {{ $schedule['room'] ?? 'TBA' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Quick Attendance Modal -->
<div class="modal fade" id="quick-attendance-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Attendance - <span id="attendance-class-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="quick-attendance-form">
                @csrf
                <input type="hidden" id="attendance-class-id" name="class_id">
                <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                <div class="modal-body">
                    <div id="attendance-students-list">
                        <!-- Students will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Mark Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Quick attendance marking
    $('.mark-attendance-btn').on('click', function() {
        const classId = $(this).data('class-id');
        const className = $(this).data('class-name');
        
        $('#attendance-class-id').val(classId);
        $('#attendance-class-name').text(className);
        
        // Load students for this class
        loadStudentsForAttendance(classId);
        
        $('#quick-attendance-modal').modal('show');
    });

    // Load students for attendance
    function loadStudentsForAttendance(classId) {
        $('#attendance-students-list').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading students...</div>');
        
        $.ajax({
            url: `/teacher/classes/${classId}/students`,
            type: 'GET',
            success: function(response) {
                let studentsHtml = '';
                response.students.forEach(function(student) {
                    studentsHtml += `
                        <div class="row mb-2 align-items-center">
                            <div class="col-md-6">
                                <strong>${student.full_name}</strong>
                                <br><small class="text-muted">${student.student_id || 'N/A'}</small>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-outline-success btn-sm active">
                                        <input type="radio" name="attendance[${student.id}][status]" value="present" checked> Present
                                    </label>
                                    <label class="btn btn-outline-danger btn-sm">
                                        <input type="radio" name="attendance[${student.id}][status]" value="absent"> Absent
                                    </label>
                                    <label class="btn btn-outline-warning btn-sm">
                                        <input type="radio" name="attendance[${student.id}][status]" value="late"> Late
                                    </label>
                                </div>
                                <input type="hidden" name="attendance[${student.id}][student_id]" value="${student.id}">
                            </div>
                        </div>
                        <hr>
                    `;
                });
                $('#attendance-students-list').html(studentsHtml);
            },
            error: function() {
                $('#attendance-students-list').html('<div class="alert alert-danger">Failed to load students</div>');
            }
        });
    }

    // Submit quick attendance
    $('#quick-attendance-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ route("teacher.dashboard.quick-attendance") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#quick-attendance-modal').modal('hide');
                toastr.success(response.message);
                
                // Refresh the page to update attendance status
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                toastr.error(response.message || 'Failed to mark attendance');
            }
        });
    });

    // Auto-refresh dashboard stats every 5 minutes
    setInterval(function() {
        $.ajax({
            url: '{{ route("teacher.dashboard.stats") }}',
            type: 'GET',
            success: function(response) {
                // Update statistics if needed
                console.log('Dashboard stats refreshed');
            }
        });
    }, 300000); // 5 minutes
});
</script>
@endpush