@extends('layouts.parent')

@section('title', 'Communication')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Communication</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Communication</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Quick Actions -->
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('parent.communication.messages') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Send Message to Teacher
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('parent.communication.meetings') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-calendar-plus mr-2"></i>
                                    Request Meeting
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Conversations -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Conversations</h3>
                    </div>
                    <div class="card-body">
                        @if($conversations->count() > 0)
                            <div class="list-group">
                                @foreach($conversations as $conversation)
                                    <a href="{{ route('parent.communication.messages', ['conversation_id' => $conversation->id]) }}" 
                                       class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">
                                                {{ $conversation->subject }}
                                                @if($conversation->unreadMessagesCount(auth()->id()) > 0)
                                                    <span class="badge badge-primary">{{ $conversation->unreadMessagesCount(auth()->id()) }}</span>
                                                @endif
                                            </h6>
                                            <small>{{ $conversation->updated_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">
                                            <strong>Teacher:</strong> {{ $conversation->teacher->name }}<br>
                                            <strong>Student:</strong> {{ $conversation->student->name }}
                                        </p>
                                        @if($conversation->lastMessage)
                                            <small class="text-muted">
                                                {{ Str::limit($conversation->lastMessage->message, 100) }}
                                            </small>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                            
                            <div class="mt-3">
                                {{ $conversations->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No conversations yet. Start by sending a message to a teacher.</p>
                                <a href="{{ route('parent.communication.messages') }}" class="btn btn-primary">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Send First Message
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upcoming Meetings -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Upcoming Meetings</h3>
                    </div>
                    <div class="card-body">
                        @if($upcomingMeetings->count() > 0)
                            @foreach($upcomingMeetings as $meeting)
                                <div class="card card-outline card-info mb-2">
                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-1">
                                            {{ $meeting->teacher->name }}
                                            <span class="badge {{ $meeting->status_badge }}">{{ ucfirst($meeting->status) }}</span>
                                        </h6>
                                        <p class="card-text small mb-1">
                                            <strong>Student:</strong> {{ $meeting->student->name }}<br>
                                            <strong>Date:</strong> {{ $meeting->formatted_scheduled_time ?? $meeting->formatted_preferred_time }}<br>
                                            <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $meeting->meeting_type)) }}
                                        </p>
                                        <p class="card-text small text-muted">
                                            {{ Str::limit($meeting->purpose, 80) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="text-center mt-2">
                                <a href="{{ route('parent.communication.meetings') }}" class="btn btn-sm btn-outline-primary">
                                    View All Meetings
                                </a>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-calendar fa-2x text-muted mb-2"></i>
                                <p class="text-muted small">No upcoming meetings</p>
                                <a href="{{ route('parent.communication.meetings') }}" class="btn btn-sm btn-success">
                                    Request Meeting
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- My Children -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">My Children</h3>
                    </div>
                    <div class="card-body">
                        @foreach($children as $child)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $child->name }}</h6>
                                    <small class="text-muted">
                                        {{ $child->classModel->name ?? 'No class assigned' }}
                                        @if($child->classModel && $child->classModel->teacher)
                                            - {{ $child->classModel->teacher->name }}
                                        @endif
                                    </small>
                                </div>
                                <div>
                                    @if($child->classModel && $child->classModel->teacher)
                                        <a href="{{ route('parent.communication.messages', ['teacher_id' => $child->classModel->teacher->id, 'student_id' => $child->id]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection