<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-day mr-2"></i>
            Daily Schedule - {{ $currentDate->format('l, M j, Y') }}
        </h3>
        <div class="card-tools">
            <div class="btn-group btn-group-sm">
                <a href="{{ route('student.academic.schedule', ['view' => 'daily', 'date' => $currentDate->copy()->subDay()->format('Y-m-d')]) }}" 
                   class="btn btn-default">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="{{ route('student.academic.schedule', ['view' => 'daily', 'date' => now()->format('Y-m-d')]) }}" 
                   class="btn btn-default">
                    Today
                </a>
                <a href="{{ route('student.academic.schedule', ['view' => 'daily', 'date' => $currentDate->copy()->addDay()->format('Y-m-d')]) }}" 
                   class="btn btn-default">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(count($scheduleData['class_schedule']) > 0)
            <div class="timeline">
                @foreach($scheduleData['class_schedule'] as $index => $schedule)
                    <div class="time-label">
                        <span class="bg-primary">{{ $schedule['start_time'] }}</span>
                    </div>
                    <div>
                        <i class="fas fa-book bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time">
                                <i class="fas fa-clock"></i> 
                                {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                                ({{ $schedule['duration'] }} min)
                            </span>
                            <h3 class="timeline-header">
                                <strong>{{ $schedule['subject'] }}</strong>
                            </h3>
                            <div class="timeline-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <i class="fas fa-user mr-2"></i>
                                            <strong>Teacher:</strong> {{ $schedule['teacher'] }}
                                        </p>
                                        @if($schedule['room'])
                                            <p class="mb-1">
                                                <i class="fas fa-map-marker-alt mr-2"></i>
                                                <strong>Room:</strong> {{ $schedule['room'] }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box bg-light">
                                            <span class="info-box-icon bg-primary">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Duration</span>
                                                <span class="info-box-number">{{ $schedule['duration'] }} minutes</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div>
                    <i class="fas fa-clock bg-gray"></i>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Classes Today</h5>
                <p class="text-muted">You don't have any classes scheduled for {{ $currentDate->format('l, M j, Y') }}.</p>
                @if($currentDate->isWeekend())
                    <p class="text-info">
                        <i class="fas fa-info-circle"></i>
                        It's the weekend! Enjoy your time off.
                    </p>
                @endif
            </div>
        @endif
    </div>
</div>

@if(count($scheduleData['class_schedule']) > 0)
    <!-- Daily Summary -->
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-pie mr-2"></i>
                Daily Summary
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-book"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Classes</span>
                            <span class="info-box-number">{{ count($scheduleData['class_schedule']) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success">
                            <i class="fas fa-clock"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Hours</span>
                            <span class="info-box-number">
                                {{ number_format(collect($scheduleData['class_schedule'])->sum('duration') / 60, 1) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning">
                            <i class="fas fa-play"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">First Class</span>
                            <span class="info-box-number">
                                {{ collect($scheduleData['class_schedule'])->min('start_time') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger">
                            <i class="fas fa-stop"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Last Class</span>
                            <span class="info-box-number">
                                {{ collect($scheduleData['class_schedule'])->max('end_time') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif