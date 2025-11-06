@extends('layouts.parent')

@section('title', 'Meetings')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Meetings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('parent.communication.index') }}">Communication</a></li>
                    <li class="breadcrumb-item active">Meetings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Request New Meeting -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Request New Meeting</h3>
                    </div>
                    <div class="card-body">
                        <form id="meeting-request-form">
                            @csrf
                            <div class="form-group">
                                <label for="student_id">Student</label>
                                <select name="student_id" id="student_id" class="form-control" required>
                                    <option value="">Select a student...</option>
                                    @foreach($children as $child)
                                        <option value="{{ $child->id }}" data-teacher-id="{{ $child->classModel->teacher->id ?? '' }}">
                                            {{ $child->name }} 
                                            @if($child->classModel)
                                                ({{ $child->classModel->name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="teacher_id">Teacher</label>
                                <select name="teacher_id" id="teacher_id" class="form-control" required>
                                    <option value="">Select a teacher...</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="preferred_date">Preferred Date</label>
                                <input type="date" name="preferred_date" id="preferred_date" class="form-control" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="preferred_time">Preferred Time</label>
                                <input type="time" name="preferred_time" id="preferred_time" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="meeting_type">Meeting Type</label>
                                <select name="meeting_type" id="meeting_type" class="form-control" required>
                                    <option value="">Select type...</option>
                                    <option value="in_person">In Person</option>
                                    <option value="video_call">Video Call</option>
                                    <option value="phone_call">Phone Call</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="purpose">Purpose of Meeting</label>
                                <textarea name="purpose" id="purpose" class="form-control" rows="4" 
                                          placeholder="Please describe what you'd like to discuss..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" id="request-btn">
                                <i class="fas fa-calendar-plus mr-1"></i>
                                Request Meeting
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Meetings List -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">My Meeting Requests</h3>
                    </div>
                    <div class="card-body">
                        @if($meetings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Teacher</th>
                                            <th>Student</th>
                                            <th>Date & Time</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($meetings as $meeting)
                                            <tr>
                                                <td>{{ $meeting->teacher->name }}</td>
                                                <td>{{ $meeting->student->name }}</td>
                                                <td>
                                                    @if($meeting->scheduled_at)
                                                        <strong>{{ $meeting->formatted_scheduled_time }}</strong>
                                                    @else
                                                        <span class="text-muted">
                                                            Requested: {{ $meeting->formatted_preferred_time }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ ucfirst(str_replace('_', ' ', $meeting->meeting_type)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $meeting->status_badge }}">
                                                        {{ ucfirst($meeting->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-info btn-sm" 
                                                                onclick="viewMeetingDetails({{ $meeting->id }})"
                                                                data-toggle="modal" data-target="#meetingDetailsModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        @if($meeting->canBeCancelled())
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                    onclick="cancelMeeting({{ $meeting->id }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-3">
                                {{ $meetings->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No meeting requests yet.</p>
                                <p class="text-muted">Use the form on the left to request your first meeting with a teacher.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Meeting Details Modal -->
<div class="modal fade" id="meetingDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Meeting Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="meeting-details-content">
                <!-- Content will be loaded via AJAX -->
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
$(document).ready(function() {
    // Update teacher dropdown when student is selected
    $('#student_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const teacherId = selectedOption.data('teacher-id');
        const teacherSelect = $('#teacher_id');
        
        // Clear teacher dropdown
        teacherSelect.empty().append('<option value="">Select a teacher...</option>');
        
        if (teacherId) {
            // Get teacher name from the children data
            @foreach($children as $child)
                @if($child->classModel && $child->classModel->teacher)
                    if ({{ $child->classModel->teacher->id }} == teacherId) {
                        teacherSelect.append('<option value="{{ $child->classModel->teacher->id }}">{{ $child->classModel->teacher->name }}</option>');
                    }
                @endif
            @endforeach
        }
    });

    // Handle meeting request form submission
    $('#meeting-request-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const requestBtn = $('#request-btn');
        const originalText = requestBtn.html();
        
        requestBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Requesting...');
        
        $.ajax({
            url: '{{ route("parent.communication.request-meeting") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // Reset form
                    $('#meeting-request-form')[0].reset();
                    $('#teacher_id').empty().append('<option value="">Select a teacher...</option>');
                    
                    // Reload page to show new meeting
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Failed to request meeting');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.values(errors).forEach(function(errorArray) {
                        errorArray.forEach(function(error) {
                            toastr.error(error);
                        });
                    });
                } else {
                    toastr.error('Failed to request meeting. Please try again.');
                }
            },
            complete: function() {
                requestBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});

function viewMeetingDetails(meetingId) {
    // This would load meeting details via AJAX
    $('#meeting-details-content').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    
    // In a real implementation, you would make an AJAX call to get meeting details
    setTimeout(function() {
        $('#meeting-details-content').html('<p>Meeting details would be loaded here...</p>');
    }, 500);
}

function cancelMeeting(meetingId) {
    if (confirm('Are you sure you want to cancel this meeting request?')) {
        // Handle meeting cancellation
        toastr.info('Meeting cancellation functionality would be implemented here');
    }
}
</script>
@endpush