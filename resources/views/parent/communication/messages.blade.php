@extends('layouts.parent')

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
                    <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('parent.communication.index') }}">Communication</a></li>
                    <li class="breadcrumb-item active">Messages</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            @if($conversation)
                                Conversation: {{ $conversation->subject }}
                            @else
                                New Message
                            @endif
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('parent.communication.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back to Communication
                            </a>
                        </div>
                    </div>
                    
                    @if($conversation)
                        <!-- Conversation Header -->
                        <div class="card-body border-bottom">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Teacher:</strong> {{ $conversation->teacher->name }}<br>
                                    <strong>Student:</strong> {{ $conversation->student->name }}
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted">
                                        Started: {{ $conversation->created_at->format('M d, Y \a\t g:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;" id="messages-container">
                            @foreach($messages as $message)
                                <div class="message-item mb-3 {{ $message->sender_id === auth()->id() ? 'text-right' : '' }}">
                                    <div class="d-inline-block {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded" style="max-width: 70%;">
                                        <div class="message-content">
                                            {{ $message->message }}
                                        </div>
                                        @if($message->attachment_path)
                                            <div class="mt-2">
                                                <a href="{{ route('parent.communication.download-attachment', $message->id) }}" 
                                                   class="btn btn-sm {{ $message->sender_id === auth()->id() ? 'btn-light' : 'btn-primary' }}">
                                                    <i class="fas fa-paperclip mr-1"></i>
                                                    Download Attachment
                                                </a>
                                            </div>
                                        @endif
                                        <div class="message-meta mt-2 small {{ $message->sender_id === auth()->id() ? 'text-light' : 'text-muted' }}">
                                            <strong>{{ $message->sender->name }}</strong> - 
                                            {{ $message->created_at->format('M d, Y \a\t g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Message Form -->
                    <div class="card-footer">
                        <form id="message-form" enctype="multipart/form-data">
                            @csrf
                            @if($conversation)
                                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                            @else
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="teacher_id" class="form-label">Select Teacher</label>
                                        <select name="teacher_id" id="teacher_id" class="form-control" required>
                                            <option value="">Choose a teacher...</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="student_id" class="form-label">Regarding Student</label>
                                        <select name="student_id" id="student_id" class="form-control" required>
                                            <option value="">Choose a student...</option>
                                            @foreach($children as $child)
                                                <option value="{{ $child->id }}" {{ request('student_id') == $child->id ? 'selected' : '' }}>
                                                    {{ $child->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" name="subject" id="subject" class="form-control" 
                                           placeholder="Enter message subject" required>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea name="message" id="message" class="form-control" rows="4" 
                                          placeholder="Type your message here..." required></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="attachment" class="form-label">Attachment (optional)</label>
                                        <input type="file" name="attachment" id="attachment" class="form-control-file"
                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <small class="form-text text-muted">
                                            Max file size: 10MB. Allowed types: PDF, DOC, DOCX, JPG, PNG
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary" id="send-btn">
                                        <i class="fas fa-paper-plane mr-1"></i>
                                        Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-scroll to bottom of messages
    function scrollToBottom() {
        const container = $('#messages-container');
        if (container.length) {
            container.scrollTop(container[0].scrollHeight);
        }
    }
    
    scrollToBottom();

    // Handle message form submission
    $('#message-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const sendBtn = $('#send-btn');
        const originalText = sendBtn.html();
        
        sendBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Sending...');
        
        $.ajax({
            url: '{{ route("parent.communication.send-message") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // If this is a new conversation, redirect to the conversation
                    if (!$('input[name="conversation_id"]').val()) {
                        window.location.href = '{{ route("parent.communication.messages") }}?conversation_id=' + response.data.conversation_id;
                    } else {
                        // Add message to the conversation
                        const message = response.data.message;
                        const messageHtml = `
                            <div class="message-item mb-3 text-right">
                                <div class="d-inline-block bg-primary text-white p-3 rounded" style="max-width: 70%;">
                                    <div class="message-content">${message.message}</div>
                                    <div class="message-meta mt-2 small text-light">
                                        <strong>${message.sender.name}</strong> - 
                                        ${new Date(message.created_at).toLocaleString()}
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        $('#messages-container').append(messageHtml);
                        scrollToBottom();
                        
                        // Clear the message textarea
                        $('#message').val('');
                        $('#attachment').val('');
                    }
                } else {
                    toastr.error(response.message || 'Failed to send message');
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
                    toastr.error('Failed to send message. Please try again.');
                }
            },
            complete: function() {
                sendBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Mark messages as read when viewing conversation
    @if($conversation)
        $.ajax({
            url: '{{ route("parent.communication.mark-as-read") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                conversation_id: {{ $conversation->id }}
            }
        });
    @endif
});
</script>
@endpush