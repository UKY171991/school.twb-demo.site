@extends('layouts.tc')

@section('title', 'Mark Attendance')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Mark Attendance</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.attendance.index') }}">Attendance</a></li>
                        <li class="breadcrumb-item active">Mark Attendance</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Attendance Form
                        </h3>
                    </div>
                    <form id="attendanceForm">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_id">Select Class <span class="text-danger">*</span></label>
                                        <select class="form-control" id="class_id" name="class_id" required>
                                            <option value="">Choose a class...</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" 
                                                    {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                    {{ $class->full_name }} ({{ $class->students_count }} students)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="attendance_date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="attendance_date" name="date" 
                                               value="{{ request('date', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-info" id="loadStudentsBtn">
                                        <i class="fas fa-users mr-2"></i>
                                        Load Students
                                    </button>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <!-- Students List -->
                            <div id="studentsSection" style="display: none;">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h5>
                                            <i class="fas fa-user-graduate mr-2"></i>
                                            Students List
                                        </h5>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-success btn-sm" id="markAllPresent">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                All Present
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" id="markAllAbsent">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                All Absent
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="studentsTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="25%">Student Name</th>
                                                <th width="15%">Student ID</th>
                                                <th width="20%">Status</th>
                                                <th width="35%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="studentsTableBody">
                                            <!-- Students will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Attendance Summary -->
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="info-box bg-success">
                                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Present</span>
                                                <span class="info-box-number" id="presentCount">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Absent</span>
                                                <span class="info-box-number" id="absentCount">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-warning">
                                            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Late</span>
                                                <span class="info-box-number" id="lateCount">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-info">
                                            <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Attendance %</span>
                                                <span class="info-box-number" id="attendancePercentage">0%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('teacher.attendance.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Back to List
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-primary" id="saveAttendanceBtn" style="display: none;">
                                        <i class="fas fa-save mr-2"></i>
                                        Save Attendance
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .attendance-radio {
            margin-right: 10px;
        }
        
        .attendance-radio input[type="radio"] {
            margin-right: 5px;
        }
        
        .student-row {
            transition: background-color 0.3s ease;
        }
        
        .student-row:hover {
            background-color: #f8f9fa;
        }
        
        .info-box-number {
            font-size: 1.5rem !important;
            font-weight: bold;
        }
        
        .remarks-input {
            width: 100%;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 5px 10px;
        }
        
        .status-present { color: #28a745; font-weight: bold; }
        .status-absent { color: #dc3545; font-weight: bold; }
        .status-late { color: #ffc107; font-weight: bold; }
        .status-excused { color: #17a2b8; font-weight: bold; }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    let studentsData = [];
    
    // Load students when button is clicked
    $('#loadStudentsBtn').click(function() {
        loadStudents();
    });
    
    // Load students when class or date changes
    $('#class_id, #attendance_date').change(function() {
        if ($('#class_id').val() && $('#attendance_date').val()) {
            loadStudents();
        }
    });
    
    // Mark all present
    $('#markAllPresent').click(function() {
        $('input[name$="[status]"][value="present"]').prop('checked', true);
        updateAttendanceSummary();
    });
    
    // Mark all absent
    $('#markAllAbsent').click(function() {
        $('input[name$="[status]"][value="absent"]').prop('checked', true);
        updateAttendanceSummary();
    });
    
    // Form submission
    $('#attendanceForm').submit(function(e) {
        e.preventDefault();
        saveAttendance();
    });
    
    // Auto-load if class and date are pre-selected
    if ($('#class_id').val() && $('#attendance_date').val()) {
        loadStudents();
    }
    
    function loadStudents() {
        const classId = $('#class_id').val();
        const date = $('#attendance_date').val();
        
        if (!classId || !date) {
            toastr.warning('Please select both class and date');
            return;
        }
        
        // Show loading
        $('#loadStudentsBtn').html('<i class="fas fa-spinner fa-spin mr-2"></i>Loading...');
        $('#loadStudentsBtn').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("teacher.attendance.students") }}',
            method: 'GET',
            data: { class_id: classId, date: date },
            success: function(response) {
                if (response.success) {
                    studentsData = response.data.students;
                    displayStudents(response.data.students, response.data.existing_attendance);
                    $('#studentsSection').show();
                    $('#saveAttendanceBtn').show();
                } else {
                    toastr.error(response.message || 'Failed to load students');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                toastr.error(response?.message || 'Failed to load students');
            },
            complete: function() {
                $('#loadStudentsBtn').html('<i class="fas fa-users mr-2"></i>Load Students');
                $('#loadStudentsBtn').prop('disabled', false);
            }
        });
    }
    
    function displayStudents(students, existingAttendance = {}) {
        const tbody = $('#studentsTableBody');
        tbody.empty();
        
        if (students.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        <i class="fas fa-info-circle mr-2"></i>
                        No students found in this class
                    </td>
                </tr>
            `);
            return;
        }
        
        students.forEach(function(student, index) {
            const attendance = existingAttendance[student.id] || {};
            const defaultStatus = attendance.status || 'present';
            const defaultRemarks = attendance.remarks || '';
            
            tbody.append(`
                <tr class="student-row">
                    <td>${index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${student.photo_url}" class="img-circle mr-2" width="30" height="30" alt="Student Photo">
                            <div>
                                <strong>${student.full_name}</strong>
                            </div>
                        </div>
                    </td>
                    <td>${student.student_id}</td>
                    <td>
                        <div class="attendance-radio">
                            <label class="status-present">
                                <input type="radio" name="attendance_data[${student.id}][status]" value="present" 
                                       ${defaultStatus === 'present' ? 'checked' : ''} onchange="updateAttendanceSummary()">
                                Present
                            </label>
                        </div>
                        <div class="attendance-radio">
                            <label class="status-absent">
                                <input type="radio" name="attendance_data[${student.id}][status]" value="absent" 
                                       ${defaultStatus === 'absent' ? 'checked' : ''} onchange="updateAttendanceSummary()">
                                Absent
                            </label>
                        </div>
                        <div class="attendance-radio">
                            <label class="status-late">
                                <input type="radio" name="attendance_data[${student.id}][status]" value="late" 
                                       ${defaultStatus === 'late' ? 'checked' : ''} onchange="updateAttendanceSummary()">
                                Late
                            </label>
                        </div>
                        <div class="attendance-radio">
                            <label class="status-excused">
                                <input type="radio" name="attendance_data[${student.id}][status]" value="excused" 
                                       ${defaultStatus === 'excused' ? 'checked' : ''} onchange="updateAttendanceSummary()">
                                Excused
                            </label>
                        </div>
                        <input type="hidden" name="attendance_data[${student.id}][student_id]" value="${student.id}">
                    </td>
                    <td>
                        <input type="text" class="remarks-input" 
                               name="attendance_data[${student.id}][remarks]" 
                               placeholder="Optional remarks..." 
                               value="${defaultRemarks}">
                    </td>
                </tr>
            `);
        });
        
        updateAttendanceSummary();
    }
    
    function updateAttendanceSummary() {
        const presentCount = $('input[name$="[status]"][value="present"]:checked').length;
        const absentCount = $('input[name$="[status]"][value="absent"]:checked').length;
        const lateCount = $('input[name$="[status]"][value="late"]:checked').length;
        const excusedCount = $('input[name$="[status]"][value="excused"]:checked').length;
        const totalStudents = studentsData.length;
        
        const attendancePercentage = totalStudents > 0 
            ? Math.round(((presentCount + lateCount + excusedCount) / totalStudents) * 100) 
            : 0;
        
        $('#presentCount').text(presentCount);
        $('#absentCount').text(absentCount);
        $('#lateCount').text(lateCount);
        $('#attendancePercentage').text(attendancePercentage + '%');
    }
    
    function saveAttendance() {
        const formData = new FormData($('#attendanceForm')[0]);
        
        // Convert FormData to regular object for easier manipulation
        const attendanceData = [];
        const studentIds = [];
        
        // Extract student IDs
        $('input[name$="[student_id]"]').each(function() {
            studentIds.push($(this).val());
        });
        
        // Build attendance data array
        studentIds.forEach(function(studentId) {
            const status = $(`input[name="attendance_data[${studentId}][status]"]:checked`).val();
            const remarks = $(`input[name="attendance_data[${studentId}][remarks]"]`).val();
            
            if (status) {
                attendanceData.push({
                    student_id: studentId,
                    status: status,
                    remarks: remarks || null
                });
            }
        });
        
        if (attendanceData.length === 0) {
            toastr.warning('Please mark attendance for at least one student');
            return;
        }
        
        // Show loading
        $('#saveAttendanceBtn').html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...');
        $('#saveAttendanceBtn').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("teacher.attendance.store") }}',
            method: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                class_id: $('#class_id').val(),
                date: $('#attendance_date').val(),
                attendance_data: attendanceData
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Attendance saved successfully');
                    
                    // Redirect after a short delay
                    setTimeout(function() {
                        window.location.href = '{{ route("teacher.attendance.index") }}';
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Failed to save attendance');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    // Display validation errors
                    Object.keys(response.errors).forEach(function(key) {
                        toastr.error(response.errors[key][0]);
                    });
                } else {
                    toastr.error(response?.message || 'Failed to save attendance');
                }
            },
            complete: function() {
                $('#saveAttendanceBtn').html('<i class="fas fa-save mr-2"></i>Save Attendance');
                $('#saveAttendanceBtn').prop('disabled', false);
            }
        });
    }
    
    // Global function for updating summary (called from inline onchange)
    window.updateAttendanceSummary = updateAttendanceSummary;
});
</script>
@stop