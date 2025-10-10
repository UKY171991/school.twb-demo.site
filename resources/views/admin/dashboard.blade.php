@extends('layouts.app')

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
            <a href="{{ route('schools.index') }}" class="small-box-footer">
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
            <a href="{{ route('teachers.index') }}" class="small-box-footer">
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
            <a href="{{ route('students.index') }}" class="small-box-footer">
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
            <a href="{{ route('classes.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Schools -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-school mr-2"></i>
                    Recent Schools
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>School Name</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="recent-schools">
                            @forelse($recentSchools ?? [] as $school)
                                <tr>
                                    <td>{{ $school->name }}</td>
                                    <td>{{ $school->code }}</td>
                                    <td>
                                        <span class="badge badge-{{ $school->is_active ? 'success' : 'danger' }}">
                                            {{ $school->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('schools.show', $school->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No schools found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('schools.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> View All Schools
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Students -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-graduate mr-2"></i>
                    Recent Students
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="recent-students">
                            @forelse($recentStudents ?? [] as $student)
                                <tr>
                                    <td>{{ $student->full_name }}</td>
                                    <td>{{ $student->class->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No students found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('students.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> View All Students
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-2"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('schools.create') }}" class="btn btn-info btn-block mb-3">
                            <i class="fas fa-school mr-2"></i>
                            Add New School
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('teachers.create') }}" class="btn btn-success btn-block mb-3">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            Add New Teacher
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('students.create') }}" class="btn btn-warning btn-block mb-3">
                            <i class="fas fa-user-graduate mr-2"></i>
                            Add New Student
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('classes.create') }}" class="btn btn-danger btn-block mb-3">
                            <i class="fas fa-door-open mr-2"></i>
                            Add New Class
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-block mb-3">
                            <i class="fas fa-book mr-2"></i>
                            Add New Subject
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('attendance.create') }}" class="btn btn-secondary btn-block mb-3">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Take Attendance
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('grades.create') }}" class="btn btn-dark btn-block mb-3">
                            <i class="fas fa-star mr-2"></i>
                            Add Grades
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="#" class="btn btn-light btn-block mb-3">
                            <i class="fas fa-chart-bar mr-2"></i>
                            View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-2"></i>
                    Statistics Overview
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="statsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Load dashboard statistics
        loadDashboardStats();
        
        // Initialize chart
        initializeChart();
        
        // Refresh stats every 30 seconds
        setInterval(loadDashboardStats, 30000);
    });

    function loadDashboardStats() {
        $.ajax({
            url: '{{ route("dashboard.stats") }}',
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
    }

    function initializeChart() {
        const ctx = document.getElementById('statsChart').getContext('2d');
        
        // Get stats data from the page
        const statsData = {
            schools: {{ $stats['schools'] ?? 0 }},
            teachers: {{ $stats['teachers'] ?? 0 }},
            students: {{ $stats['students'] ?? 0 }},
            classes: {{ $stats['classes'] ?? 0 }}
        };

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Schools', 'Teachers', 'Students', 'Classes'],
                datasets: [{
                    label: 'Count',
                    data: [
                        statsData.schools,
                        statsData.teachers,
                        statsData.students,
                        statsData.classes
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
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
                        display: false
                    }
                }
            }
        });
    }

    // Quick action buttons with confirmation
    $('.btn-block').on('click', function(e) {
        const action = $(this).text().trim();
        
        // Add loading state
        const originalText = $(this).html();
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Loading...');
        
        // Re-enable button after a short delay (in case of navigation)
        setTimeout(() => {
            $(this).prop('disabled', false).html(originalText);
        }, 2000);
    });
</script>
@endsection
