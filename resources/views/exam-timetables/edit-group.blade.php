@extends('adminlte::page')

@section('title', 'Edit Exam Timetable')

@section('content_header')
    <h1>Edit Exam Timetable for Class</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Exam Timetable</h3>
        <div class="card-tools">
            <a href="{{ route('exam-timetables.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Timetables
            </a>
        </div>
    </div>
    <form action="{{ route('exam-timetables.update-group') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Basic Information -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exam_type_id">Exam Type</label>
                        <select name="exam_type_id" id="exam_type_id" class="form-control" readonly style="pointer-events: none; background-color: #e9ecef;">
                            @foreach($examTypes as $examType)
                                <option value="{{ $examType->id }}" {{ $groupData['exam_type_id'] == $examType->id ? 'selected' : '' }}>
                                    {{ $examType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="academic_year">Academic Year</label>
                        <input type="text" name="academic_year" class="form-control" value="{{ $groupData['academic_year'] }}" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class">Class</label>
                        <input type="text" name="class" class="form-control" value="{{ $groupData['class'] }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="section">Section</label>
                        <input type="text" name="section" class="form-control" value="{{ $groupData['section'] }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="exam_center">Exam Center</label>
                        <input type="text" name="exam_center" id="exam_center" class="form-control @error('exam_center') is-invalid @enderror" 
                               value="{{ old('exam_center') ?? $groupData['exam_center'] }}" placeholder="Will use school name if empty">
                        @error('exam_center')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5>Subject-wise Exam Schedule</h5>
            <p class="text-muted">Set the date and time for each subject's examination. Leave date/time blank to remove a subject from the schedule.</p>

            <div id="subjects-container">
                @foreach($subjects as $index => $subject)
                    @php
                        $timetable = $timetables[$subject->id] ?? null;
                    @endphp
                    <div class="subject-row border rounded p-3 mb-3" style="background: {{ $timetable ? '#f8f9fa' : '#ffffff' }}; border-left: 5px solid {{ $timetable ? '#28a745' : '#dee2e6' }} !important;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Subject</label>
                                    <input type="hidden" name="subjects[{{ $index }}][subject_id]" value="{{ $subject->id }}">
                                    <input type="text" class="form-control" value="{{ $subject->name }}" readonly>
                                    <small class="text-muted">Max: {{ $subject->max_marks }}, Pass: {{ $subject->pass_marks }}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Exam Date</label>
                                    <input type="date" name="subjects[{{ $index }}][exam_date]" class="form-control" 
                                           value="{{ $timetable ? $timetable->exam_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <input type="time" name="subjects[{{ $index }}][start_time]" class="form-control" 
                                           value="{{ $timetable ? $timetable->start_time->format('H:i') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>End Time</label>
                                    <input type="time" name="subjects[{{ $index }}][end_time]" class="form-control" 
                                           value="{{ $timetable ? $timetable->end_time->format('H:i') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Duration</label>
                                    <input type="text" class="form-control duration-display" readonly placeholder="Auto calculated">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Special Instructions (Optional)</label>
                                    <textarea name="subjects[{{ $index }}][instructions]" class="form-control" rows="2" 
                                              placeholder="Any special instructions for this subject exam">{{ $timetable ? $timetable->instructions : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Fill Options -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Quick Fill Options</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Start Date</label>
                            <input type="date" id="quick_start_date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label>Start Time</label>
                            <input type="time" id="quick_start_time" class="form-control" value="10:00">
                        </div>
                        <div class="col-md-2">
                            <label>Duration (hours)</label>
                            <input type="number" id="quick_duration" class="form-control" value="3" min="1" max="8" step="0.5">
                        </div>
                        <div class="col-md-2">
                            <label>Gap (days)</label>
                            <input type="number" id="quick_gap" class="form-control" value="1" min="0" max="7">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-info btn-block" onclick="quickFillDates()">
                                <i class="fas fa-magic"></i> Auto Fill All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Update Timetable
            </button>
            <a href="{{ route('exam-timetables.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
function quickFillDates() {
    const startDate = document.getElementById('quick_start_date').value;
    const startTime = document.getElementById('quick_start_time').value;
    const duration = parseFloat(document.getElementById('quick_duration').value);
    const gap = parseInt(document.getElementById('quick_gap').value);
    
    if (!startDate || !startTime || !duration) {
        alert('Please fill in start date, start time, and duration');
        return;
    }
    
    const subjects = document.querySelectorAll('.subject-row');
    let currentDate = new Date(startDate);
    
    subjects.forEach((subject, index) => {
        // Set date
        const dateInput = subject.querySelector('input[name*="[exam_date]"]');
        dateInput.value = currentDate.toISOString().split('T')[0];
        
        // Set start time
        const startTimeInput = subject.querySelector('input[name*="[start_time]"]');
        startTimeInput.value = startTime;
        
        // Calculate end time
        const endTimeInput = subject.querySelector('input[name*="[end_time]"]');
        const startDateTime = new Date(`2000-01-01T${startTime}:00`);
        const endDateTime = new Date(startDateTime.getTime() + (duration * 60 * 60 * 1000));
        endTimeInput.value = endDateTime.toTimeString().slice(0, 5);
        
        // Update duration display
        updateDurationDisplay(subject);
        
        // Move to next date
        currentDate.setDate(currentDate.getDate() + gap);
    });
}

function updateDurationDisplay(subjectRow) {
    const startTime = subjectRow.querySelector('input[name*="[start_time]"]').value;
    const endTime = subjectRow.querySelector('input[name*="[end_time]"]').value;
    const durationDisplay = subjectRow.querySelector('.duration-display');
    
    if (startTime && endTime) {
        const start = new Date(`2000-01-01T${startTime}:00`);
        const end = new Date(`2000-01-01T${endTime}:00`);
        let diffMs = end - start;
        if (diffMs < 0) diffMs += 24 * 60 * 60 * 1000; // Handle overnight
        const diffHours = diffMs / (1000 * 60 * 60);
        
        if (diffHours > 0) {
            durationDisplay.value = `${diffHours.toFixed(1)} hours`;
        } else {
            durationDisplay.value = 'Invalid time range';
        }
    }
}

// Add event listeners for duration calculation
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name*="[start_time]"], input[name*="[end_time]"]').forEach(input => {
        // Initial calculation
        const subjectRow = input.closest('.subject-row');
        updateDurationDisplay(subjectRow);

        input.addEventListener('change', function() {
            const subjectRow = this.closest('.subject-row');
            updateDurationDisplay(subjectRow);
        });
    });
});
</script>
@stop
