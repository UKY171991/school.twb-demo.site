@extends('layouts.school-admin')

@section('title', 'School Admin Dashboard')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">School Administration Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <button type="button" class="btn btn-outline-primary btn-sm mr-2" data-toggle="modal" data-target="#widgetConfigModal">
                            <i class="fas fa-cogs mr-1"></i>
                            Customize Dashboard
                        </button>
                        <ol class="breadcrumb d-inline-block mb-0">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- School Info Card -->
            @if($currentSchool)
            <div class="row">
                <div class="col-12">
                    <div class="school-info-card">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-1">{{ $currentSchool->name }}</h4>
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $currentSchool->address }}
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-phone mr-1"></i>
                                    {{ $currentSchool->phone }} | 
                                    <i class="fas fa-envelope mr-1"></i>
                                    {{ $currentSchool->email }}
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="school-logo">
                                    @if($currentSchool->logo)
                                        <img src="{{ $currentSchool->getLogoUrlAttribute() }}" alt="School Logo" class="img-fluid" style="max-height: 60px;">
                                    @else
                                        <i class="fas fa-school fa-3x"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bolt mr-2"></i>
                                Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($quickActions as $action)
                                <div class="col-md-2 col-sm-4 col-6 mb-3">
                                    <a href="{{ $action['url'] }}" class="btn btn-{{ $action['color'] }} btn-block quick-action-btn">
                                        <i class="{{ $action['icon'] }} fa-2x mb-2"></i>
                                        <br>
                                        <small>{{ $action['title'] }}</small>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Statistics Cards -->
            <div class="row">
                <!-- Students Card -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="students-count">{{ $stats['total_students'] ?? 0 }}</h3>
                            <p>Total Students</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <a href="{{ route('admin.students.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Teachers Card -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="teachers-count">{{ $stats['total_teachers'] ?? 0 }}</h3>
                            <p>Total Teachers</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <a href="{{ route('admin.teachers.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Classes Card -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="classes-count">{{ $stats['total_classes'] ?? 0 }}</h3>
                            <p>Total Classes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <a href="{{ route('admin.classes.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Subjects Card -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="subjects-count">{{ $stats['total_subjects'] ?? 0 }}</h3>
                            <p>Total Subjects</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('admin.subjects.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Attendance Today -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3 id="attendance-rate">{{ $stats['attendance_rate'] ?? 0 }}%</h3>
                            <p>Today's Attendance</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <a href="{{ route('admin.attendance.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Recent Enrollments -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3 id="recent-enrollments">{{ $stats['recent_enrollments'] ?? 0 }}</h3>
                            <p>New This Month</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <a href="{{ route('admin.students.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Average Grade -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-dark">
                        <div class="inner">
                            <h3 id="average-grade">{{ $stats['average_grade'] ?? 0 }}</h3>
                            <p>Average Grade</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <a href="{{ route('admin.grades.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Student-Teacher Ratio -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3 id="student-teacher-ratio" class="text-dark">{{ $stats['student_teacher_ratio'] ?? 0 }}:1</h3>
                            <p class="text-dark">Student-Teacher Ratio</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-balance-scale text-dark"></i>
                        </div>
                        <div class="small-box-footer bg-light">
                            <span class="text-dark">Ratio Analysis</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="row">
                <!-- Enrollment Trends -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>
                                Enrollment Trends
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="enrollmentTrendsChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Class Performance -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Class Performance
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="classPerformanceChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teacher Workload and Attendance -->
            <div class="row">
                <!-- Teacher Workload -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users mr-2"></i>
                                Teacher Workload
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="teacherWorkloadTable">
                                    <thead>
                                        <tr>
                                            <th>Teacher</th>
                                            <th>Subjects</th>
                                            <th>Classes</th>
                                            <th>Students</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Attendance Summary -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-check mr-2"></i>
                                Today's Attendance Summary
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-success">
                                            <i class="fas fa-check"></i> {{ $stats['present_today'] ?? 0 }}
                                        </span>
                                        <h5 class="description-header">Present</h5>
                                        <span class="description-text">Students</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-danger">
                                            <i class="fas fa-times"></i> {{ $stats['absent_today'] ?? 0 }}
                                        </span>
                                        <h5 class="description-header">Absent</h5>
                                        <span class="description-text">Students</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="description-block">
                                        <span class="description-percentage text-info">
                                            <i class="fas fa-percentage"></i> {{ $stats['attendance_rate'] ?? 0 }}%
                                        </span>
                                        <h5 class="description-header">Rate</h5>
                                        <span class="description-text">Overall</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities and Upcoming Events -->
            <div class="row">
                <!-- Recent Admissions -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-plus mr-2"></i>
                                Recent Admissions
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Class</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recent-admissions">
                                        @forelse($recentStudents ?? [] as $student)
                                            <tr>
                                                <td>{{ $student->user->name }}</td>
                                                <td>{{ $student->classModel->name ?? 'N/A' }}</td>
                                                <td>{{ $student->created_at->format('d M') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">No recent admissions</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('admin.students.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> View All Students
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Upcoming Events
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                @forelse($upcomingEvents ?? [] as $event)
                                    <li class="item">
                                        <div class="product-img">
                                            <i class="fas fa-calendar text-{{ $event['type'] === 'exam' ? 'danger' : ($event['type'] === 'meeting' ? 'warning' : 'info') }}"></i>
                                        </div>
                                        <div class="product-info">
                                            <a href="#" class="product-title">{{ $event['title'] }}
                                                <span class="badge badge-{{ $event['type'] === 'exam' ? 'danger' : ($event['type'] === 'meeting' ? 'warning' : 'info') }} float-right">
                                                    {{ $event['date']->format('M d') }}
                                                </span>
                                            </a>
                                            <span class="product-description">
                                                {{ $event['description'] }}
                                            </span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="item text-center">
                                        No upcoming events
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="card-footer text-center">
                            <a href="#" class="btn btn-primary btn-sm">
                                <i class="fas fa-calendar"></i> View Calendar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Activity Log -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-2"></i>
                                Recent Activity
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Action</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody id="activity-log">
                                        @forelse($recentActivities ?? [] as $activity)
                                            <tr>
                                                <td>{{ $activity->user->name ?? 'System' }}</td>
                                                <td>{{ $activity->description ?? 'Activity' }}</td>
                                                <td>{{ $activity->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">No recent activity</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="#" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> View Full Log
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Include Widget Configuration Modal -->
@include('admin.partials.widget-config-modal')
@endsection

@push('styles')
<style>
    .dashboard-widget {
        transition: all 0.3s ease;
    }
    
    .dashboard-widget:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .metric-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .metric-value {
        font-size: 2.5rem;
        font-weight: bold;
        color: #007bff;
    }
    
    .metric-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin-top: 5px;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Load dashboard statistics
    loadDashboardStats();
    
    // Initialize charts
    initEnrollmentTrendsChart();
    initClassPerformanceChart();
    
    // Load dynamic data
    loadTeacherWorkload();
    
    // Refresh data periodically
    setInterval(loadDashboardStats, 30000);
    setInterval(loadTeacherWorkload, 60000);
});

function loadDashboardStats() {
    $.ajax({
        url: '{{ route("admin.dashboard.stats") }}',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                updateStatsDisplay(response.data);
            }
        },
        error: function(xhr) {
            console.log('Error loading dashboard stats');
        }
    });
}

function updateStatsDisplay(data) {
    $('#students-count').text(data.total_students || 0);
    $('#teachers-count').text(data.total_teachers || 0);
    $('#classes-count').text(data.total_classes || 0);
    $('#subjects-count').text(data.total_subjects || 0);
    $('#attendance-rate').text((data.attendance_rate || 0) + '%');
    $('#recent-enrollments').text(data.recent_enrollments || 0);
    $('#average-grade').text(data.average_grade || 0);
    $('#student-teacher-ratio').text((data.student_teacher_ratio || 0) + ':1');
}

function initEnrollmentTrendsChart() {
    $.ajax({
        url: '{{ route("admin.dashboard.enrollment-trends") }}',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const ctx = document.getElementById('enrollmentTrendsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: response.data.months,
                        datasets: response.data.datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Monthly Enrollment Trends'
                            }
                        }
                    }
                });
            }
        }
    });
}

