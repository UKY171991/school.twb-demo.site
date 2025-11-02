@extends('layouts.superadmin')

@section('title', 'Super Admin Dashboard')

@section('content-header')
    @include('layouts.partials.content-header', [
        'title' => 'Dashboard',
        'subtitle' => 'Super Admin Overview',
        'breadcrumbs' => [
            ['title' => 'Dashboard', 'active' => true]
        ]
    ])
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards Row -->
    <div class="row">
        <!-- Total Schools -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $statistics['total_schools'] ?? 0 }}</h3>
                    <p>Total Schools</p>
                </div>
                <div class="icon">
                    <i class="fas fa-school"></i>
                </div>
                <a href="{{ route('superadmin.schools.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $statistics['total_users'] ?? 0 }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('superadmin.users.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Students -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $statistics['total_students'] ?? 0 }}</h3>
                    <p>Total Students</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $statistics['total_teachers'] ?? 0 }}</h3>
                    <p>Total Teachers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics Row -->
    <div class="row">
        <!-- Active Schools -->
        <div class="col-lg-2 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Active Schools</span>
                    <span class="info-box-number">{{ $statistics['active_schools'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- New Users This Month -->
        <div class="col-lg-2 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-user-plus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">New Users</span>
                    <span class="info-box-number">{{ $statistics['new_users_this_month'] ?? 0 }}</span>
                    <span class="info-box-more">This Month</span>
                </div>
            </div>
        </div>

        <!-- New Students This Month -->
        <div class="col-lg-2 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-graduation-cap"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">New Students</span>
                    <span class="info-box-number">{{ $statistics['new_students_this_month'] ?? 0 }}</span>
                    <span class="info-box-more">This Month</span>
                </div>
            </div>
        </div>

        <!-- Average Students per School -->
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-chart-bar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Avg Students/School</span>
                    <span class="info-box-number">{{ $statistics['average_students_per_school'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-bell"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Unread Notifications</span>
                    <span class="info-box-number">{{ $statistics['unread_notifications'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Data Row -->
    <div class="row">
        <!-- Enrollment Trends Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Enrollment Trends
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="enrollmentChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <div class="overlay" id="enrollmentChartLoader">
                    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-heartbeat mr-1"></i>
                        System Health
                    </h3>
                </div>
                <div class="card-body" id="systemHealthContent">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Loading system health...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Activity and School Performance Row -->
    <div class="row">
        <!-- User Activity Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-1"></i>
                        User Activity (Last 30 Days)
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="userActivityChart" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <div class="overlay" id="userActivityChartLoader">
                    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i>
                        Recent Activities
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" id="refreshActivities">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <div id="recentActivitiesContent">
                            <div class="text-center p-3">
                                <i class="fas fa-spinner fa-spin fa-2x"></i>
                                <p class="mt-2">Loading recent activities...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- School Performance Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-school mr-1"></i>
                        School Performance Overview
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" id="refreshSchoolPerformance">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="schoolPerformanceContent">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p class="mt-2">Loading school performance data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize charts and load data
    loadEnrollmentTrends();
    loadUserActivity();
    loadSystemHealth();
    loadRecentActivities();
    loadSchoolPerformance();

    // Refresh buttons
    $('#refreshActivities').click(function() {
        loadRecentActivities();
    });

    $('#refreshSchoolPerformance').click(function() {
        loadSchoolPerformance();
    });
});

// Load enrollment trends chart
function loadEnrollmentTrends() {
    $.ajax({
        url: '{{ route("superadmin.dashboard.enrollment-trends") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const ctx = document.getElementById('enrollmentChart').getContext('2d');
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
                            }
                        }
                    }
                });
            }
        },
        error: function() {
            $('#enrollmentChart').parent().html('<p class="text-center text-muted">Failed to load enrollment trends</p>');
        },
        complete: function() {
            $('#enrollmentChartLoader').hide();
        }
    });
}

// Load user activity chart
function loadUserActivity() {
    $.ajax({
        url: '{{ route("superadmin.dashboard.user-activity") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const ctx = document.getElementById('userActivityChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: response.data.days,
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
                            }
                        }
                    }
                });
            }
        },
        error: function() {
            $('#userActivityChart').parent().html('<p class="text-center text-muted">Failed to load user activity</p>');
        },
        complete: function() {
            $('#userActivityChartLoader').hide();
        }
    });
}

