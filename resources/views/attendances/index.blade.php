@extends('layouts.app')

@section('title', 'Attendance')

@section('content_header')
    <h1>Attendance Management</h1>
@stop

@section('content')
    <!-- AJAX Messages Container -->
    <div id="ajax-messages"></div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Attendance Records</h3>
            <div class="card-tools">
                <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Mark Attendance
                </a>
                <a href="{{ url('admin/leaves') }}" class="btn btn-warning btn-sm ml-2">
                    <i class="fas fa-calendar-times"></i> Leave Management
                </a>
                <a href="{{ url('admin/holidays') }}" class="btn btn-info btn-sm ml-2">
                    <i class="fas fa-umbrella-beach"></i> Holidays
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Date Navigation -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="prev-day">
                            <i class="fas fa-chevron-left"></i> Previous Day
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="today">
                            <i class="fas fa-calendar-day"></i> Today
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="next-day">
                            Next Day <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <strong id="current-date-display"></strong>
                </div>
            </div>

            <!-- Attendance Summary -->
            <!-- Attendance Summary -->
            <div id="attendance-summary" class="mb-3" style="display: none;">
                <div class="card card-outline card-primary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 text-center">
                                <div class="description-block">
                                    <h5 class="description-header text-success" id="present-count">0</h5>
                                    <span class="description-text">PRESENT</span>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="description-block">
                                    <h5 class="description-header text-danger" id="absent-count">0</h5>
                                    <span class="description-text">ABSENT</span>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="description-block">
                                    <h5 class="description-header text-warning" id="late-count">0</h5>
                                    <span class="description-text">LATE</span>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="description-block">
                                    <h5 class="description-header text-info" id="excused-count">0</h5>
                                    <span class="description-text">EXCUSED</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Attendance Section -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h5 class="card-title"><i class="fas fa-bolt"></i> Quick Attendance</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Select a grade/class to quickly mark attendance for today:</p>
                            <div class="row" id="quick-attendance-grades">
                                @forelse($grades as $grade)
                                <div class="col-md-3 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-block quick-grade-btn" 
                                            data-grade-id="{{ $grade->id }}" 
                                            data-grade-name="{{ $grade->name }}@if($grade->section) - {{ $grade->section }}@endif">
                                        <i class="fas fa-users"></i> {{ $grade->name }}
                                        @if($grade->section) - {{ $grade->section }} @endif
                                    </button>
                                </div>
                                @empty
                                <div class="col-md-12">
                                    <p class="text-center text-muted">No grades/classes found. Please add grades first.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Table -->
            <div id="attendance-table-container">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p>Loading attendance data...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Attendance Modal -->
    <div class="modal fade" id="quickAttendanceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mark Attendance - <span id="modal-grade-name"></span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="quick-attendance-form">
                        @csrf
                        <input type="hidden" id="modal-grade-id" name="grade_id">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modal-attendance-date">Attendance Date</label>
                                    <input type="date" class="form-control" id="modal-attendance-date" 
                                           name="attendance_date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>&nbsp;</label>
                                <div class="btn-group d-block">
                                    <button type="button" class="btn btn-success btn-sm" id="modal-check-all">
                                        <i class="fas fa-check-square"></i> Mark All Present
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" id="modal-uncheck-all">
                                        <i class="fas fa-square"></i> Clear All
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" id="modal-mark-absent">
                                        <i class="fas fa-times-circle"></i> Mark All Absent
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="modal-students-container">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading students...</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-quick-attendance">
                        <i class="fas fa-save"></i> Save Attendance
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('js')
<script>
$(document).ready(function() {
    let currentDate = '{{ date('Y-m-d') }}';
    
    // Load initial data
    loadAttendanceData(currentDate);
    
    // Date navigation
    $('#prev-day').click(function() {
        const date = new Date(currentDate);
        date.setDate(date.getDate() - 1);
        currentDate = date.toISOString().split('T')[0];
        loadAttendanceData(currentDate);
    });
    
    $('#next-day').click(function() {
        const date = new Date(currentDate);
        date.setDate(date.getDate() + 1);
        currentDate = date.toISOString().split('T')[0];
        loadAttendanceData(currentDate);
    });
    
    $('#today').click(function() {
        currentDate = new Date().toISOString().split('T')[0];
        loadAttendanceData(currentDate);
    });
    
    // Quick grade buttons
    $('.quick-grade-btn').click(function() {
        const gradeId = $(this).data('grade-id');
        const gradeName = $(this).data('grade-name');
        
        $('#modal-grade-id').val(gradeId);
        $('#modal-grade-name').text(gradeName);
        $('#modal-attendance-date').val(currentDate);
        
        // Load students for this grade
        loadStudentsForGrade(gradeId);
        
        $('#quickAttendanceModal').modal('show');
    });
    
    // Modal bulk actions
    $('#modal-check-all').click(function() {
        $('.modal-attendance-radio[value="present"]').prop('checked', true);
        updateModalSummary();
    });
    
    $('#modal-uncheck-all').click(function() {
        $('.modal-attendance-radio').prop('checked', false);
        updateModalSummary();
    });
    
    $('#modal-mark-absent').click(function() {
        $('.modal-attendance-radio[value="absent"]').prop('checked', true);
        updateModalSummary();
    });
    
    // Save quick attendance
    $('#save-quick-attendance').click(function() {
        const formData = new FormData(document.getElementById('quick-attendance-form'));
        const data = {
            attendance_date: formData.get('attendance_date'),
            student_ids: [],
            status: {},
            note: {}
        };
        
        // Collect form data
        $('#quick-attendance-form .student-row').each(function() {
            const studentId = $(this).data('student-id');
            const status = $(this).find('.modal-attendance-radio:checked').val() || 'absent';
            const note = $(this).find('.note-input').val() || '';
            
            data.student_ids.push(studentId);
            data.status[studentId] = status;
            data.note[studentId] = note;
        });
        
        // Save via AJAX
        $.ajax({
            url: '{{ route("attendances.save-ajax") }}',
            method: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showAjaxMessage('Attendance saved successfully!', 'success');
                $('#quickAttendanceModal').modal('hide');
                loadAttendanceData(currentDate);
            },
            error: function(xhr) {
                showAjaxMessage('Error saving attendance: ' + xhr.responseJSON?.message, 'danger');
            }
        });
    });
    
    // Update modal summary when radio buttons change
    $(document).on('change', '.modal-attendance-radio', function() {
        updateModalSummary();
    });
    
    function loadAttendanceData(date) {
        $('#attendance-table-container').html(`
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p>Loading attendance data...</p>
            </div>
        `);
        
        $.ajax({
            url: '{{ route("attendances.data") }}',
            method: 'GET',
            data: { date: date },
            success: function(response) {
                currentDate = response.date;
                updateDateDisplay(response.formatted_date);
                updateSummary(response.summary);
                renderAttendanceTable(response.attendances);
            },
            error: function(xhr) {
                $('#attendance-table-container').html(`
                    <div class="alert alert-danger">
                        Error loading attendance data: ${xhr.responseJSON?.message || 'Unknown error'}
                    </div>
                `);
            }
        });
    }
    
    function updateDateDisplay(formattedDate) {
        $('#current-date-display').text(formattedDate);
    }
    
    function updateSummary(summary) {
        if (summary.total > 0) {
            $('#present-count').text(summary.present);
            $('#absent-count').text(summary.absent);
            $('#late-count').text(summary.late);
            $('#excused-count').text(summary.excused);
            $('#attendance-summary').show();
        } else {
            $('#attendance-summary').hide();
        }
    }
    
    function renderAttendanceTable(attendances) {
        let html = `
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Grade/Class</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        if (attendances.length > 0) {
            attendances.forEach(function(attendance) {
                let statusBadge = '';
                switch(attendance.status) {
                    case 'present':
                        statusBadge = '<span class="badge badge-success">Present</span>';
                        break;
                    case 'absent':
                        statusBadge = '<span class="badge badge-danger">Absent</span>';
                        break;
                    case 'late':
                        statusBadge = '<span class="badge badge-warning">Late</span>';
                        break;
                    case 'excused':
                        statusBadge = '<span class="badge badge-info">Excused</span>';
                        break;
                }
                
                html += `
                    <tr>
                        <td>${attendance.id}</td>
                        <td>${attendance.student_name}</td>
                        <td><span class="badge badge-info">${attendance.grade_name}</span></td>
                        <td>${attendance.date}</td>
                        <td>${statusBadge}</td>
                        <td>${attendance.note || '-'}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" onclick="openAttendanceEdit(${attendance.id})">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteAttendance(${attendance.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            html += `<tr><td colspan="7" class="text-center">No attendance records found for the selected date.</td></tr>`;
        }
        
        html += `
                </tbody>
            </table>
        `;
        
        $('#attendance-table-container').html(html);
    }
    
    function loadStudentsForGrade(gradeId) {
        $('#modal-students-container').html(`
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading students...</span>
                </div>
            </div>
        `);
        
        $.ajax({
            url: '{{ url("admin/attendances/students") }}/' + gradeId,
            method: 'GET',
            data: { date: $('#modal-attendance-date').val() || currentDate },
            success: function(response) {
                renderStudentsInModal(response.students);
            },
            error: function(xhr) {
                $('#modal-students-container').html(`
                    <div class="alert alert-danger">
                        Error loading students: ${xhr.responseJSON?.message || 'Unknown error'}
                    </div>
                `);
            }
        });
    }
    
    function renderStudentsInModal(students) {
        let html = `
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="40%">Student Name</th>
                            <th width="15%" class="text-center">Present</th>
                            <th width="15%" class="text-center">Absent</th>
                            <th width="15%" class="text-center">Late</th>
                            <th width="15%" class="text-center">Excused</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        students.forEach(function(student) {
            // determine pre-selected status and note
            const attendance = student.attendance || null;
            const status = attendance ? (attendance.status || '') : 'present';
            const noteVal = attendance ? (attendance.note || '') : '';

            const checkedPresent = status === 'present' ? 'checked' : '';
            const checkedAbsent = status === 'absent' ? 'checked' : '';
            const checkedLate = status === 'late' ? 'checked' : '';
            const checkedExcused = status === 'excused' ? 'checked' : '';

            html += `
                <tr class="student-row" data-student-id="${student.id}">
                    <td>
                        <strong>${student.name}</strong>
                        ${student.roll_number ? `<br><small class="text-muted">Roll: ${student.roll_number}</small>` : ''}
                        <input type="hidden" name="student_ids[]" value="${student.id}">
                    </td>
                    <td class="text-center">
                        <div class="form-check">
                            <input type="radio" name="status[${student.id}]" value="present" 
                                   class="form-check-input modal-attendance-radio" ${checkedPresent}>
                            <label class="form-check-label">
                                <i class="fas fa-check text-success"></i>
                            </label>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-check">
                            <input type="radio" name="status[${student.id}]" value="absent" 
                                   class="form-check-input modal-attendance-radio" ${checkedAbsent}>
                            <label class="form-check-label">
                                <i class="fas fa-times text-danger"></i>
                            </label>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-check">
                            <input type="radio" name="status[${student.id}]" value="late" 
                                   class="form-check-input modal-attendance-radio" ${checkedLate}>
                            <label class="form-check-label">
                                <i class="fas fa-clock text-warning"></i>
                            </label>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-check">
                            <input type="radio" name="status[${student.id}]" value="excused" 
                                   class="form-check-input modal-attendance-radio" ${checkedExcused}>
                            <label class="form-check-label">
                                <i class="fas fa-user-check text-info"></i>
                            </label>
                        </div>
                    </td>
                    <td>
                        <input type="text" name="note[${student.id}]" value="${noteVal}" class="form-control form-control-sm note-input" 
                               placeholder="Optional note">
                    </td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
            <div id="modal-attendance-summary" class="mt-2"></div>
        `;
        
        $('#modal-students-container').html(html);
        updateModalSummary();
    }
    
    function updateModalSummary() {
        const present = $('.modal-attendance-radio[value="present"]:checked').length;
        const absent = $('.modal-attendance-radio[value="absent"]:checked').length;
        const late = $('.modal-attendance-radio[value="late"]:checked').length;
        const excused = $('.modal-attendance-radio[value="excused"]:checked').length;
        const total = $('.student-row').length;
        
        const percentage = total > 0 ? Math.round((present / total) * 100) : 0;
        
        const summaryHtml = `
            <div class="alert alert-light border">
                <div class="row">
                    <div class="col-md-8">
                        <strong><i class="fas fa-chart-pie"></i> Attendance Summary:</strong> 
                        <span class="badge badge-success ml-1">Present: ${present}</span>
                        <span class="badge badge-danger ml-1">Absent: ${absent}</span>
                        <span class="badge badge-warning ml-1">Late: ${late}</span>
                        <span class="badge badge-info ml-1">Excused: ${excused}</span>
                        <span class="badge badge-secondary ml-1">Total: ${total}</span>
                    </div>
                    <div class="col-md-4 text-right">
                        <span class="badge badge-primary badge-lg">
                            <i class="fas fa-percentage"></i> ${percentage}% Present
                        </span>
                    </div>
                </div>
            </div>
        `;
        
        $('#modal-attendance-summary').html(summaryHtml);
    }
    
    function showAjaxMessage(message, type) {
        const alertClass = `alert-${type}`;
        const html = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle"></i> ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        $('#ajax-messages').html(html);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $('#ajax-messages .alert').fadeOut();
        }, 5000);
    }
    
    // Delete attendance function
    window.deleteAttendance = function(id) {
        if (confirm('Are you sure you want to delete this attendance record?')) {
            $.ajax({
                url: '{{ url("admin/attendances") }}/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showAjaxMessage('Attendance record deleted successfully!', 'success');
                    loadAttendanceData(currentDate);
                },
                error: function(xhr) {
                    showAjaxMessage('Error deleting attendance: ' + (xhr.responseJSON?.message || 'Unknown error'), 'danger');
                }
            });
        }
    };

    // Open edit modal for a single attendance record (AJAX)
    window.openAttendanceEdit = function(id) {
        $.ajax({
            url: '{{ url("admin/attendances") }}/' + id + '/data',
            method: 'GET',
            success: function(response) {
                // set modal fields
                const gradeId = response.grade_id;
                const gradeName = response.grade_name || '';
                const dateVal = response.attendance_date || currentDate;

                $('#modal-grade-id').val(gradeId);
                $('#modal-grade-name').text(gradeName);
                $('#modal-attendance-date').val(dateVal);

                // load students for grade; server will include attendance for this date
                loadStudentsForGrade(gradeId);

                // show modal
                $('#quickAttendanceModal').modal('show');
            },
            error: function(xhr) {
                showAjaxMessage('Error loading attendance for edit: ' + (xhr.responseJSON?.message || 'Unknown error'), 'danger');
            }
        });
    };
});
</script>
@stop
