@extends('layouts.tc')

@section('title', 'Reports & Analytics')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reports & Analytics</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Report Statistics -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
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
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['recent_grades'] ?? 0 }}</h3>
                        <p>Recent Grades</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['recent_communications'] ?? 0 }}</h3>
                        <p>Recent Messages</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['average_performance'] ?? 0 }}%</h3>
                        <p>Avg Performance</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Types -->
        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Class Performance Reports
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Generate detailed performance reports for your classes including grade distribution, improvement tracking, and attendance correlation.</p>
                        
                        <form id="classPerformanceForm">
                            <div class="form-group">
                                <label for="class_id">Select Class</label>
                                <select class="form-control" id="class_id" name="class_id" required>
                                    <option value="">Choose a class...</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="date_from">From Date</label>
                                        <input type="date" class="form-control" id="date_from" name="date_from" 
                                               value="{{ now()->subMonth()->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="date_to">To Date</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to" 
                                               value="{{ now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Generate Report
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-comments mr-2"></i>
                            Parent Communication Log
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Track your communication with parents including message history, response rates, and engagement metrics.</p>
                        
                        <form id="communicationForm">
                            <div class="form-group">
                                <label for="comm_class_id">Filter by Class (Optional)</label>
                                <select class="form-control" id="comm_class_id" name="class_id">
                                    <option value="">All classes...</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="comm_date_from">From Date</label>
                                        <input type="date" class="form-control" id="comm_date_from" name="date_from" 
                                               value="{{ now()->subMonth()->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="comm_date_to">To Date</label>
                                        <input type="date" class="form-control" id="comm_date_to" name="date_to" 
                                               value="{{ now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-comments mr-2"></i>
                                View Communication Log
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-check mr-2"></i>
                            Teaching Effectiveness
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Analyze your teaching effectiveness with comprehensive metrics including student feedback and performance analytics.</p>
                        
                        <form id="effectivenessForm">
                            <div class="form-group">
                                <label for="eff_class_id">Filter by Class (Optional)</label>
                                <select class="form-control" id="eff_class_id" name="class_id">
                                    <option value="">All classes...</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="eff_date_from">From Date</label>
                                        <input type="date" class="form-control" id="eff_date_from" name="date_from" 
                                               value="{{ now()->subMonth()->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="eff_date_to">To Date</label>
                                        <input type="date" class="form-control" id="eff_date_to" name="date_to" 
                                               value="{{ now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-user-check mr-2"></i>
                                Analyze Effectiveness
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-2"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-outline-primary btn-block" onclick="generateQuickReport('weekly_summary')">
                                    <i class="fas fa-calendar-week mr-2"></i>
                                    Weekly Summary
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-success btn-block" onclick="generateQuickReport('monthly_performance')">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    Monthly Performance
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-warning btn-block" onclick="generateQuickReport('student_alerts')">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Student Alerts
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-info btn-block" onclick="exportAllReports()">
                                    <i class="fas fa-download mr-2"></i>
                                    Export All Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="reportModalTitle">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Report Results
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="reportModalBody">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Generating report...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="exportReportBtn" style="display: none;">
                        <i class="fas fa-download mr-2"></i>
                        Export Report
                    </button>
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
        
        .card {
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .btn-outline-primary:hover,
        .btn-outline-success:hover,
        .btn-outline-warning:hover,
        .btn-outline-info:hover {
            transform: translateY(-1px);
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Class Performance Report Form
    $('#classPerformanceForm').submit(function(e) {
        e.preventDefault();
        generateClassPerformanceReport();
    });
    
    // Communication Report Form
    $('#communicationForm').submit(function(e) {
        e.preventDefault();
        generateCommunicationReport();
    });
    
    // Effectiveness Report Form
    $('#effectivenessForm').submit(function(e) {
        e.preventDefault();
        generateEffectivenessReport();
    });
});

function generateClassPerformanceReport() {
    const formData = $('#classPerformanceForm').serialize();
    
    if (!$('#class_id').val()) {
        toastr.error('Please select a class');
        return;
    }
    
    showReportModal('Class Performance Report');
    
    $.ajax({
        url: '{{ route("teacher.reports.class-performance") }}',
        method: 'GET',
        data: formData,
        success: function(response) {
            if (response.success) {
                displayClassPerformanceReport(response.data);
            } else {
                toastr.error('Failed to generate report');
                $('#reportModal').modal('hide');
            }
        },
        error: function() {
            toastr.error('Failed to generate report');
            $('#reportModal').modal('hide');
        }
    });
}

function generateCommunicationReport() {
    const formData = $('#communicationForm').serialize();
    
    showReportModal('Parent Communication Log');
    
    $.ajax({
        url: '{{ route("teacher.reports.parent-communication") }}',
        method: 'GET',
        data: formData,
        success: function(response) {
            if (response.success) {
                displayCommunicationReport(response.data);
            } else {
                toastr.error('Failed to generate report');
                $('#reportModal').modal('hide');
            }
        },
        error: function() {
            toastr.error('Failed to generate report');
            $('#reportModal').modal('hide');
        }
    });
}

function generateEffectivenessReport() {
    const formData = $('#effectivenessForm').serialize();
    
    showReportModal('Teaching Effectiveness Analysis');
    
    $.ajax({
        url: '{{ route("teacher.reports.teaching-effectiveness") }}',
        method: 'GET',
        data: formData,
        success: function(response) {
            if (response.success) {
                displayEffectivenessReport(response.data);
            } else {
                toastr.error('Failed to generate report');
                $('#reportModal').modal('hide');
            }
        },
        error: function() {
            toastr.error('Failed to generate report');
            $('#reportModal').modal('hide');
        }
    });
}

