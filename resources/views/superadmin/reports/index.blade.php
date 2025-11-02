@extends('layouts.superadmin')

@section('title', 'System Reports')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">System Reports</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Report Filters -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-2"></i>
                                Report Filters
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="reportFiltersForm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="reportType">Report Type</label>
                                            <select class="form-control" id="reportType" name="report_type">
                                                @foreach($report_types as $key => $type)
                                                    <option value="{{ $key }}">{{ $type['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dateRange">Date Range</label>
                                            <select class="form-control" id="dateRange" name="date_range">
                                                @foreach($date_ranges as $key => $range)
                                                    <option value="{{ $key }}" 
                                                            data-start="{{ $range['start_date'] }}" 
                                                            data-end="{{ $range['end_date'] }}">
                                                        {{ $range['name'] }}
                                                    </option>
                                                @endforeach
                                                <option value="custom">Custom Range</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="startDate">Start Date</label>
                                            <input type="date" class="form-control" id="startDate" name="start_date">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="endDate">End Date</label>
                                            <input type="date" class="form-control" id="endDate" name="end_date">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button type="button" class="btn btn-primary btn-block" id="generateReport">
                                                    <i class="fas fa-chart-bar mr-1"></i>
                                                    Generate Report
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Additional Filters -->
                                <div class="row" id="additionalFilters" style="display: none;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="schoolFilter">Schools</label>
                                            <select class="form-control select2" id="schoolFilter" name="school_ids[]" multiple>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="userTypeFilter" style="display: none;">
                                        <div class="form-group">
                                            <label for="userTypes">User Types</label>
                                            <select class="form-control select2" id="userTypes" name="user_types[]" multiple>
                                                <option value="admin">Admin</option>
                                                <option value="teacher">Teacher</option>
                                                <option value="student">Student</option>
                                                <option value="parent">Parent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="periodFilter" style="display: none;">
                                        <div class="form-group">
                                            <label for="period">Period</label>
                                            <select class="form-control" id="period" name="period">
                                                <option value="monthly">Monthly</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="daily">Daily</option>
                                                <option value="yearly">Yearly</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Content -->
            <div class="row" id="reportContent" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" id="reportTitle">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Report Results
                            </h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm" id="exportExcel">
                                        <i class="fas fa-file-excel mr-1"></i>
                                        Excel
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" id="exportPDF">
                                        <i class="fas fa-file-pdf mr-1"></i>
                                        PDF
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" id="exportCSV">
                                        <i class="fas fa-file-csv mr-1"></i>
                                        CSV
                                    </button>
                                </div>
                                <button type="button" class="btn btn-warning btn-sm ml-2" id="scheduleReport">
                                    <i class="fas fa-clock mr-1"></i>
                                    Schedule
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="reportData">
                                <!-- Report data will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scheduled Reports -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Scheduled Reports
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="scheduledReportsTable">
                                    <thead>
                                        <tr>
                                            <th>Report Type</th>
                                            <th>Frequency</th>
                                            <th>Format</th>
                                            <th>Recipients</th>
                                            <th>Next Run</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Scheduled reports will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Schedule Report Modal -->
<div class="modal fade" id="scheduleReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Schedule Automated Report</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="scheduleReportForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="scheduleReportType">Report Type</label>
                                <select class="form-control" id="scheduleReportType" name="report_type" required>
                                    @foreach($report_types as $key => $type)
                                        <option value="{{ $key }}">{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="scheduleFrequency">Frequency</label>
                                <select class="form-control" id="scheduleFrequency" name="frequency" required>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="scheduleFormat">Format</label>
                                <select class="form-control" id="scheduleFormat" name="format" required>
                                    <option value="excel">Excel</option>
                                    <option value="pdf">PDF</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="scheduleActive">Status</label>
                                <select class="form-control" id="scheduleActive" name="is_active">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="scheduleRecipients">Email Recipients</label>
                        <textarea class="form-control" id="scheduleRecipients" name="email_recipients" rows="3" 
                                  placeholder="Enter email addresses separated by commas" required></textarea>
                        <small class="form-text text-muted">Enter multiple email addresses separated by commas</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
.report-summary-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.report-chart-container {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.metric-card {
    background: white;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 15px;
}

.metric-value {
    font-size: 2rem;
    font-weight: bold;
    color: #007bff;
}

.metric-label {
    color: #6c757d;
    font-size: 0.9rem;
}

.growth-positive {
    color: #28a745;
}

.growth-negative {
    color: #dc3545;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('vendor/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Handle date range selection
    $('#dateRange').change(function() {
        const selectedOption = $(this).find(':selected');
        const startDate = selectedOption.data('start');
        const endDate = selectedOption.data('end');
        
        if ($(this).val() === 'custom') {
            $('#startDate, #endDate').prop('disabled', false);
        } else {
            $('#startDate').val(startDate).prop('disabled', true);
            $('#endDate').val(endDate).prop('disabled', true);
        }
    });

    // Handle report type change
    $('#reportType').change(function() {
        const reportType = $(this).val();
        
        // Show/hide additional filters based on report type
        if (reportType === 'user_analytics') {
            $('#userTypeFilter').show();
        } else {
            $('#userTypeFilter').hide();
        }
        
        if (reportType === 'enrollment_trends') {
            $('#periodFilter').show();
        } else {
            $('#periodFilter').hide();
        }
        
        $('#additionalFilters').show();
    });

    // Initialize date range
    $('#dateRange').trigger('change');
    $('#reportType').trigger('change');

    // Generate report
    $('#generateReport').click(function() {
        const formData = $('#reportFiltersForm').serialize();
        const reportType = $('#reportType').val();
        
        // Show loading
        $('#reportContent').show();
        $('#reportData').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Generating report...</p></div>');
        
        // Update report title
        const reportTitle = $('#reportType option:selected').text();
        $('#reportTitle').html('<i class="fas fa-chart-pie mr-2"></i>' + reportTitle);
        
        // Make AJAX request
        $.ajax({
            url: `/superadmin/reports/${reportType}`,
            method: 'GET',
            data: formData,
            success: function(response) {
                if (response.success) {
                    renderReportData(reportType, response.data);
                } else {
                    showError('Failed to generate report: ' + response.message);
                }
            },
            error: function(xhr) {
                showError('Error generating report. Please try again.');
                console.error(xhr);
            }
        });
    });

    // Export functions
    $('#exportExcel').click(function() { exportReport('excel'); });
    $('#exportPDF').click(function() { exportReport('pdf'); });
    $('#exportCSV').click(function() { exportReport('csv'); });

    // Schedule report
    $('#scheduleReport').click(function() {
        // Pre-fill modal with current filters
        $('#scheduleReportType').val($('#reportType').val());
        $('#scheduleReportModal').modal('show');
    });

    // Handle schedule form submission
    $('#scheduleReportForm').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serializeArray();
        const data = {};
        
        // Convert form data to object
        $.each(formData, function(i, field) {
            if (field.name === 'email_recipients') {
                data[field.name] = field.value.split(',').map(email => email.trim());
            } else {
                data[field.name] = field.value;
            }
        });
        
        // Add current filters
        data.school_ids = $('#schoolFilter').val();
        data.user_types = $('#userTypes').val();
        
        $.ajax({
            url: '{{ route("superadmin.reports.schedule") }}',
            method: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#scheduleReportModal').modal('hide');
                    showSuccess('Report scheduled successfully');
                    loadScheduledReports();
                } else {
                    showError('Failed to schedule report: ' + response.message);
                }
            },
            error: function(xhr) {
                showError('Error scheduling report. Please try again.');
                console.error(xhr);
            }
        });
    });

    // Load scheduled reports
    loadScheduledReports();
});

function renderReportData(reportType, data) {
    let html = '';
    
    switch (reportType) {
        case 'system_overview':
            html = renderSystemOverview(data);
            break;
        case 'school_performance':
            html = renderSchoolPerformance(data);
            break;
        case 'user_analytics':
            html = renderUserAnalytics(data);
            break;
        case 'enrollment_trends':
            html = renderEnrollmentTrends(data);
            break;
    }
    
    $('#reportData').html(html);
}

function renderSystemOverview(data) {
    return `
        <div class="row">
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value">${data.summary.total_schools}</div>
                    <div class="metric-label">Total Schools</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value">${data.summary.total_users}</div>
                    <div class="metric-label">Total Users</div>
                    <div class="growth-positive">+${data.growth.user_growth}%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value">${data.summary.total_students}</div>
                    <div class="metric-label">Total Students</div>
                    <div class="growth-positive">+${data.growth.student_growth}%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value">${data.summary.total_teachers}</div>
                    <div class="metric-label">Total Teachers</div>
                    <div class="growth-positive">+${data.growth.teacher_growth}%</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="report-chart-container">
                    <h5>User Distribution</h5>
                    <canvas id="userDistributionChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="report-chart-container">
                    <h5>Period Summary</h5>
                    <p><strong>Period:</strong> ${data.period.start_date} to ${data.period.end_date}</p>
                    <p><strong>Duration:</strong> ${data.period.days} days</p>
                    <p><strong>Active Users:</strong> ${data.summary.active_users}</p>
                </div>
            </div>
        </div>
    `;
}

