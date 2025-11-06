@extends('layouts.student')

@section('title', 'Class Schedule')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Class Schedule</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.academic.index') }}">Academic</a></li>
                    <li class="breadcrumb-item active">Schedule</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Schedule Controls -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="btn-group" role="group">
                    <a href="{{ route('student.academic.schedule', ['view' => 'daily', 'date' => $currentDate->format('Y-m-d')]) }}" 
                       class="btn {{ request('view', 'weekly') === 'daily' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Daily
                    </a>
                    <a href="{{ route('student.academic.schedule', ['view' => 'weekly', 'date' => $currentDate->format('Y-m-d')]) }}" 
                       class="btn {{ request('view', 'weekly') === 'weekly' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Weekly
                    </a>
                    <a href="{{ route('student.academic.schedule', ['view' => 'monthly', 'date' => $currentDate->format('Y-m-d')]) }}" 
                       class="btn {{ request('view', 'weekly') === 'monthly' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Monthly
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="float-right">
                    <input type="date" class="form-control d-inline-block" style="width: auto;" 
                           value="{{ $currentDate->format('Y-m-d') }}" 
                           onchange="window.location.href='{{ route('student.academic.schedule') }}?view={{ request('view', 'weekly') }}&date=' + this.value">
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Schedule Display -->
            <div class="col-md-8">
                @if(request('view', 'weekly') === 'daily')
                    @include('student.academic.partials.daily-schedule')
                @elseif(request('view', 'weekly') === 'weekly')
                    @include('student.academic.partials.weekly-schedule')
                @else
                    @include('student.academic.partials.monthly-schedule')
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Upcoming Assignments -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tasks mr-2"></i>
                            Upcoming Assignments
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        @if(count($scheduleData['upcoming_assignments']) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($scheduleData['upcoming_assignments'] as $assignment)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $assignment['title'] }}</h6>
                                                <p class="mb-1 text-muted small">{{ $assignment['subject'] }}</p>
                                                <small class="text-muted">
                                                    Due: {{ Carbon\Carbon::parse($assignment['due_date'])->format('M j, Y') }}
                                                    @if($assignment['due_time'])
                                                        at {{ $assignment['due_time'] }}
                                                    @endif
                                                </small>
                                            </div>
                                            <span class="badge badge-{{ $assignment['priority_color'] }}">
                                                {{ $assignment['days_until_due'] }} days
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-tasks fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No upcoming assignments</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('student.academic.assignments') }}" class="btn btn-sm btn-primary">
                            View All Assignments
                        </a>
                    </div>
                </div>

                <!-- Exam Schedule -->
                @if(count($scheduleData['exam_schedule']) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                Upcoming Exams
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($scheduleData['exam_schedule'] as $exam)
                                    <div class="list-group-item">
                                        <h6 class="mb-1">{{ $exam['subject'] }}</h6>
                                        <p class="mb-1 text-muted small">{{ ucfirst($exam['exam_type']) }}</p>
                                        <small class="text-muted">
                                            {{ Carbon\Carbon::parse($exam['exam_date'])->format('M j, Y') }}
                                            • {{ $exam['total_marks'] }} marks
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Academic Calendar -->
                @if(count($scheduleData['academic_calendar']) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar mr-2"></i>
                                Academic Calendar
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach(array_slice($scheduleData['academic_calendar'], 0, 5) as $event)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $event['title'] }}</h6>
                                                <small class="text-muted">{{ $event['subject'] }}</small>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge badge-{{ $event['color'] }}">{{ ucfirst($event['type']) }}</span>
                                                <br>
                                                <small class="text-muted">{{ Carbon\Carbon::parse($event['date'])->format('M j') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh every 5 minutes
    setTimeout(function() {
        location.reload();
    }, 300000);
});
</script>
@endpush