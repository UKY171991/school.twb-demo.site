@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Welcome, {{ $student->first_name }}!</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Quick Stats -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $dashboardData['quick_stats']['total_subjects'] }}</h3>
                        <p>Total Subjects</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $dashboardData['attendance_summary']['semester']['percentage'] }}%</h3>
                        <p>Attendance Rate</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $dashboardData['performance_metrics']['overall_average'] }}%</h3>
                        <p>Overall Average</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $dashboardData['quick_stats']['unread_notifications'] }}</h3>
                        <p>New Notifications</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Overview -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            Academic Overview
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Student ID:</strong></td>
                                        <td>{{ $dashboardData['academic_overview']['student_id'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Class:</strong></td>
                                        <td>{{ $dashboardData['academic_overview']['class_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>School:</strong></td>
                                        <td>{{ $dashboardData['academic_overview']['school_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Class Teacher:</strong></td>
                                        <td>{{ $dashboardData['academic_overview']['class_teacher'] }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Academic Year:</strong></td>
                                        <td>{{ $dashboardData['academic_overview']['academic_year'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Current Semester:</strong></td>
                                        <td>{{ $dashboardData['academic_overview']['current_semester'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Enrollment Date:</strong></td>
                                        <td>{{ $dashboardData['academic_overview']['enrollment_date']->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Performance Status:</strong></td>
                                        <td>
                                            <span class="badge badge-{{ $dashboardData['performance_metrics']['performance_status'] === 'excellent' ? 'success' : ($dashboardData['performance_metrics']['performance_status'] === 'good' ? 'primary' : ($dashboardData['performance_metrics']['performance_status'] === 'satisfactory' ? 'warning' : 'danger')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $dashboardData['performance_metrics']['performance_status'])) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clock mr-2"></i>
                            Next Class
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($dashboardData['class_schedule']['next_class'])
                            <div class="text-center">
                                <h4 class="text-primary">{{ $dashboardData['class_schedule']['next_class']['subject'] }}</h4>
                                <p class="text-muted">{{ $dashboardData['class_schedule']['next_class']['time'] }}</p>
                                <p><strong>Teacher:</strong> {{ $dashboardData['class_schedule']['next_class']['teacher'] }}</p>
                                <p><strong>Room:</strong> {{ $dashboardData['class_schedule']['next_class']['room'] }}</p>
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                <p>No more classes today</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Grades and Attendance -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-star mr-2"></i>
                            Current Grades
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('student.grades') }}" class="btn btn-sm btn-primary">
                                View All Grades
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(count($dashboardData['current_grades']) > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Average</th>
                                            <th>Grade</th>
                                            <th>Trend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dashboardData['current_grades'] as $grade)
                                            <tr>
                                                <td>
                                                    <strong>{{ $grade['subject_name'] }}</strong>
                                                    <br><small class="text-muted">{{ $grade['subject_code'] }}</small>
                                                </td>
                                                <td>{{ $grade['average_grade'] }}%</td>
                                                <td>
                                                    <span class="badge badge-{{ $grade['letter_grade'] === 'A' ? 'success' : ($grade['letter_grade'] === 'B' ? 'primary' : ($grade['letter_grade'] === 'C' ? 'warning' : 'danger')) }}">
                                                        {{ $grade['letter_grade'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($grade['grade_trend'] === 'improving')
                                                        <i class="fas fa-arrow-up text-success"></i>
                                                    @elseif($grade['grade_trend'] === 'declining')
                                                        <i class="fas fa-arrow-down text-danger"></i>
                                                    @else
                                                        <i class="fas fa-minus text-muted"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-star fa-2x mb-2"></i>
                                <p>No grades available yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Attendance Summary
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('student.attendance') }}" class="btn btn-sm btn-success">
                                View Details
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-success">
                                        {{ $dashboardData['attendance_summary']['monthly']['percentage'] }}%
                                    </span>
                                    <h5 class="description-header">{{ $dashboardData['attendance_summary']['monthly']['present'] }}/{{ $dashboardData['attendance_summary']['monthly']['total'] }}</h5>
                                    <span class="description-text">This Month</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="description-block">
                                    <span class="description-percentage text-info">
                                        {{ $dashboardData['attendance_summary']['semester']['percentage'] }}%
                                    </span>
                                    <h5 class="description-header">{{ $dashboardData['attendance_summary']['semester']['present'] }}/{{ $dashboardData['attendance_summary']['semester']['total'] }}</h5>
                                    <span class="description-text">This Semester</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="progress progress-sm mt-3">
                            <div class="progress-bar bg-{{ $dashboardData['attendance_summary']['attendance_status'] === 'excellent' ? 'success' : ($dashboardData['attendance_summary']['attendance_status'] === 'good' ? 'primary' : ($dashboardData['attendance_summary']['attendance_status'] === 'satisfactory' ? 'warning' : 'danger')) }}" 
                                 style="width: {{ $dashboardData['attendance_summary']['semester']['percentage'] }}%"></div>
                        </div>
                        <small class="text-muted">
                            Status: {{ ucfirst(str_replace('_', ' ', $dashboardData['attendance_summary']['attendance_status'])) }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Schedule and Upcoming Events -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-day mr-2"></i>
                            Today's Schedule
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(count($dashboardData['class_schedule']['today_schedule']) > 0)
                            <div class="timeline">
                                @foreach($dashboardData['class_schedule']['today_schedule'] as $class)
                                    <div class="time-label">
                                        <span class="bg-primary">{{ $class['time'] }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-book bg-blue"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header">{{ $class['subject'] }}</h3>
                                            <div class="timeline-body">
                                                <strong>Teacher:</strong> {{ $class['teacher'] }}<br>
                                                <strong>Room:</strong> {{ $class['room'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                <p>No classes scheduled for today</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Upcoming Events
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(count($dashboardData['upcoming_events']) > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($dashboardData['upcoming_events'] as $event)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">{{ $event['title'] }}</div>
                                            <small class="text-muted">{{ $event['description'] }}</small>
                                            <br><small class="text-info">{{ \Carbon\Carbon::parse($event['date'])->format('M d, Y') }}</small>
                                        </div>
                                        <span class="badge badge-{{ $event['priority'] === 'high' ? 'danger' : ($event['priority'] === 'medium' ? 'warning' : 'info') }} badge-pill">
                                            {{ ucfirst($event['type']) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-calendar fa-2x mb-2"></i>
                                <p>No upcoming events</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bell mr-2"></i>
                            Recent Notifications
                        </h3>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-info" onclick="markAllAsRead()">
                                Mark All as Read
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(count($dashboardData['recent_notifications']) > 0)
                            <div class="list-group">
                                @foreach($dashboardData['recent_notifications'] as $notification)
                                    <div class="list-group-item {{ !$notification['is_read'] ? 'list-group-item-light' : '' }}">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">
                                                @if(!$notification['is_read'])
                                                    <i class="fas fa-circle text-primary" style="font-size: 8px;"></i>
                                                @endif
                                                {{ $notification['title'] }}
                                            </h6>
                                            <small>{{ $notification['time_ago'] }}</small>
                                        </div>
                                        <p class="mb-1">{{ $notification['message'] }}</p>
                                        <small class="text-muted">
                                            <span class="badge badge-{{ $notification['type'] === 'success' ? 'success' : ($notification['type'] === 'warning' ? 'warning' : ($notification['type'] === 'error' ? 'danger' : 'info')) }}">
                                                {{ ucfirst($notification['type']) }}
                                            </span>
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                <p>No recent notifications</p>
                            </div>
                        @endif
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
        
        .description-block {
            text-align: center;
        }
        
        .description-header {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .description-text {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ddd;
            left: 31px;
            margin: 0;
            border-radius: 2px;
        }
        
        .timeline > .time-label > span {
            font-weight: 600;
            color: #fff;
            font-size: 12px;
            padding: 5px 10px;
            display: inline-block;
            border-radius: 4px;
        }
        
        .timeline-item {
            background: #fff;
            border-radius: 3px;
            width: calc(100% - 45px);
            margin-left: 45px;
            margin-top: 10px;
            color: #444;
            padding: 10px;
            position: relative;
            border-left: 3px solid #007bff;
        }
        
        .timeline-header {
            margin: 0;
            color: #555;
            font-size: 16px;
            font-weight: 600;
        }
        
        .timeline-body {
            padding-top: 10px;
        }
    </style>
@stop

@section('js')
<script>
function markAllAsRead() {
    // AJAX call to mark all notifications as read
    $.ajax({
        url: '/ajax/notifications/mark-all-read',
        method: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('All notifications marked as read');
                // Remove the unread indicators
                $('.list-group-item .fa-circle').remove();
                $('.list-group-item-light').removeClass('list-group-item-light');
            }
        },
        error: function() {
            toastr.error('Failed to mark notifications as read');
        }
    });
}
</script>
@stop