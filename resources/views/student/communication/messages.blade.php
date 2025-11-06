@extends('layouts.student')

@section('title', 'Messages')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Messages</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.communication.index') }}">Communication</a></li>
                    <li class="breadcrumb-item active">Messages</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Tabs -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" 
                           href="{{ route('student.communication.messages', ['filter' => 'all']) }}">
                            All Messages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $filter === 'unread' ? 'active' : '' }}" 
                           href="{{ route('student.communication.messages', ['filter' => 'unread']) }}">
                            Unread
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $filter === 'sent' ? 'active' : '' }}" 
                           href="{{ route('student.communication.messages', ['filter' => 'sent']) }}">
                            Sent
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $filter === 'received' ? 'active' : '' }}" 
                           href="{{ route('student.communication.messages', ['filter' => 'received']) }}">
                            Received
                        </a>
                    </li>
                </ul>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary" onclick="showComposeModal()">
                        <i class="fas fa-plus mr-1"></i> Compose Message
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @if($messages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;"></th>
                                    <th>From/To</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($messages as $message)
                                    <tr class="{{ !$message->is_read && $message->receiver_id === auth()->id() ? 'table-info' : '' }}">
                                        <td>
                                            @if($message->is_important)
                                                <i class="fas fa-star text-warning"></i>
                                            @endif
                                            @if($message->attachments && count($message->attachments) > 0)
                                                <i class="fas fa-paperclip text-muted"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($message->sender_id === auth()->id())
                                                <strong>To:</strong> {{ $message->receiver->name }}
                                            @else
                                                <strong>From:</strong> {{ $message->sender->name }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('student.communication.message', $message->id) }}" 
                                               class="text-decoration-none">
                                                {{ $message->subject }}
                                                @if($message->replies->count() > 0)
                                                    <span class="badge badge-secondary badge-sm ml-1">
                                                        {{ $message->replies->count() }}
                                                    </span>
                                                @endif
                                            </a>
                                        </td>
                                        <td>{!! $message->priority_badge !!}</td>
                                        <td>{{ $message->created_at->format('M j, Y H:i') }}</td>
                                        <td>{!! $message->status_badge !!}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('student.communication.message', $message->id) }}" 
                                                   class="btn btn-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-primary btn-sm" 
                                                        onclick="replyToMessage({{ $message->id }})" title="Reply">
                                                    <i class="fas fa-reply"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Messages Found</h5>
                        <p class="text-muted">
                            @if($filter === 'unread')
                                You have no unread messages.
                            @elseif($filter === 'sent')
                                You haven't sent any messages yet.
                            @elseif($filter === 'received')
                                You haven't received any messages yet.
                            @else
                                You don't have any messages yet.
                            @endif
                        </p>
                        <button type="button" class="btn btn-primary" onclick="showComposeModal()">
                            <i class="fas fa-plus mr-1"></i> Send Your First Message
                        </button>
                    </div>
                @endif
            </div>
            @if($messages->hasPages())
                <div class="card-footer">
                    {{ $messages->appends(request()->query())->links() }}
                </div>
            @endif
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
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->user->id }}">{{ $teacher->full_name }}</option>
                            @endforeach
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
@endsection

@push('scripts')
<script>
function showComposeModal() {
    $('#composeModal').modal('show');
}

function replyToMessage(messageId) {
    window.location.href = `/student/communication/messages/${messageId}`;
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
});
</script>
@endpush