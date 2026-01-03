@extends('adminlte::page')

@section('title', 'Mark Attendance')

@section('content_header')
    <h1>Mark Attendance</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Select Grade and Mark Attendance</h3>
        </div>
        <div class="card-body">
            <!-- Grade Selection Form -->
            <form id="gradeForm" method="GET" action="{{ route('attendances.create') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="grade_id">Select Grade/Class <span class="text-danger">*</span></label>
                            <select name="grade_id" id="grade_id" class="form-control" required>
                                <option value="">Select Grade</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }} @if($grade->section) - {{ $grade->section }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-info btn-block">Load Students</button>
                    </div>
                </div>
            </form>

            @if(count($students) > 0)
                <!-- Attendance Form -->
                <hr>
                <form action="{{ route('attendances.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="attendance_date">Attendance Date <span class="text-danger">*</span></label>
                                <input type="date" name="attendance_date" id="attendance_date" 
                                       class="form-control @error('attendance_date') is-invalid @enderror" 
                                       value="{{ old('attendance_date', date('Y-m-d')) }}" required>
                                @error('attendance_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label>&nbsp;</label>
                            <div class="btn-group d-block">
                                <button type="button" class="btn btn-success btn-lg" id="checkAll">
                                    <i class="fas fa-check-square"></i> Mark All Present
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" id="uncheckAll">
                                    <i class="fas fa-square"></i> Clear All
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" id="markAbsent">
                                    <i class="fas fa-times-circle"></i> Mark All Absent
                                </button>
                                <button type="button" class="btn btn-info btn-sm" id="markLate">
                                    <i class="fas fa-clock"></i> Mark All Late
                                </button>
                            </div>
                        </div>
                    </div>

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
                                @foreach($students as $student)
                                <tr>
                                    <td>
                                        <strong>{{ $student->name }}</strong>
                                        @if($student->roll_number)
                                            <br><small class="text-muted">Roll: {{ $student->roll_number }}</small>
                                        @endif
                                        <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input type="radio" name="status[{{ $student->id }}]" value="present" 
                                                   class="form-check-input attendance-radio present-radio" 
                                                   id="present_{{ $student->id }}" checked>
                                            <label class="form-check-label" for="present_{{ $student->id }}">
                                                <i class="fas fa-check text-success"></i>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input type="radio" name="status[{{ $student->id }}]" value="absent" 
                                                   class="form-check-input attendance-radio absent-radio" 
                                                   id="absent_{{ $student->id }}">
                                            <label class="form-check-label" for="absent_{{ $student->id }}">
                                                <i class="fas fa-times text-danger"></i>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input type="radio" name="status[{{ $student->id }}]" value="late" 
                                                   class="form-check-input attendance-radio late-radio" 
                                                   id="late_{{ $student->id }}">
                                            <label class="form-check-label" for="late_{{ $student->id }}">
                                                <i class="fas fa-clock text-warning"></i>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input type="radio" name="status[{{ $student->id }}]" value="excused" 
                                                   class="form-check-input attendance-radio excused-radio" 
                                                   id="excused_{{ $student->id }}">
                                            <label class="form-check-label" for="excused_{{ $student->id }}">
                                                <i class="fas fa-user-check text-info"></i>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="note[{{ $student->id }}]" class="form-control form-control-sm" 
                                               placeholder="Optional note">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Attendance Summary -->
                    <div id="attendanceSummary" class="mb-3"></div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Submit Attendance
                        </button>
                        <button type="submit" name="save_and_continue" value="1" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> Save & Mark Another Class
                        </button>
                        <a href="{{ route('attendances.index') }}" class="btn btn-default">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            @elseif(request('grade_id'))
                <div class="alert alert-warning mt-3">
                    No students found in the selected grade.
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
<style>
.attendance-radio {
    transform: scale(1.2);
}

.form-check-label {
    cursor: pointer;
    font-size: 1.2em;
}

.table-success {
    background-color: rgba(40, 167, 69, 0.1) !important;
}

.table-danger {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.table-info {
    background-color: rgba(23, 162, 184, 0.1) !important;
}

.btn-group .btn {
    margin-right: 5px;
}

.badge-lg {
    font-size: 1em;
    padding: 8px 12px;
}

.bulk-action-feedback {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Check All Present
    $('#checkAll').click(function() {
        $('.present-radio').prop('checked', true);
        updateSummary();
        showFeedback('All students marked as Present', 'success');
    });
    
    // Mark All Absent
    $('#markAbsent').click(function() {
        $('.absent-radio').prop('checked', true);
        updateSummary();
        showFeedback('All students marked as Absent', 'warning');
    });
    
    // Uncheck All (default to present)
    $('#uncheckAll').click(function() {
        $('.present-radio').prop('checked', true);
        updateSummary();
        showFeedback('All students marked as Present', 'info');
    });
    
    // Update summary when radio buttons change
    $('.attendance-radio').change(function() {
        updateSummary();
    });
    
    // Function to update attendance summary
    function updateSummary() {
        var present = $('.present-radio:checked').length;
        var absent = $('.absent-radio:checked').length;
        var late = $('.late-radio:checked').length;
        var excused = $('.excused-radio:checked').length;
        var total = $('.attendance-radio[value="present"]').length;
        
        // Calculate percentage
        var presentPercentage = total > 0 ? Math.round((present / total) * 100) : 0;
        
        // Update summary with better styling
        var summaryHtml = `
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
                            <i class="fas fa-percentage"></i> ${presentPercentage}% Present
                        </span>
                    </div>
                </div>
            </div>
        `;
        
        $('#attendanceSummary').html(summaryHtml);
    }
    
    // Function to show feedback messages
    function showFeedback(message, type) {
        // Remove existing feedback
        $('.bulk-action-feedback').remove();
        
        // Add new feedback
        var alertClass = 'alert-' + type;
        var feedbackHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show bulk-action-feedback mt-2" role="alert">
                <i class="fas fa-info-circle"></i> ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        $('.btn-group').after(feedbackHtml);
        
        // Auto-hide after 3 seconds
        setTimeout(function() {
            $('.bulk-action-feedback').fadeOut();
        }, 3000);
    }
    
    // Initialize summary on page load
    updateSummary();
    
    // Add visual feedback for radio button changes
    $('.attendance-radio').change(function() {
        var row = $(this).closest('tr');
        var status = $(this).val();
        
        // Remove all status classes
        row.removeClass('table-success table-danger table-warning table-info');
        
        // Add appropriate class based on status
        switch(status) {
            case 'present':
                row.addClass('table-success');
                break;
            case 'absent':
                row.addClass('table-danger');
                break;
            case 'late':
                row.addClass('table-warning');
                break;
            case 'excused':
                row.addClass('table-info');
                break;
        }
    });
    
    // Initialize row colors based on default selection
    $('.present-radio:checked').each(function() {
        $(this).closest('tr').addClass('table-success');
    });
    
    // Keyboard shortcuts for bulk actions
    $(document).keydown(function(e) {
        // Only work if not typing in input fields
        if ($(e.target).is('input, textarea, select')) {
            return;
        }
        
        switch(e.key) {
            case '1':
                e.preventDefault();
                $('#checkAll').click();
                showFeedback('All students marked as Present (Shortcut: 1)', 'success');
                break;
            case '2':
                e.preventDefault();
                $('#markAbsent').click();
                showFeedback('All students marked as Absent (Shortcut: 2)', 'danger');
                break;
            case '3':
                e.preventDefault();
                $('#markLate').click();
                showFeedback('All students marked as Late (Shortcut: 3)', 'warning');
                break;
            case '4':
                e.preventDefault();
                $('#uncheckAll').click();
                showFeedback('All selections cleared (Shortcut: 4)', 'secondary');
                break;
        }
    });
    
    // Add tooltip to show shortcuts
    $('.btn-group .btn').attr('title', function() {
        var btnId = $(this).attr('id');
        switch(btnId) {
            case 'checkAll': return 'Mark All Present (Shortcut: Press 1)';
            case 'markAbsent': return 'Mark All Absent (Shortcut: Press 2)';
            case 'markLate': return 'Mark All Late (Shortcut: Press 3)';
            case 'uncheckAll': return 'Clear All (Shortcut: Press 4)';
        }
    });
});
</script>

<!-- Keyboard Shortcuts Help -->
<div class="mt-3">
    <div class="alert alert-light border">
        <h6><i class="fas fa-keyboard"></i> Keyboard Shortcuts:</h6>
        <div class="row">
            <div class="col-md-3"><kbd>1</kbd> Mark All Present</div>
            <div class="col-md-3"><kbd>2</kbd> Mark All Absent</div>
            <div class="col-md-3"><kbd>3</kbd> Mark All Late</div>
            <div class="col-md-3"><kbd>4</kbd> Clear All</div>
        </div>
    </div>
</div>
@stop