function showReportModal(title) {
    $('#reportModalTitle').html('<i class="fas fa-chart-bar mr-2"></i>' + title);
    $('#reportModalBody').html(`
        <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">Generating report...</p>
        </div>
    `);
    $('#exportReportBtn').hide();
    $('#reportModal').modal('show');
}

function displayClassPerformanceReport(data) {
    let html = `
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Grade Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="gradeDistributionChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Summary Statistics</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><td>Total Students:</td><td><strong>${data.summary.total_students}</strong></td></tr>
                            <tr><td>Total Grades:</td><td><strong>${data.summary.total_grades}</strong></td></tr>
                            <tr><td>Average Grade:</td><td><strong>${data.summary.average_grade.toFixed(1)}%</strong></td></tr>
                            <tr><td>Passing Rate:</td><td><strong>${data.summary.passing_rate.toFixed(1)}%</strong></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    if (data.improvement_tracking.length > 0) {
        html += `
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Student Improvement Tracking</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>First Grade</th>
                                            <th>Last Grade</th>
                                            <th>Improvement</th>
                                            <th>Trend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
        `;
        
        data.improvement_tracking.forEach(student => {
            const trendColor = student.trend === 'improving' ? 'success' : 
                              student.trend === 'declining' ? 'danger' : 'warning';
            const improvementSign = student.improvement > 0 ? '+' : '';
            
            html += `
                <tr>
                    <td>${student.student_name}</td>
                    <td>${student.first_grade.toFixed(1)}%</td>
                    <td>${student.last_grade.toFixed(1)}%</td>
                    <td>${improvementSign}${student.improvement.toFixed(1)}%</td>
                    <td><span class="badge badge-${trendColor}">${student.trend}</span></td>
                </tr>
            `;
        });
        
        html += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    $('#reportModalBody').html(html);
    $('#exportReportBtn').show();
    
    // Create grade distribution chart
    setTimeout(() => {
        createGradeDistributionChart(data.grade_distribution);
    }, 100);
}

function displayCommunicationReport(data) {
    let html = `
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Communication Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-envelope"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Messages</span>
                                        <span class="info-box-number">${data.stats.total_messages}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-eye"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Read Messages</span>
                                        <span class="info-box-number">${data.stats.read_messages}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-percentage"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Response Rate</span>
                                        <span class="info-box-number">${data.stats.response_rate}%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger"><i class="fas fa-calendar-day"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Avg/Day</span>
                                        <span class="info-box-number">${data.stats.avg_messages_per_day}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#reportModalBody').html(html);
    $('#exportReportBtn').show();
}

function displayEffectivenessReport(data) {
    let html = `
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Overall Effectiveness Metrics</h5>
                    </div>
                    <div class="card-body">
                        <div class="progress-group">
                            <span class="progress-text">Overall Grade Average</span>
                            <span class="float-right"><b>${data.overall_metrics.overall_grade}%</b></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" style="width: ${data.overall_metrics.overall_grade}%"></div>
                            </div>
                        </div>
                        <div class="progress-group">
                            <span class="progress-text">Passing Rate</span>
                            <span class="float-right"><b>${data.overall_metrics.overall_passing_rate}%</b></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: ${data.overall_metrics.overall_passing_rate}%"></div>
                            </div>
                        </div>
                        <div class="progress-group">
                            <span class="progress-text">Attendance Rate</span>
                            <span class="float-right"><b>${data.overall_metrics.overall_attendance}%</b></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-warning" style="width: ${data.overall_metrics.overall_attendance}%"></div>
                            </div>
                        </div>
                        <div class="progress-group">
                            <span class="progress-text">Improvement Rate</span>
                            <span class="float-right"><b>${data.overall_metrics.overall_improvement_rate}%</b></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-info" style="width: ${data.overall_metrics.overall_improvement_rate}%"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h4>Effectiveness Score</h4>
                            <h2 class="text-primary">${data.overall_metrics.effectiveness_score}%</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Recommendations</h5>
                    </div>
                    <div class="card-body">
    `;
    
    if (data.recommendations.length > 0) {
        data.recommendations.forEach(rec => {
            const priorityColor = rec.priority === 'high' ? 'danger' : 
                                 rec.priority === 'medium' ? 'warning' : 'info';
            html += `
                <div class="alert alert-${priorityColor}">
                    <h6><i class="fas fa-lightbulb mr-2"></i>${rec.class}</h6>
                    <p class="mb-0">${rec.message}</p>
                </div>
            `;
        });
    } else {
        html += '<div class="alert alert-success"><i class="fas fa-check-circle mr-2"></i>Great job! No specific recommendations at this time.</div>';
    }
    
    html += `
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#reportModalBody').html(html);
    $('#exportReportBtn').show();
}

function createGradeDistributionChart(distribution) {
    const ctx = document.getElementById('gradeDistributionChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(distribution),
            datasets: [{
                data: Object.values(distribution).map(d => d.count),
                backgroundColor: [
                    '#28a745', // A - Green
                    '#17a2b8', // B - Cyan
                    '#ffc107', // C - Yellow
                    '#fd7e14', // D - Orange
                    '#dc3545'  // F - Red
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });
}

function generateQuickReport(type) {
    toastr.info('Quick report generation will be implemented for: ' + type);
}

function exportAllReports() {
    toastr.info('Export functionality will be implemented');
}
</script>
@stop