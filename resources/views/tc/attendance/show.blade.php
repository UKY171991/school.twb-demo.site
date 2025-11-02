@extends('layouts.tc')

@section('title', 'View Attendance')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">View Attendance</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.attendance.index') }}">Attendance</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Class and Date Info -->
        <div class="row">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>
                            Attendance Details
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('teacher.attendance.create', ['class_id' => $class->id, 'date' => $date->format('Y-m-d')]) }}" 
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-edit mr-1"></i>
                                Edit Attendance
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Class:</strong><br>
                                <span class="text-muted">{{ $class->full_name }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Date:</strong><br>
                                <span class="text-muted">{{ $date->format('l, F j, Y') }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Total Students:</strong><br>
                                <span class="text-muted">{{ $students->count() }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Attendance Marked:</strong><br>
                                <span class="text-muted">{{ $attendance->count() }} / {{ $students->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $attendance->where('status', 'present')->count() }}</h3>
                        <p>Present</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $attendance->where('status', 'absent')->count() }}</h3>
                        <p>Absent</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $attendance->where('status', 'late')->count() }}</h3>
                        <p>Late</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        @php
                            $totalStudents = $students->count();
                            $presentCount = $attendance->whereIn('status', ['present', 'late', 'excused'])->count();
                            $attendancePercentage = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100) : 0;
                        @endphp
                        <h3>{{ $attendancePercentage }}%</h3>
                        <p>Attendance Rate</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-2"></i>
                            Student Attendance List
                        </h3>
                        <div class="card-tools">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-filter mr-1"></i>
                                    Filter by Status
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item filter-status" href="#" data-status="all">
                                        <i class="fas fa-list mr-2"></i>All Students
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item filter-status" href="#" data-status="present">
                                        <i class="fas fa-check-circle text-success mr-2"></i>Present Only
                                    </a>
                                    <a class="dropdown-item filter-status" href="#" data-status="absent">
                                        <i class="fas fa-times-circle text-danger mr-2"></i>Absent Only
                                    </a>
                                    <a class="dropdown-item filter-status" href="#" data-status="late">
                                        <i class="fas fa-clock text-warning mr-2"></i>Late Only
                                    </a>
                                    <a class="dropdown-item filter-status" href="#" data-status="excused">
                                        <i class="fas fa-user-check text-info mr-2"></i>Excused Only
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="10%">Photo</th>
                                    <th width="25%">Student Name</th>
                                    <th width="15%">Student ID</th>
                                    <th width="15%">Status</th>
                                    <th width="25%">Remarks</th>
                                    <th width="5%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTableBody">
                                @forelse($students as $index => $student)
                                    @php
                                        $studentAttendance = $attendanceMap->get($student->id);
                                        $status = $studentAttendance ? $studentAttendance->status : 'not_marked';
                                        $remarks = $studentAttendance ? $studentAttendance->remarks : '';
                                    @endphp
                                    <tr class="student-row" data-status="{{ $status }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <img src="{{ $student->photo_url }}" 
                                                 class="img-circle" 
                                                 width="40" 
                                                 height="40" 
                                                 alt="Student Photo">
                                        </td>
                                        <td>
                                            <strong>{{ $student->full_name }}</strong>
                                            @if($student->user)
                                                <br><small class="text-muted">{{ $student->user->email }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $student->student_id }}</td>
                                        <td>
                                            @switch($status)
                                                @case('present')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle mr-1"></i>Present
                                                    </span>
                                                    @break
                                                @case('absent')
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times-circle mr-1"></i>Absent
                                                    </span>
                                                    @break
                                                @case('late')
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock mr-1"></i>Late
                                                    </span>
                                                    @break
                                                @case('excused')
                                                    <span class="badge badge-info">
                                                        <i class="fas fa-user-check mr-1"></i>Excused
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-question-circle mr-1"></i>Not Marked
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($remarks)
                                                <span class="text-muted">{{ $remarks }}</span>
                                            @else
                                                <span class="text-muted font-italic">No remarks</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($studentAttendance)
                                                <button class="btn btn-sm btn-warning" 
                                                        onclick="editAttendance({{ $studentAttendance->id }}, '{{ $student->full_name }}', '{{ $status }}', '{{ $remarks }}')"
                                                        title="Edit Attendance">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-primary" 
                                                        onclick="markAttendance({{ $student->id }}, '{{ $student->full_name }}')"
                                                        title="Mark Attendance">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            No students found in this class
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                                <a href="{{ route('teacher.attendance.create', ['class_id' => $class->id, 'date' => $date->format('Y-m-d')]) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit All Attendance
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Single Attendance Modal -->
    <div class="modal fade" id="editAttendanceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Attendance
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="editAttendanceForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="attendanceId" name="attendance_id">
                        
                        <div class="form-group">
                            <label>Student Name</label>
                            <input type="text" class="form-control" id="studentName" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="statusPresent" value="present">
                                        <label class="form-check-label text-success" for="statusPresent">
                                            <i class="fas fa-check-circle mr-1"></i>Present
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="statusAbsent" value="absent">
                                        <label class="form-check-label text-danger" for="statusAbsent">
                                            <i class="fas fa-times-circle mr-1"></i>Absent
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="statusLate" value="late">
                                        <label class="form-check-label text-warning" for="statusLate">
                                            <i class="fas fa-clock mr-1"></i>Late
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="statusExcused" value="excused">
                                        <label class="form-check-label text-info" for="statusExcused">
                                            <i class="fas fa-user-check mr-1"></i>Excused
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Optional remarks..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Update Attendance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .student-row {
            transition: background-color 0.3s ease;
        }
        
        .student-row:hover {
            background-color: #f8f9fa;
        }
        
        .small-box .inner h3 {
            font-size: 2.2rem;
            font-weight: bold;
        }
        
        .badge {
            font-size: 0.875em;
            padding: 0.375rem 0.75rem;
        }
        
        .form-check-label {
            font-weight: 500;
        }
        
        .hidden {
            display: none !important;
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Filter functionality
    $('.filter-status').click(function(e) {
        e.preventDefault();
        const status = $(this).data('status');
        filterByStatus(status);
    });
    
    // Edit attendance form submission
    $('#editAttendanceForm').submit(function(e) {
        e.preventDefault();
        updateSingleAttendance();
    });
    
    function filterByStatus(status) {
        const rows = $('.student-row');
        
        if (status === 'all') {
            rows.show();
        } else {
            rows.hide();
            rows.filter(`[data-status="${status}"]`).show();
        }
        
        // Update button text
        const statusText = status === 'all' ? 'All Students' : 
                          status.charAt(0).toUpperCase() + status.slice(1) + ' Only';
        $('.dropdown-toggle').html(`<i class="fas fa-filter mr-1"></i>${statusText}`);
    }
    
    function updateSingleAttendance() {
        const formData = $('#editAttendanceForm').serialize();
        
        $.ajax({
            url: '{{ route("ajax.teacher.attendance.update") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Attendance updated successfully');
                    $('#editAttendanceModal').modal('hide');
                    
                    // Reload the page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Failed to update attendance');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    Object.keys(response.errors).forEach(function(key) {
                        toastr.error(response.errors[key][0]);
                    });
                } else {
                    toastr.error(response?.message || 'Failed to update attendance');
                }
            }
        });
    }
    
    // Global functions for button actions
    window.editAttendance = function(attendanceId, studentName, status, remarks) {
        $('#attendanceId').val(attendanceId);
        $('#studentName').val(studentName);
        $(`input[name="status"][value="${status}"]`).prop('checked', true);
        $('#remarks').val(remarks);
        $('#editAttendanceModal').modal('show');
    };
    
    window.markAttendance = function(studentId, studentName) {
        // This would be for marking attendance for a student who doesn't have attendance yet
        // For now, redirect to the create page
        const classId = {{ $class->id }};
        const date = '{{ $date->format("Y-m-d") }}';
        window.location.href = `{{ route('teacher.attendance.create') }}?class_id=${classId}&date=${date}`;
    };
});
</script>
@stop