function initClassPerformanceChart() {
    $.ajax({
        url: '{{ route("admin.dashboard.class-performance") }}',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const ctx = document.getElementById('classPerformanceChart').getContext('2d');
                const classes = response.data.classes;
                
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: classes.map(c => c.name),
                        datasets: [{
                            label: 'Students',
                            data: classes.map(c => c.students),
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                            yAxisID: 'y'
                        }, {
                            label: 'Average Grade',
                            data: classes.map(c => c.average_grade),
                            backgroundColor: 'rgba(255, 99, 132, 0.8)',
                            yAxisID: 'y1'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                beginAtZero: true
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                beginAtZero: true,
                                grid: {
                                    drawOnChartArea: false,
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Class Performance Overview'
                            }
                        }
                    }
                });
            }
        }
    });
}

function loadTeacherWorkload() {
    $.ajax({
        url: '{{ route("admin.dashboard.teacher-workload") }}',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                let html = '';
                response.data.teachers.forEach(teacher => {
                    html += `<tr>
                        <td>${teacher.name}</td>
                        <td><span class="badge badge-info">${teacher.subjects}</span></td>
                        <td><span class="badge badge-success">${teacher.classes}</span></td>
                        <td><span class="badge badge-warning">${teacher.students}</span></td>
                    </tr>`;
                });
                $('#teacherWorkloadTable tbody').html(html);
            }
        }
    });
}

// Utility functions
function showSuccess(message) {
    toastr.success(message);
}

function showError(message) {
    toastr.error(message);
}

function showInfo(message) {
    toastr.info(message);
}
</script>
@endpush