// Load system health
function loadSystemHealth() {
    $.ajax({
        url: '{{ route("superadmin.dashboard.system-health") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                let html = `
                    <div class="progress-group">
                        <span class="progress-text">User Activity Rate</span>
                        <span class="float-right"><b>${data.user_activity.recently_active}</b>/${data.user_activity.total}</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" style="width: ${data.user_activity.activity_rate}%"></div>
                        </div>
                    </div>
                    
                    <div class="progress-group">
                        <span class="progress-text">Active Schools</span>
                        <span class="float-right"><b>${data.school_status.active}</b>/${data.school_status.total}</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: ${data.school_status.active_rate}%"></div>
                        </div>
                    </div>
                    
                    <div class="progress-group">
                        <span class="progress-text">Notification Read Rate</span>
                        <span class="float-right"><b>${data.notifications.total - data.notifications.unread}</b>/${data.notifications.total}</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-info" style="width: ${data.notifications.read_rate}%"></div>
                        </div>
                    </div>
                `;
                $('#systemHealthContent').html(html);
            }
        },
        error: function() {
            $('#systemHealthContent').html('<p class="text-center text-muted">Failed to load system health</p>');
        }
    });
}

// Load recent activities
function loadRecentActivities() {
    $.ajax({
        url: '{{ route("superadmin.dashboard.recent-activities") }}',
        method: 'GET',
        success: function(response) {
            if (response.success && response.data.activities.length > 0) {
                let html = '<ul class="list-unstyled">';
                response.data.activities.forEach(function(activity) {
                    html += `
                        <li class="media">
                            <div class="media-object bg-${activity.color} elevation-1 rounded-circle p-2 mr-3">
                                <i class="${activity.icon} text-white"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="mt-0 mb-1">${activity.message}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-school mr-1"></i>${activity.school}
                                    <i class="fas fa-clock ml-2 mr-1"></i>${moment(activity.date).fromNow()}
                                </small>
                            </div>
                        </li>
                    `;
                });
                html += '</ul>';
                $('#recentActivitiesContent').html(html);
            } else {
                $('#recentActivitiesContent').html('<p class="text-center text-muted p-3">No recent activities</p>');
            }
        },
        error: function() {
            $('#recentActivitiesContent').html('<p class="text-center text-muted p-3">Failed to load recent activities</p>');
        }
    });
}

// Load school performance
function loadSchoolPerformance() {
    $.ajax({
        url: '{{ route("superadmin.dashboard.school-performance") }}',
        method: 'GET',
        success: function(response) {
            if (response.success && response.data.schools.length > 0) {
                let html = `
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>School Name</th>
                                    <th>Students</th>
                                    <th>Teachers</th>
                                    <th>S/T Ratio</th>
                                    <th>Attendance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                response.data.schools.forEach(function(school) {
                    const statusBadge = school.status === 'Active' ? 'badge-success' : 'badge-danger';
                    html += `
                        <tr>
                            <td><strong>${school.name}</strong></td>
                            <td>${school.students}</td>
                            <td>${school.teachers}</td>
                            <td>${school.student_teacher_ratio}:1</td>
                            <td>${school.attendance_rate}%</td>
                            <td><span class="badge ${statusBadge}">${school.status}</span></td>
                        </tr>
                    `;
                });
                
                html += `
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="description-block">
                                <h5 class="description-header">${response.data.summary.total_schools}</h5>
                                <span class="description-text">TOTAL SCHOOLS</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block">
                                <h5 class="description-header">${Math.round(response.data.summary.avg_students_per_school)}</h5>
                                <span class="description-text">AVG STUDENTS</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block">
                                <h5 class="description-header">${Math.round(response.data.summary.avg_teachers_per_school)}</h5>
                                <span class="description-text">AVG TEACHERS</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block">
                                <h5 class="description-header">${Math.round(response.data.summary.avg_attendance_rate)}%</h5>
                                <span class="description-text">AVG ATTENDANCE</span>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#schoolPerformanceContent').html(html);
            } else {
                $('#schoolPerformanceContent').html('<p class="text-center text-muted">No school performance data available</p>');
            }
        },
        error: function() {
            $('#schoolPerformanceContent').html('<p class="text-center text-muted">Failed to load school performance data</p>');
        }
    });
}
</script>
@endpush

@push('styles')
<style>
.media {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #dee2e6;
}

.media:last-child {
    border-bottom: none;
}

.media-object {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.progress-group {
    margin-bottom: 1rem;
}

.description-block {
    text-align: center;
}

.description-header {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.25rem;
}

.description-text {
    font-size: 0.875rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
}
</style>
@endpush