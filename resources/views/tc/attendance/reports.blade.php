@extends('layouts.tc')

@section('title', 'Attendance Reports')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Attendance Reports</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.attendance.index') }}">Attendance</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Report Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-filter mr-2"></i>
                            Report Filters
                        </h3>
                    </div>
                    <form id="reportForm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="class_id">Class</label>
                                        <select class="form-control" id="class_id" name="class_id">
                                            <option value="">All Classes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" 
                                               value="{{ date('Y-m-01') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" 
                                               value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="format">Export Format</label>
                                        <select class="form-control" id="format" name="format">
                                            <option value="pdf">PDF</option>
                                            <option value="excel">Excel</option>
                                            <option value="csv">CSV</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-info" id="generateReportBtn">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Generate Report
                            </button>
                            <button type="button" class="btn btn-success" id="exportReportBtn">
                                <i class="fas fa-download mr-2"></i>
                                Export Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Analytics Dashboard -->
        <div class="row" id="analyticsSection" style="display: none;">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-2"></i>
                            Daily Attendance Trends
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceTrendsChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-pie-chart mr-2"></i>
                            Attendance Summary
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceSummaryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Attendance Students -->
        <div class="row" id="lowAttendanceSection" style="display: none;">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Students with Low Attendance (Below 75%)
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" id="lowAttendanceTable">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Total Days</th>
                                    <th>Present Days</th>
                                    <th>Attendance %</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="lowAttendanceTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Summary -->
        <div class="row" id="classSummarySection" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Class-wise Attendance Summary
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" id="classSummaryTable">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Total Records</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>Late</th>
                                    <th>Excused</th>
                                    <th>Attendance Rate</th>
                                </tr>
                            </thead>
                            <tbody id="classSummaryTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .analytics-card {
            transition: transform 0.2s;
        }
        
        .analytics-card:hover {
            transform: translateY(-2px);
        }
        
        .attendance-percentage {
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        .status-excellent { color: #28a745; }
        .status-good { color: #17a2b8; }
        .status-average { color: #ffc107; }
        .status-poor { color: #dc3545; }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    loadClasses();
    
    $('#generateReportBtn').click(function() {
        generateAnalytics();
    });
    
    $('#exportReportBtn').click(function() {
        exportReport();
    });
    
    function loadClasses() {
        $.ajax({
            url: '{{ route("ajax.teacher.classes") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const select = $('#class_id');
                    select.empty().append('<option value="">All Classes</option>');
                    
                    response.data.forEach(function(cls) {
                        select.append(`<option value="${cls.id}">${cls.full_name}</option>`);
                    });
                }
            }
        });
    }
    
    function generateAnalytics() {
        const formData = $('#reportForm').serialize();
        
        $('#generateReportBtn').html('<i class="fas fa-spinner fa-spin mr-2"></i>Generating...');
        $('#generateReportBtn').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("teacher.attendance.analytics") }}',
            method: 'GET',
            data: formData,
            success: function(response) {
                if (response.success) {
                    displayAnalytics(response.data);
                    showAnalyticsSections();
                } else {
                    toastr.error(response.message || 'Failed to generate analytics');
                }
            },
            error: function() {
                toastr.error('Failed to generate analytics');
            },
            complete: function() {
                $('#generateReportBtn').html('<i class="fas fa-chart-bar mr-2"></i>Generate Report');
                $('#generateReportBtn').prop('disabled', false);
            }
        });
    }
    
    function displayAnalytics(data) {
        // Display daily trends chart
        displayDailyTrendsChart(data.daily_trends);
        
        // Display attendance summary chart
        displayAttendanceSummaryChart(data.class_summary);
        
        // Display low attendance students
        displayLowAttendanceStudents(data.low_attendance_students);
        
        // Display class summary
        displayClassSummary(data.class_summary);
    }
    
    function showAnalyticsSections() {
        $('#analyticsSection').show();
        $('#lowAttendanceSection').show();
        $('#classSummarySection').show();
    }
    
    function displayDailyTrendsChart(dailyTrends) {
        const ctx = document.getElementById('attendanceTrendsChart').getContext('2d');
        
        // Process data for chart
        const dates = Object.keys(dailyTrends).sort();
        const presentData = [];
        const absentData = [];
        
        dates.forEach(date => {
            const dayData = dailyTrends[date];
            const present = dayData.find(d => d.status === 'present')?.count || 0;
            const absent = dayData.find(d => d.status === 'absent')?.count || 0;
            
            presentData.push(present);
            absentData.push(absent);
        });
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Present',
                    data: presentData,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.1
                }, {
                    label: 'Absent',
                    data: absentData,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    function displayAttendanceSummaryChart(classSummary) {
        const ctx = document.getElementById('attendanceSummaryChart').getContext('2d');
        
        // Calculate totals
        let totalPresent = 0, totalAbsent = 0, totalLate = 0, totalExcused = 0;
        
        Object.values(classSummary).forEach(classData => {
            classData.forEach(record => {
                switch(record.status) {
                    case 'present': totalPresent += record.count; break;
                    case 'absent': totalAbsent += record.count; break;
                    case 'late': totalLate += record.count; break;
                    case 'excused': totalExcused += record.count; break;
                }
            });
        });
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Late', 'Excused'],
                datasets: [{
                    data: [totalPresent, totalAbsent, totalLate, totalExcused],
                    backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#17a2b8']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    function displayLowAttendanceStudents(students) {
        const tbody = $('#lowAttendanceTableBody');
        tbody.empty();
        
        if (students.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        <i class="fas fa-check-circle mr-2"></i>
                        No students with low attendance found
                    </td>
                </tr>
            `);
            return;
        }
        
        students.forEach(function(item) {
            const student = item.student;
            const percentage = item.attendance_percentage;
            const statusClass = getAttendanceStatusClass(percentage);
            const statusText = getAttendanceStatusText(percentage);
            
            tbody.append(`
                <tr>
                    <td>${student.full_name}</td>
                    <td>${student.class?.name || 'N/A'}</td>
                    <td>${item.total_days}</td>
                    <td>${item.present_days}</td>
                    <td>
                        <span class="attendance-percentage ${statusClass}">${percentage}%</span>
                    </td>
                    <td>
                        <span class="badge badge-${getBadgeClass(percentage)}">${statusText}</span>
                    </td>
                </tr>
            `);
        });
    }
    
    function displayClassSummary(classSummary) {
        const tbody = $('#classSummaryTableBody');
        tbody.empty();
        
        Object.entries(classSummary).forEach(([classId, classData]) => {
            const className = classData[0]?.class?.name || 'Unknown Class';
            
            let present = 0, absent = 0, late = 0, excused = 0;
            classData.forEach(record => {
                switch(record.status) {
                    case 'present': present = record.count; break;
                    case 'absent': absent = record.count; break;
                    case 'late': late = record.count; break;
                    case 'excused': excused = record.count; break;
                }
            });
            
            const total = present + absent + late + excused;
            const attendanceRate = total > 0 ? Math.round(((present + late + excused) / total) * 100) : 0;
            
            tbody.append(`
                <tr>
                    <td>${className}</td>
                    <td>${total}</td>
                    <td><span class="badge badge-success">${present}</span></td>
                    <td><span class="badge badge-danger">${absent}</span></td>
                    <td><span class="badge badge-warning">${late}</span></td>
                    <td><span class="badge badge-info">${excused}</span></td>
                    <td>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: ${attendanceRate}%"></div>
                        </div>
                        <small>${attendanceRate}%</small>
                    </td>
                </tr>
            `);
        });
    }
    
    function exportReport() {
        const formData = $('#reportForm').serialize();
        
        $('#exportReportBtn').html('<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...');
        $('#exportReportBtn').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("teacher.attendance.report") }}',
            method: 'POST',
            data: formData + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
            success: function(response) {
                if (response.success) {
                    toastr.success('Report exported successfully');
                    // Handle file download here
                } else {
                    toastr.error(response.message || 'Failed to export report');
                }
            },
            error: function() {
                toastr.error('Failed to export report');
            },
            complete: function() {
                $('#exportReportBtn').html('<i class="fas fa-download mr-2"></i>Export Report');
                $('#exportReportBtn').prop('disabled', false);
            }
        });
    }
    
    function getAttendanceStatusClass(percentage) {
        if (percentage >= 90) return 'status-excellent';
        if (percentage >= 80) return 'status-good';
        if (percentage >= 70) return 'status-average';
        return 'status-poor';
    }
    
    function getAttendanceStatusText(percentage) {
        if (percentage >= 90) return 'Excellent';
        if (percentage >= 80) return 'Good';
        if (percentage >= 70) return 'Average';
        return 'Poor';
    }
    
    function getBadgeClass(percentage) {
        if (percentage >= 90) return 'success';
        if (percentage >= 80) return 'info';
        if (percentage >= 70) return 'warning';
        return 'danger';
    }
});
</script>
@stop