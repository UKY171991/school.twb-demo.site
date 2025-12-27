@extends('adminlte::page')

@section('title', 'Create Exam Timetable Entry')

@section('content_header')
    <h1>Create Single Exam Timetable Entry</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Exam Schedule</h3>
        <div class="card-tools">
            <a href="{{ route('exam-timetables.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Timetables
            </a>
        </div>
    </div>
    <form action="{{ route('exam-timetables.store') }}" method="POST">
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
                        <label for="subject_id">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} (Max: {{ $subject->max_marks }})
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
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
                        <label for="exam_date">Exam Date</label>
                        <input type="date" name="exam_date" id="exam_date" class="form-control @error('exam_date') is-invalid @enderror" 
                               value="{{ old('exam_date') }}" required>
                        @error('exam_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" 
                               value="{{ old('start_time', '10:00') }}" required>
                        @error('start_time')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" 
                               value="{{ old('end_time', '13:00') }}" required>
                        @error('end_time')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" id="duration_display" class="form-control" readonly placeholder="Auto calculated">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exam_center">Exam Center</label>
                        <input type="text" name="exam_center" id="exam_center" class="form-control @error('exam_center') is-invalid @enderror" 
                               value="{{ old('exam_center') }}" placeholder="Will use school name if empty">
                        @error('exam_center')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="is_active">Status</label>
                        <select name="is_active" id="is_active" class="form-control @error('is_active') is-invalid @enderror">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="instructions">Special Instructions (Optional)</label>
                        <textarea name="instructions" id="instructions" class="form-control @error('instructions') is-invalid @enderror" 
                                  rows="3" placeholder="Any special instructions for this exam">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Create Timetable Entry
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
function updateDurationDisplay() {
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const durationDisplay = document.getElementById('duration_display');
    
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
    document.getElementById('start_time').addEventListener('change', updateDurationDisplay);
    document.getElementById('end_time').addEventListener('change', updateDurationDisplay);
    
    // Calculate initial duration if values are present
    updateDurationDisplay();
});
</script>
@stop