@extends('layouts.student')

@section('title', 'Assignments & Homework')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Assignments & Homework</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.academic.index') }}">Academic</a></li>
                    <li class="breadcrumb-item active">Assignments</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row mb-3">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $assignmentStats['total_assignments'] }}</h3>
                        <p>Total Assignments</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $assignmentStats['pending_assignments'] }}</h3>
                        <p>Pending</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $assignmentStats['overdue_assignments'] }}</h3>
                        <p>Overdue</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $assignmentStats['due_this_week'] }}</h3>
                        <p>Due This Week</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Assignments</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('student.academic.assignments') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="subject_id">Subject</label>
                                <select name="subject_id" id="subject_id" class="form-control">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subjectOption)
                                        <option value="{{ $subjectOption->id }}" {{ $subject == $subjectOption->id ? 'selected' : '' }}>
                                            {{ $subjectOption->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="homework" {{ $type === 'homework' ? 'selected' : '' }}>Homework</option>
                                    <option value="project" {{ $type === 'project' ? 'selected' : '' }}>Project</option>
                                    <option value="quiz" {{ $type === 'quiz' ? 'selected' : '' }}>Quiz</option>
                                    <option value="exam" {{ $type === 'exam' ? 'selected' : '' }}>Exam</option>
                                    <option value="presentation" {{ $type === 'presentation' ? 'selected' : '' }}>Presentation</option>
                                    <option value="other" {{ $type === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter mr-1"></i> Filter
                                    </button>
                                    <a href="{{ route('student.academic.assignments') }}" class="btn btn-secondary">
                                        <i class="fas fa-times mr-1"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Assignments List -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Assignments</h3>
            </div>
            <div class="card-body p-0">
                @if($assignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Assignment</th>
                                    <th>Subject</th>
                                    <th>Type</th>
                                    <th>Assigned Date</th>
                                    <th>Due Date</th>
                                    <th>Marks</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                    <tr class="{{ $assignment->is_overdue ? 'table-danger' : ($assignment->isDueSoon(3) ? 'table-warning' : '') }}">
                                        <td>
                                            <div>
                                                <strong>{{ $assignment->title }}</strong>
                                                @if($assignment->description)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($assignment->description, 100) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $assignment->subject->name ?? 'Unknown' }}</span>
                                        </td>
                                        <td>
                                            {!! $assignment->type_badge !!}
                                        </td>
                                        <td>
                                            {{ $assignment->assigned_date->format('M j, Y') }}
                                        </td>
                                        <td>
                                            <div>
                                                {{ $assignment->due_date->format('M j, Y') }}
                                                @if($assignment->due_time)
                                                    <br>
                                                    <small class="text-muted">{{ Carbon\Carbon::parse($assignment->due_time)->format('H:i') }}</small>
                                                @endif
                                            </div>
                                            @if($assignment->is_overdue)
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Overdue
                                                </small>
                                            @elseif($assignment->isDueSoon(3))
                                                <small class="text-warning">
                                                    <i class="fas fa-clock"></i> Due in {{ $assignment->days_until_due }} day(s)
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $assignment->total_marks }} pts</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $assignment->getPriorityColor() }}">
                                                {{ ucfirst($assignment->getPriority()) }} Priority
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-info btn-sm" 
                                                        onclick="viewAssignment({{ $assignment->id }})"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($assignment->attachments && count($assignment->attachments) > 0)
                                                    <button type="button" class="btn btn-success btn-sm" 
                                                            onclick="downloadAttachments({{ $assignment->id }})"
                                                            title="Download Attachments">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No assignments found</h5>
                        <p class="text-muted">There are no assignments matching your current filters.</p>
                    </div>
                @endif
            </div>
            @if($assignments->hasPages())
                <div class="card-footer">
                    {{ $assignments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Assignment Details Modal -->
<div class="modal fade" id="assignmentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assignment Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="assignmentDetails">
                <!-- Assignment details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewAssignment(assignmentId) {
    // Show loading state
    $('#assignmentDetails').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#assignmentModal').modal('show');
    
    // In a real implementation, you would fetch assignment details via AJAX
    // For now, we'll show a placeholder
    setTimeout(function() {
        $('#assignmentDetails').html(`
            <div class="alert alert-info">
                <h6>Assignment Details</h6>
                <p>This feature would show detailed assignment information including:</p>
                <ul>
                    <li>Full description and instructions</li>
                    <li>Submission requirements</li>
                    <li>Grading rubric</li>
                    <li>Attachments and resources</li>
                    <li>Submission status</li>
                </ul>
            </div>
        `);
    }, 500);
}

function downloadAttachments(assignmentId) {
    // In a real implementation, this would trigger file downloads
    toastr.info('Download functionality would be implemented here');
}

$(document).ready(function() {
    // Initialize tooltips
    $('[title]').tooltip();
    
    // Auto-submit form when filters change
    $('#status, #subject_id, #type').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush