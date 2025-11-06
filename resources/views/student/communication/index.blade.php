@extends('layouts.student')

@section('title', 'Communication Center')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Communication Center</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Communication</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['unread_messages'] }}</h3>
                        <p>Unread Messages</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <a href="{{ route('student.communication.messages', ['filter' => 'unread']) }}" class="small-box-footer">
                        View Messages <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['unread_announcements'] }}</h3>
                        <p>New Announcements</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <a href="{{ route('student.communication.announcements', ['filter' => 'unread']) }}" class="small-box-footer">
                        View Announcements <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['pending_feedback'] }}</h3>
                        <p>Pending Feedback</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <a href="{{ route('student.communication.feedback', ['filter' => 'pending']) }}" class="small-box-footer">
                        View Feedback <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['total_messages'] }}</h3>
                        <p>Total Messages</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <a href="{{ route('student.communication.messages') }}" class="small-box-footer">
                        All Messages <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-block" onclick="showComposeModal()">
                                <i class="fas fa-edit mr-2"></i>
                                Compose Message
                            </button>
                            <button type="button" class="btn btn-success btn-block" onclick="showFeedbackModal()">
                                <i class="fas fa-comment-alt mr-2"></i>
                                Submit Feedback
                            </button>
                            <a href="{{ route('student.communication.announcements') }}" class="btn btn-info btn-block">
                                <i class="fas fa-bullhorn mr-2"></i>
                                View All Announcements
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Messages -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-envelope mr-2"></i>
                            Recent Messages
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('student.communication.messages') }}" class="btn btn-tool">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($recentMessages->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentMessages as $message)
                                    <a href="{{ route('student.communication.message', $message->id) }}" 
                                       class="list-group-item list-group-item-action {{ !$message->is_read && $message->receiver_id === auth()->id() ? 'bg-light' : '' }}">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ Str::limit($message->subject, 30) }}</h6>
                                            <small>{{ $message->time_ago }}</small>
                                        </div>
                                        <p class="mb-1 text-muted">
                                            From: {{ $message->sender->name }}
                                        </p>
                                        <small class="text-muted">{{ Str::limit($message->message, 50) }}</small>
                                        @if(!$message->is_read && $message->receiver_id === auth()->id())
                                            <span class="badge badge-primary badge-sm ml-2">New</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-envelope fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No messages yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Announcements -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bullhorn mr-2"></i>
                            Recent Announcements
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('student.communication.announcements') }}" class="btn btn-tool">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($recentAnnouncements->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentAnnouncements as $announcement)
                                    <a href="{{ route('student.communication.announcement', $announcement->id) }}" 
                                       class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">
                                                {{ Str::limit($announcement->title, 30) }}
                                                @if($announcement->is_pinned)
                                                    <i class="fas fa-thumbtack text-warning ml-1"></i>
                                                @endif
                                            </h6>
                                            <small>{{ $announcement->time_ago }}</small>
                                        </div>
                                        <p class="mb-1 text-muted">
                                            By: {{ $announcement->author->name }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">{{ Str::limit(strip_tags($announcement->content), 50) }}</small>
                                            <div>
                                                {!! $announcement->type_badge !!}
                                                @if($announcement->priority !== 'normal')
                                                    {!! $announcement->priority_badge !!}
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-bullhorn fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No announcements yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Compose Message Modal -->
<div class="modal fade" id="composeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Compose Message</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="composeForm" action="{{ route('student.communication.send-message') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="receiver_id">To (Teacher)</label>
                        <select class="form-control" id="receiver_id" name="receiver_id" required>
                            <option value="">Select Teacher</option>
                            <!-- Teachers will be loaded via AJAX -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select class="form-control" id="priority" name="priority">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="attachments">Attachments (Optional)</label>
                        <input type="file" class="form-control-file" id="attachments" name="attachments[]" multiple>
                        <small class="form-text text-muted">Maximum 10MB per file</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit Feedback</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="feedbackForm" action="{{ route('student.communication.submit-feedback') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="feedback_type">Feedback Type</label>
                        <select class="form-control" id="feedback_type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="course_evaluation">Course Evaluation</option>
                            <option value="teacher_feedback">Teacher Feedback</option>
                            <option value="suggestion">Suggestion</option>
                            <option value="complaint">Complaint</option>
                            <option value="general">General Feedback</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="feedback_title">Title</label>
                        <input type="text" class="form-control" id="feedback_title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="feedback_content">Content</label>
                        <textarea class="form-control" id="feedback_content" name="content" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_anonymous" name="is_anonymous" value="1">
                            <label class="custom-control-label" for="is_anonymous">Submit anonymously</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showComposeModal() {
    // Load teachers
    $.get('{{ route("ajax.teachers") }}', function(data) {
        const select = $('#receiver_id');
        select.empty().append('<option value="">Select Teacher</option>');
        data.forEach(function(teacher) {
            select.append(`<option value="${teacher.user_id}">${teacher.full_name}</option>`);
        });
    });
    
    $('#composeModal').modal('show');
}

function showFeedbackModal() {
    $('#feedbackModal').modal('show');
}

$(document).ready(function() {
    // Handle compose form submission
    $('#composeForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#composeModal').modal('hide');
                    toastr.success(response.message);
                    $('#composeForm')[0].reset();
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    Object.keys(errors).forEach(function(key) {
                        toastr.error(errors[key][0]);
                    });
                } else {
                    toastr.error('An error occurred while sending message');
                }
            }
        });
    });

    // Handle feedback form submission
    $('#feedbackForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#feedbackModal').modal('hide');
                    toastr.success(response.message);
                    $('#feedbackForm')[0].reset();
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    Object.keys(errors).forEach(function(key) {
                        toastr.error(errors[key][0]);
                    });
                } else {
                    toastr.error('An error occurred while submitting feedback');
                }
            }
        });
    });
});
</script>
@endpush