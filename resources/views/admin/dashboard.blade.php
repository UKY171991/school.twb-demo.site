@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- Schools Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="schools-count">{{ $stats['schools'] ?? 0 }}</h3>
                <p>Total Schools</p>
            </div>
            <div class="icon">
                <i class="fas fa-school"></i>
            </div>
            <a href="{{ route('admin.schools.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Teachers Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="teachers-count">{{ $stats['teachers'] ?? 0 }}</h3>
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

    <!-- Students Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="students-count">{{ $stats['students'] ?? 0 }}</h3>
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

    <!-- Classes Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="classes-count">{{ $stats['classes'] ?? 0 }}</h3>
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

    <!-- New KPI Cards -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3 id="fees-collected">{{ $stats['total_fees_collected'] ?? 0 }}</h3>
                <p>Fees Collected</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3 id="pending-fees">{{ $stats['pending_fees'] ?? 0 }}</h3>
                <p>Pending Fees</p>
            </div>
            <div class="icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-dark">
            <div class="inner">
                <h3 id="upcoming-exams">{{ $stats['upcoming_exams'] ?? 0 }}</h3>
                <p>Upcoming Exams</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Charts Section -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Analytics
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p class="text-center"><strong>Attendance Trends</strong></p>
                        <canvas id="attendanceChart" style="height: 200px;"></canvas>
                    </div>
                    <div class="col-md-4">
                        <p class="text-center"><strong>Fee Collection Trends</strong></p>
                        <canvas id="feeTrendsChart" style="height: 200px;"></canvas>
                    </div>
                    <div class="col-md-4">
                        <p class="text-center"><strong>Student Performance</strong></p>
                        <canvas id="studentPerformanceChart" style="height: 200px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Admissions -->
    <div class="col-md-6">
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
                                <th>Admission Date</th>
                            </tr>
                        </thead>
                        <tbody id="recent-admissions">
                            @forelse($recentStudents ?? [] as $student)
                                <tr>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->classModel->name ?? 'N/A' }}</td>
                                    <td>{{ $student->admission_date->format('d M, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No recent admissions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('admin.students.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> View All Students
                </a>
            </div>
        </div>
    </div>

    <!-- Pending Payments -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-money-bill-wave mr-2"></i>
                    Pending Payments
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
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody id="pending-payments">
                            <tr>
                                <td colspan="3" class="text-center">No pending payments found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="#" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye"></i> View All Payments
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Latest Notifications -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bell mr-2"></i>
                    Latest Notifications
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2" id="latest-notifications">
                    <li class="item text-center">
                        No new notifications
                    </li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="#" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye"></i> View All Notifications
                </a>
            </div>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2"></i>
                    Activity Log
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
                            @forelse($activityLog as $log)
                                <tr>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->created_at->diffForHumans() }}</td>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Load dashboard statistics
        loadDashboardStats();
        
        // Initialize charts
        initAttendanceChart();
        initFeeTrendsChart();
        initStudentPerformanceChart();

        // Load dynamic data
        loadPendingPayments();
        loadLatestNotifications();
        loadActivityLog();
        
        // Refresh stats every 30 seconds
        setInterval(loadDashboardStats, 30000);
        setInterval(loadPendingPayments, 60000);
        setInterval(loadLatestNotifications, 60000);
        setInterval(loadActivityLog, 30000);
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
        $('#schools-count').text(data.schools || 0);
        $('#teachers-count').text(data.teachers || 0);
        $('#students-count').text(data.students || 0);
        $('#classes-count').text(data.classes || 0);
        $('#fees-collected').text(data.total_fees_collected || 0);
        $('#pending-fees').text(data.pending_fees || 0);
        $('#upcoming-exams').text(data.upcoming_exams || 0);
    }

    // Chart functions (placeholders for now)
    function initAttendanceChart() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Attendance %',
                    data: [65, 59, 80, 81, 56, 55],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    function initFeeTrendsChart() {
        const ctx = document.getElementById('feeTrendsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Fees Collected',
                    data: [1200, 1900, 3000, 500, 2000, 3000],
                    backgroundColor: 'rgba(75, 192, 192, 0.8)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    function initStudentPerformanceChart() {
        const ctx = document.getElementById('studentPerformanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Excellent', 'Good', 'Average', 'Below Average'],
                datasets: [{
                    data: [300, 50, 100, 20],
                    backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    }

    // Dynamic data loading functions (placeholders for now)
    function loadPendingPayments() {
        $.ajax({
            url: '{{ route("admin.pending-payments") }}',
            type: 'GET',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(item => {
                        html += `<tr>
                            <td>${item.student_name}</td>
                            <td>${item.amount}</td>
                            <td>${item.due_date}</td>
                        </tr>`;
                    });
                    $('#pending-payments').html(html);
                } else {
                    $('#pending-payments').html('<tr><td colspan="3" class="text-center">No pending payments found</td></tr>');
                }
            }
        });
    }

    function loadLatestNotifications() {
        $.ajax({
            url: '{{ route("admin.latest-notifications") }}',
            type: 'GET',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(item => {
                        html += `<li class="item">
                            <div class="product-img">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="product-info">
                                <a href="${item.link}" class="product-title">${item.message}
                                    <span class="badge badge-warning float-right">${item.time}</span></a>
                                <span class="product-description">
                                    ${item.description}
                                </span>
                            </div>
                        </li>`;
                    });
                    $('#latest-notifications').html(html);
                } else {
                    $('#latest-notifications').html('<li class="item text-center">No new notifications</li>');
                }
            }
        });
    }

    function loadActivityLog() {
        $.ajax({
            url: '{{ route("admin.activity-log") }}',
            type: 'GET',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(log => {
                        html += `<tr>
                            <td>${log.user.name}</td>
                            <td>${log.description}</td>
                            <td>${moment(log.created_at).fromNow()}</td>
                        </tr>`;
                    });
                    $('#activity-log').html(html);
                } else {
                    $('#activity-log').html('<tr><td colspan="3" class="text-center">No recent activity</td></tr>');
                }
            }
        });
    }
@endpush
