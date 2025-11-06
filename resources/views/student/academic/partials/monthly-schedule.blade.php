<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar mr-2"></i>
            Monthly Schedule - {{ $currentDate->format('F Y') }}
        </h3>
        <div class="card-tools">
            <div class="btn-group btn-group-sm">
                <a href="{{ route('student.academic.schedule', ['view' => 'monthly', 'date' => $currentDate->copy()->subMonth()->format('Y-m-d')]) }}" 
                   class="btn btn-default">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="{{ route('student.academic.schedule', ['view' => 'monthly', 'date' => now()->format('Y-m-d')]) }}" 
                   class="btn btn-default">
                    This Month
                </a>
                <a href="{{ route('student.academic.schedule', ['view' => 'monthly', 'date' => $currentDate->copy()->addMonth()->format('Y-m-d')]) }}" 
                   class="btn btn-default">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @php
            $startOfMonth = $currentDate->copy()->startOfMonth();
            $endOfMonth = $currentDate->copy()->endOfMonth();
            $startOfCalendar = $startOfMonth->copy()->startOfWeek();
            $endOfCalendar = $endOfMonth->copy()->endOfWeek();
            
            $calendarDays = [];
            $current = $startOfCalendar->copy();
            
            while ($current <= $endOfCalendar) {
                $calendarDays[] = $current->copy();
                $current->addDay();
            }
            
            $weeks = array_chunk($calendarDays, 7);
        @endphp
        
        <div class="calendar-grid">
            <!-- Calendar Header -->
            <div class="calendar-header bg-light">
                <div class="row no-gutters">
                    <div class="col text-center py-2 font-weight-bold">Sun</div>
                    <div class="col text-center py-2 font-weight-bold">Mon</div>
                    <div class="col text-center py-2 font-weight-bold">Tue</div>
                    <div class="col text-center py-2 font-weight-bold">Wed</div>
                    <div class="col text-center py-2 font-weight-bold">Thu</div>
                    <div class="col text-center py-2 font-weight-bold">Fri</div>
                    <div class="col text-center py-2 font-weight-bold">Sat</div>
                </div>
            </div>
            
            <!-- Calendar Body -->
            @foreach($weeks as $week)
                <div class="calendar-week">
                    <div class="row no-gutters">
                        @foreach($week as $day)
                            @php
                                $dayKey = $day->format('Y-m-d');
                                $daySchedule = $scheduleData['class_schedule'][$dayKey] ?? null;
                                $isCurrentMonth = $day->month === $currentDate->month;
                                $isToday = $day->isToday();
                                $isWeekend = $day->isWeekend();
                            @endphp
                            
                            <div class="col calendar-day {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }} {{ $isWeekend ? 'weekend' : '' }}">
                                <div class="day-content p-2" style="min-height: 100px;">
                                    <div class="day-number mb-1">
                                        <span class="badge {{ $isToday ? 'badge-primary' : ($isCurrentMonth ? 'badge-light' : 'badge-secondary') }}">
                                            {{ $day->format('j') }}
                                        </span>
                                    </div>
                                    
                                    @if($daySchedule && $isCurrentMonth)
                                        <div class="day-schedules">
                                            @if($daySchedule['schedule_count'] > 0)
                                                <div class="schedule-indicator mb-1">
                                                    <span class="badge badge-info badge-sm">
                                                        {{ $daySchedule['schedule_count'] }} class{{ $daySchedule['schedule_count'] > 1 ? 'es' : '' }}
                                                    </span>
                                                </div>
                                                
                                                @foreach(array_slice($daySchedule['schedules'], 0, 2) as $schedule)
                                                    <div class="schedule-item mb-1">
                                                        <small class="text-primary font-weight-bold d-block">
                                                            {{ $schedule['subject'] }}
                                                        </small>
                                                        <small class="text-muted">
                                                            {{ $schedule['time_slot'] }}
                                                        </small>
                                                    </div>
                                                @endforeach
                                                
                                                @if($daySchedule['schedule_count'] > 2)
                                                    <small class="text-muted">
                                                        +{{ $daySchedule['schedule_count'] - 2 }} more
                                                    </small>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <!-- Show academic events -->
                                    @php
                                        $dayEvents = collect($scheduleData['academic_calendar'])->filter(function($event) use ($dayKey) {
                                            return $event['date'] === $dayKey;
                                        });
                                    @endphp
                                    
                                    @if($dayEvents->isNotEmpty() && $isCurrentMonth)
                                        @foreach($dayEvents->take(1) as $event)
                                            <div class="event-item">
                                                <small class="badge badge-{{ $event['color'] }} badge-sm d-block mb-1">
                                                    {{ Str::limit($event['title'], 15) }}
                                                </small>
                                            </div>
                                        @endforeach
                                        
                                        @if($dayEvents->count() > 1)
                                            <small class="text-muted">
                                                +{{ $dayEvents->count() - 1 }} event{{ $dayEvents->count() > 2 ? 's' : '' }}
                                            </small>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Monthly Statistics -->
<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-bar mr-2"></i>
            Monthly Statistics
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-calendar-day"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">School Days</span>
                        <span class="info-box-number">
                            {{ collect($scheduleData['class_schedule'])->count() }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success">
                        <i class="fas fa-book"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Classes</span>
                        <span class="info-box-number">
                            {{ collect($scheduleData['class_schedule'])->sum('schedule_count') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning">
                        <i class="fas fa-tasks"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Assignments</span>
                        <span class="info-box-number">
                            {{ collect($scheduleData['academic_calendar'])->where('type', 'assignment')->count() }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-danger">
                        <i class="fas fa-graduation-cap"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Exams</span>
                        <span class="info-box-number">
                            {{ collect($scheduleData['academic_calendar'])->where('type', 'exam')->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.calendar-grid {
    border: 1px solid #dee2e6;
}

.calendar-day {
    border-right: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
}

.calendar-day:last-child {
    border-right: none;
}

.calendar-day.other-month {
    background-color: #f8f9fa;
    opacity: 0.6;
}

.calendar-day.today {
    background-color: #e3f2fd;
}

.calendar-day.weekend {
    background-color: #fafafa;
}

.day-content {
    font-size: 0.8rem;
}

.schedule-item {
    line-height: 1.2;
}

.event-item {
    margin-top: 2px;
}

.calendar-header {
    border-bottom: 2px solid #dee2e6;
}

.calendar-week:last-child .calendar-day {
    border-bottom: none;
}
</style>