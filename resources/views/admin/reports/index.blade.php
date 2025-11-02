@extends('layouts.admin')

@section('title', 'Academic Reports')
@section('page-title', 'Academic Reports')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $statistics['total_students'] }}</h3>
                <p>Total Students</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $statistics['total_teachers'] }}</h3>
                <p>Total Teachers</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $statistics['total_classes'] }}</h3>
                <p>Total Classes</p>
            </div>
            <div class="icon">
                <i class="fas fa-door-open"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $statistics['recent_grades'] }}</h3>
                <p>Recent Grades</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Report Generation -->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Quick Report Generation</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <a href="{{ route('admin.reports.students') }}" class="btn btn-block btn-outline-primary">
                            <i class="fas fa-user-graduate"></i> Student Academic Reports
                        </a>
                    </div>
                    <div class="col-12 mb-3">
                        <a href="{{ route('admin.reports.teachers') }}" class="btn btn-block btn-outline-success">
                            <i class="fas fa-chalkboard-teacher"></i> Teacher Performance Reports
                        </a>
                    </div>
                    <div class="col-12 mb-3">
                        <a href="{{ route('admin.reports.attendance') }}" class="btn btn-block btn-outline-warning">
                            <i class="fas fa-calendar-check"></i> Attendance Reports
                        </a>
                    </div>
                    <div class="col-12 mb-3">
                        <a href="{{ route('admin.reports.grades') }}" class="btn btn-block btn-outline-info">
                            <i class="fas fa-chart-bar"></i> Grades Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Performance Analytics -->
    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Class Performance Analytics</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-primary" id="refresh-analytics">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="analytics-academic-year">Academic Year</label>
                    <select class="form-control" id="analytics-academic-year">
                        <option value="">All Academic Years</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $year->is_current ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="class-performance-chart" style="height: 300px;">
                    <!-- Chart will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Custom Report Builder -->
    <div class="col-12">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Custom Report Builder</h3>
            </div>
            <div class="card-body">
                <form id="custom-report-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="report-type">Report Type</label>
                                <select class="form-control" id="report-type" name="report_type" required>
                                    <option value="">Select Report Type</option>
                                    <option value="students">Student Academic Report</option>
                                    <option value="teachers">Teacher Performance Report</option>
                                    <option value="attendance">Attendance Report</option>
                                    <option value="grades">Grades Report</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-class">Class</label>
                                <select class="form-control" id="filter-class" name="class_id">
                                    <option value="">All Classes</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-subject">Subject</label>
                                <select class="form-control" id="filter-subject" name="subject_id">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-academic-year">Academic Year</label>
                                <select class="form-control" id="filter-academic-year" name="academic_year_id">
                                    <option value="">All Academic Years</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ $year->is_current ? 'selected' : '' }}>
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date-from">Date From</label>
                                <input type="date" class="form-control" id="date-from" name="date_from">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date-to">Date To</label>
                                <input type="date" class="form-control" id="date-to" name="date_to">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="output-format">Output Format</label>
                                <select class="form-control" id="output-format" name="format">
                                    <option value="html">View Online</option>
                                    <option value="pdf">Download PDF</option>
                                    <option value="excel">Download Excel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-chart-line"></i> Generate Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Parent Communication -->
    <div class="col-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Parent Communication</h3>
            </div>
            <div class="card-body">
                <form id="progress-report-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="comm-class">Class</label>
                                <select class="form-control" id="comm-class" name="class_id">
                                    <option value="">All Classes</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="report-type-comm">Report Type</label>
                                <select class="form-control" id="report-type-comm" name="report_type" required>
                                    <option value="academic">Academic Performance</option>
                                    <option value="attendance">Attendance Summary</option>
                                    <option value="comprehensive">Comprehensive Report</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-paper-plane"></i> Send Progress Reports
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="custom-message">Custom Message (Optional)</label>
                                <textarea class="form-control" id="custom-message" name="message" rows="3" 
                                         placeholder="Add a custom message to include with the progress reports..."></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    let performanceChart = null;

    // Load class performance analytics
    function loadClassPerformance() {
        const academicYearId = $('#analytics-academic-year').val();
        
        $.ajax({
            url: '{{ route("admin.reports.class-performance") }}',
            type: 'GET',
            data: { academic_year_id: academicYearId },
            success: function(response) {
                renderPerformanceChart(response.class_performance);
            },
            error: function(xhr) {
                toastr.error('Failed to load class performance data');
            }
        });
    }

    // Render performance chart
    function renderPerformanceChart(data) {
        const ctx = document.getElementById('class-performance-chart');
        
        if (performanceChart) {
            performanceChart.destroy();
        }

        const labels = data.map(item => item.class.full_name);
        const averageGrades = data.map(item => item.academic_performance.average_grade);
        const attendanceRates = data.map(item => item.attendance_rate);

        performanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Average Grade',
                    data: averageGrades,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                }, {
                    label: 'Attendance Rate (%)',
                    data: attendanceRates,
                    backgroundColor: 'rgba(255, 99, 132, 0.8)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
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
                        title: {
                            display: true,
                            text: 'Average Grade'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Attendance Rate (%)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }

    // Academic year change handler
    $('#analytics-academic-year').on('change', function() {
        loadClassPerformance();
    });

    // Refresh analytics
    $('#refresh-analytics').on('click', function() {
        loadClassPerformance();
    });

    // Custom report form submission
    $('#custom-report-form').on('submit', function(e) {
        e.preventDefault();
        
        const reportType = $('#report-type').val();
        if (!reportType) {
            toastr.error('Please select a report type');
            return;
        }

        const formData = $(this).serialize();
        const url = `{{ route('admin.reports.index') }}/${reportType}?${formData}`;
        
        if ($('#output-format').val() === 'html') {
            window.open(url, '_blank');
        } else {
            window.location.href = url;
        }
    });

    // Progress report form submission
    $('#progress-report-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ route("admin.reports.send-progress-reports") }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success(response.message);
                $('#progress-report-form')[0].reset();
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                toastr.error(response.message || 'Failed to send progress reports');
            }
        });
    });

    // Initial load
    loadClassPerformance();
});
</script>
@endpush