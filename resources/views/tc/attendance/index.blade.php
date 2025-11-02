@extends('layouts.tc')

@section('title', 'Attendance Management')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Attendance Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Attendance</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Quick Actions Card -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('teacher.attendance.create') }}" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-plus mr-2"></i>
                                    Mark Today's Attendance
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-info btn-lg btn-block" id="viewAttendanceBtn">
                                    <i class="fas fa-search mr-2"></i>
                                    View Attendance by Date
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Statistics -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="totalClasses">{{ $stats['total_classes'] ?? 0 }}</h3>
                        <p>My Classes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="totalStudents">{{ $stats['total_students'] ?? 0 }}</h3>
                        <p>Total Students</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="todayPresent">{{ $stats['today_present'] ?? 0 }}</h3>
                        <p>Present Today</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="todayAbsent">{{ $stats['today_absent'] ?? 0 }}</h3>
                        <p>Absent Today</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Attendance Records -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-2"></i>
                            Recent Attendance Records
                        </h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="date" class="form-control" id="filterDate" value="{{ date('Y-m-d') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default" id="filterBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Class</th>
                                    <th>Total Students</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>Late</th>
                                    <th>Attendance %</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTableBody">
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="attendanceTable_info"></div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="attendanceTable_paginate"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Attendance Modal -->
    <div class="modal fade" id="viewAttendanceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        View Attendance
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="viewAttendanceForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="viewClass">Select Class</label>
                                    <select class="form-control" id="viewClass" name="class_id" required>
                                        <option value="">Choose a class...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="viewDate">Select Date</label>
                                    <input type="date" class="form-control" id="viewDate" name="date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search mr-2"></i>
                                    View Attendance
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div id="attendanceResults" style="display: none;">
                        <h5>Attendance Results</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceResultsBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .attendance-status {
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.875em;
        }
        .status-present { background-color: #d4edda; color: #155724; }
        .status-absent { background-color: #f8d7da; color: #721c24; }
        .status-late { background-color: #fff3cd; color: #856404; }
        .status-excused { background-color: #d1ecf1; color: #0c5460; }
        
        .small-box .inner h3 {
            font-size: 2.2rem;
            font-weight: bold;
        }
        
        .card-tools .input-group {
            margin-left: auto;
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Load initial data
    loadAttendanceData();
    loadClasses();
    
    // Filter by date
    $('#filterBtn').click(function() {
        loadAttendanceData();
    });
    
    // View attendance modal
    $('#viewAttendanceBtn').click(function() {
        $('#viewAttendanceModal').modal('show');
    });
    
    // View attendance form submission
    $('#viewAttendanceForm').submit(function(e) {
        e.preventDefault();
        viewAttendanceByDate();
    });
    
    function loadAttendanceData() {
        const date = $('#filterDate').val();
        
        $.ajax({
            url: '{{ route("teacher.attendance.data") }}',
            method: 'GET',
            data: { date: date },
            success: function(response) {
                if (response.success) {
                    updateAttendanceTable(response.data);
                    updateStats(response.stats);
                }
            },
            error: function() {
                toastr.error('Failed to load attendance data');
            }
        });
    }
    
    function loadClasses() {
        $.ajax({
            url: '{{ route("ajax.teacher.classes") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const select = $('#viewClass');
                    select.empty().append('<option value="">Choose a class...</option>');
                    
                    response.data.forEach(function(cls) {
                        select.append(`<option value="${cls.id}">${cls.full_name}</option>`);
                    });
                }
            }
        });
    }
    
    function updateAttendanceTable(data) {
        const tbody = $('#attendanceTableBody');
        tbody.empty();
        
        if (data.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        <i class="fas fa-info-circle mr-2"></i>
                        No attendance records found for the selected date
                    </td>
                </tr>
            `);
            return;
        }
        
        data.forEach(function(record) {
            const attendancePercentage = record.total_students > 0 
                ? Math.round((record.present_count / record.total_students) * 100) 
                : 0;
                
            tbody.append(`
                <tr>
                    <td>${record.date}</td>
                    <td>${record.class_name}</td>
                    <td>${record.total_students}</td>
                    <td><span class="badge badge-success">${record.present_count}</span></td>
                    <td><span class="badge badge-danger">${record.absent_count}</span></td>
                    <td><span class="badge badge-warning">${record.late_count}</span></td>
                    <td>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: ${attendancePercentage}%"></div>
                        </div>
                        <small>${attendancePercentage}%</small>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewClassAttendance(${record.class_id}, '${record.date}')">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="editAttendance(${record.class_id}, '${record.date}')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </td>
                </tr>
            `);
        });
    }
    
    function updateStats(stats) {
        $('#totalClasses').text(stats.total_classes || 0);
        $('#totalStudents').text(stats.total_students || 0);
        $('#todayPresent').text(stats.today_present || 0);
        $('#todayAbsent').text(stats.today_absent || 0);
    }
    
    function viewAttendanceByDate() {
        const classId = $('#viewClass').val();
        const date = $('#viewDate').val();
        
        if (!classId || !date) {
            toastr.warning('Please select both class and date');
            return;
        }
        
        $.ajax({
            url: '{{ route("teacher.attendance.by-date") }}',
            method: 'GET',
            data: { class_id: classId, date: date },
            success: function(response) {
                if (response.success) {
                    displayAttendanceResults(response.data);
                }
            },
            error: function() {
                toastr.error('Failed to load attendance data');
            }
        });
    }
    
    function displayAttendanceResults(data) {
        const tbody = $('#attendanceResultsBody');
        tbody.empty();
        
        if (data.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No attendance records found for this date
                    </td>
                </tr>
            `);
        } else {
            data.forEach(function(record) {
                const statusClass = `status-${record.status}`;
                tbody.append(`
                    <tr>
                        <td>${record.student_name}</td>
                        <td><span class="attendance-status ${statusClass}">${record.status.toUpperCase()}</span></td>
                        <td>${record.remarks || '-'}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editSingleAttendance(${record.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
        }
        
        $('#attendanceResults').show();
    }
    
    // Global functions for button actions
    window.viewClassAttendance = function(classId, date) {
        window.location.href = `{{ route('teacher.attendance.show', ['class' => ':classId']) }}?date=${date}`.replace(':classId', classId);
    };
    
    window.editAttendance = function(classId, date) {
        window.location.href = `{{ route('teacher.attendance.create') }}?class_id=${classId}&date=${date}`;
    };
    
    window.editSingleAttendance = function(attendanceId) {
        // This would open a modal for editing single attendance record
        toastr.info('Single attendance editing feature coming soon');
    };
});
</script>
@stop