function renderSchoolPerformance(data) {
    let schoolsHtml = '';
    data.schools.forEach(school => {
        schoolsHtml += `
            <tr>
                <td>${school.name}</td>
                <td>${school.total_students}</td>
                <td>${school.total_teachers}</td>
                <td>${school.student_teacher_ratio}</td>
                <td>${school.performance_score}</td>
                <td><span class="badge badge-${school.is_active ? 'success' : 'danger'}">${school.is_active ? 'Active' : 'Inactive'}</span></td>
            </tr>
        `;
    });
    
    return `
        <div class="row">
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value">${data.system_averages.avg_students_per_school}</div>
                    <div class="metric-label">Avg Students/School</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value">${data.system_averages.avg_teachers_per_school}</div>
                    <div class="metric-label">Avg Teachers/School</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value">${data.system_averages.avg_student_teacher_ratio}</div>
                    <div class="metric-label">Avg Student/Teacher Ratio</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value">${data.system_averages.avg_performance_score}</div>
                    <div class="metric-label">Avg Performance Score</div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>School</th>
                        <th>Students</th>
                        <th>Teachers</th>
                        <th>Ratio</th>
                        <th>Performance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    ${schoolsHtml}
                </tbody>
            </table>
        </div>
    `;
}

function renderUserAnalytics(data) {
    return `
        <div class="row">
            <div class="col-md-4">
                <div class="metric-card">
                    <div class="metric-value">${data.activity_metrics.total_users}</div>
                    <div class="metric-label">Total Users</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card">
                    <div class="metric-value">${data.activity_metrics.active_users}</div>
                    <div class="metric-label">Active Users</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card">
                    <div class="metric-value">${data.activity_metrics.activity_rate}%</div>
                    <div class="metric-label">Activity Rate</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="report-chart-container">
                    <h5>User Type Distribution</h5>
                    <canvas id="userTypeChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="report-chart-container">
                    <h5>School Distribution</h5>
                    <canvas id="schoolDistributionChart"></canvas>
                </div>
            </div>
        </div>
    `;
}

function renderEnrollmentTrends(data) {
    return `
        <div class="report-chart-container">
            <h5>Enrollment Trends - ${data.period.charAt(0).toUpperCase() + data.period.slice(1)}</h5>
            <canvas id="enrollmentTrendsChart"></canvas>
        </div>
    `;
}

function exportReport(format) {
    const formData = $('#reportFiltersForm').serialize() + '&format=' + format;
    const reportType = $('#reportType').val();
    
    // Create a temporary form to submit for file download
    const form = $('<form>', {
        method: 'GET',
        action: `/superadmin/reports/export/${reportType}`
    });
    
    // Add form data as hidden inputs
    const params = new URLSearchParams(formData);
    for (const [key, value] of params) {
        form.append($('<input>', {
            type: 'hidden',
            name: key,
            value: value
        }));
    }
    
    $('body').append(form);
    form.submit();
    form.remove();
}

function loadScheduledReports() {
    $.ajax({
        url: '{{ route("superadmin.reports.scheduled") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                renderScheduledReports(response.data.schedules);
            }
        },
        error: function(xhr) {
            console.error('Error loading scheduled reports:', xhr);
        }
    });
}

function renderScheduledReports(schedules) {
    let html = '';
    
    if (schedules.length === 0) {
        html = '<tr><td colspan="7" class="text-center">No scheduled reports found</td></tr>';
    } else {
        schedules.forEach(schedule => {
            html += `
                <tr>
                    <td>${schedule.report_type.replace('_', ' ').toUpperCase()}</td>
                    <td>${schedule.frequency.charAt(0).toUpperCase() + schedule.frequency.slice(1)}</td>
                    <td>${schedule.format.toUpperCase()}</td>
                    <td>${schedule.email_recipients.join(', ')}</td>
                    <td>${schedule.next_run_at}</td>
                    <td><span class="badge badge-${schedule.is_active ? 'success' : 'secondary'}">${schedule.is_active ? 'Active' : 'Inactive'}</span></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editSchedule('${schedule.id}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteSchedule('${schedule.id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    $('#scheduledReportsTable tbody').html(html);
}

function editSchedule(scheduleId) {
    // Implementation for editing scheduled reports
    showInfo('Edit functionality would be implemented here');
}

function deleteSchedule(scheduleId) {
    if (confirm('Are you sure you want to delete this scheduled report?')) {
        $.ajax({
            url: `/superadmin/reports/scheduled/${scheduleId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showSuccess('Scheduled report deleted successfully');
                    loadScheduledReports();
                } else {
                    showError('Failed to delete scheduled report');
                }
            },
            error: function(xhr) {
                showError('Error deleting scheduled report');
                console.error(xhr);
            }
        });
    }
}

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