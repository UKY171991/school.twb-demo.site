@extends('adminlte::page')

@section('title', 'Create Exam Timetable')

@section('content_header')
    <h1>Create Exam Timetable for Class</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bulk Create Exam Timetable</h3>
        <div class="card-tools">
            <a href="{{ route('exam-timetables.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Timetables
            </a>
        </div>
    </div>
    <form action="{{ route('exam-timetables.bulk-store') }}" method="POST">
        @csrf
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
                        <select name="exam_type_id" id="exam_type_id" class="form-control @error('exam_type_id') is-invalid @enderror" required>
                            <option value="">Select Exam Type</option>
                            @foreach($examTypes as $examType)
                                <option value="{{ $examType->id }}" {{ old('exam_type_id') == $examType->id ? 'selected' : '' }}>
                                    {{ $examType->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('exam_type_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="academic_year">Academic Year</label>
                        <select name="academic_year" id="academic_year" class="form-control @error('academic_year') is-invalid @enderror" required>
                            @php
                                $currentYear = date('Y');
                                $academicYears = [
                                    ($currentYear-1) . '-' . $currentYear,
                                    $currentYear . '-' . ($currentYear+1),
                                    ($currentYear+1) . '-' . ($currentYear+2)
                                ];
                            @endphp
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ old('academic_year', $currentYear . '-' . ($currentYear+1)) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class">Class</label>
                        <select name="class" id="class" class="form-control @error('class') is-invalid @enderror" required>
                            <option value="">Select Class</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->name }}" {{ old('class') == $grade->name ? 'selected' : '' }}>
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="section">Section</label>
                        <input type="text" name="section" id="section" class="form-control @error('section') is-invalid @enderror" 
                               value="{{ old('section') }}" placeholder="e.g., A, B, C (optional)">
                        @error('section')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="exam_center">Exam Center</label>
                        <input type="text" name="exam_center" id="exam_center" class="form-control @error('exam_center') is-invalid @enderror" 
                               value="{{ old('exam_center') }}" placeholder="Will use school name if empty">
                        @error('exam_center')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5>Subject-wise Exam Schedule</h5>
            <p class="text-muted">Set the date and time for each subject's examination.</p>

            <div id="subjects-container">
                @foreach($subjects as $index => $subject)
                    <div class="subject-row border rounded p-3 mb-3" style="background: #f8f9fa;">
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
                                    <input type="date" name="subjects[{{ $index }}][exam_date]" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <input type="time" name="subjects[{{ $index }}][start_time]" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>End Time</label>
                                    <input type="time" name="subjects[{{ $index }}][end_time]" class="form-control" required>
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
                                              placeholder="Any special instructions for this subject exam"></textarea>
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
                <i class="fas fa-save"></i> Create Timetable
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
        const diffMs = end - start;
        const diffHours = diffMs / (1000 * 60 * 60);
        
        if (diffHours > 0) {
            durationDisplay.value = `${diffHours} hours`;
        } else {
            durationDisplay.value = 'Invalid time range';
        }
    }
}

// Add event listeners for duration calculation
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name*="[start_time]"], input[name*="[end_time]"]').forEach(input => {
        input.addEventListener('change', function() {
            const subjectRow = this.closest('.subject-row');
            updateDurationDisplay(subjectRow);
        });
    });
});
</script>
@stop