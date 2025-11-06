<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-week mr-2"></i>
            Weekly Schedule - {{ $currentDate->format('M j, Y') }}
        </h3>
        <div class="card-tools">
            <div class="btn-group btn-group-sm">
                <a href="{{ route('student.academic.schedule', ['view' => 'weekly', 'date' => $currentDate->copy()->subWeek()->format('Y-m-d')]) }}" 
                   class="btn btn-default">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="{{ route('student.academic.schedule', ['view' => 'weekly', 'date' => now()->format('Y-m-d')]) }}" 
                   class="btn btn-default">
                    Today
                </a>
                <a href="{{ route('student.academic.schedule', ['view' => 'weekly', 'date' => $currentDate->copy()->addWeek()->format('Y-m-d')]) }}" 
                   class="btn btn-default">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 80px;">Time</th>
                        @foreach($scheduleData['class_schedule'] as $day => $dayData)
                            <th class="text-center {{ $dayData['is_today'] ? 'bg-primary text-white' : '' }}">
                                <div>{{ $dayData['day_short'] }}</div>
                                <small>{{ Carbon\Carbon::parse($dayData['date'])->format('M j') }}</small>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $timeSlots = [];
                        foreach($scheduleData['class_schedule'] as $dayData) {
                            foreach($dayData['schedules'] as $schedule) {
                                $timeSlot = $schedule['start_time'] . ' - ' . $schedule['end_time'];
                                if (!in_array($timeSlot, $timeSlots)) {
                                    $timeSlots[] = $timeSlot;
                                }
                            }
                        }
                        sort($timeSlots);
                    @endphp
                    
                    @if(count($timeSlots) > 0)
                        @foreach($timeSlots as $timeSlot)
                            <tr>
                                <td class="bg-light text-center font-weight-bold">
                                    <small>{{ $timeSlot }}</small>
                                </td>
                                @foreach($scheduleData['class_schedule'] as $day => $dayData)
                                    <td class="{{ $dayData['is_today'] ? 'bg-light' : '' }}">
                                        @php
                                            $daySchedule = collect($dayData['schedules'])->first(function($schedule) use ($timeSlot) {
                                                return ($schedule['start_time'] . ' - ' . $schedule['end_time']) === $timeSlot;
                                            });
                                        @endphp
                                        
                                        @if($daySchedule)
                                            <div class="schedule-item p-2 rounded bg-primary text-white">
                                                <div class="font-weight-bold">{{ $daySchedule['subject'] }}</div>
                                                <small>{{ $daySchedule['teacher'] }}</small>
                                                @if($daySchedule['room'])
                                                    <br><small><i class="fas fa-map-marker-alt"></i> {{ $daySchedule['room'] }}</small>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No classes scheduled for this week</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.schedule-item {
    min-height: 60px;
    font-size: 0.85rem;
}
.schedule-item:hover {
    opacity: 0.9;
    cursor: pointer;
}
</style>