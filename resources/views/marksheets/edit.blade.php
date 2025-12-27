@extends('adminlte::page')

@section('title', 'Edit Marksheet')

@section('content_header')
    <h1>Edit Marksheet</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Marksheet Details</h3>
    </div>
    <form action="{{ route('marksheets.update', $marksheet) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="student_id">Student</label>
                        <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ $marksheet->student_id == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->roll_number }}) - {{ $student->class }}-{{ $student->section }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exam_type_id">Exam Type</label>
                        <select name="exam_type_id" id="exam_type_id" class="form-control @error('exam_type_id') is-invalid @enderror" required>
                            <option value="">Select Exam Type</option>
                            @foreach(\App\Models\ExamType::getActiveTypes() as $examType)
                                <option value="{{ $examType->id }}" 
                                        data-exam-name="{{ $examType->name }}"
                                        {{ $marksheet->exam_type_id == $examType->id ? 'selected' : '' }}>
                                    {{ $examType->name }} ({{ $examType->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('exam_type_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exam_name">Exam Name</label>
                        <input type="text" name="exam_name" id="exam_name" class="form-control @error('exam_name') is-invalid @enderror" 
                               value="{{ old('exam_name', $marksheet->exam_name) }}" placeholder="Will be auto-filled when exam type is selected" required readonly>
                        @error('exam_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Exam name will be automatically set based on selected exam type. 
                            <a href="#" id="toggle-exam-name-edit">Click to edit manually</a>
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="academic_year">Academic Year</label>
                        <select name="academic_year" id="academic_year" class="form-control @error('academic_year') is-invalid @enderror" required>
                            <option value="">Select Academic Year</option>
                            @php
                                $currentYear = date('Y');
                                $academicYears = [
                                    ($currentYear-2) . '-' . ($currentYear-1),
                                    ($currentYear-1) . '-' . $currentYear,
                                    $currentYear . '-' . ($currentYear+1),
                                    ($currentYear+1) . '-' . ($currentYear+2)
                                ];
                            @endphp
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ old('academic_year', $marksheet->academic_year) == $year ? 'selected' : '' }}>
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exam_date">Exam Date</label>
                        <input type="date" name="exam_date" id="exam_date" class="form-control @error('exam_date') is-invalid @enderror" 
                               value="{{ old('exam_date', $marksheet->exam_date->format('Y-m-d')) }}" required>
                        @error('exam_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="recalculate_position" name="recalculate_position" value="1" checked>
                            <label class="form-check-label" for="recalculate_position">
                                Recalculate class position after update
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <h4>Subject Marks</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Subject Code</th>
                            <th>Max Marks</th>
                            <th>Pass Marks</th>
                            <th>Obtained Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                            @php
                                $existingMark = $marksheet->marks->where('subject_id', $subject->id)->first();
                            @endphp
                            <tr>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->code }}</td>
                                <td>{{ $subject->max_marks }}</td>
                                <td>{{ $subject->pass_marks }}</td>
                                <td>
                                    <input type="number" name="marks[{{ $subject->id }}]" 
                                           class="form-control @error('marks.' . $subject->id) is-invalid @enderror" 
                                           min="0" max="{{ $subject->max_marks }}" 
                                           value="{{ old('marks.' . $subject->id, $existingMark ? $existingMark->obtained_marks : '') }}" required>
                                    @error('marks.' . $subject->id)
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Marksheet
            </button>
            <a href="{{ route('marksheets.show', $marksheet) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const examTypeSelect = document.getElementById('exam_type_id');
    const examNameInput = document.getElementById('exam_name');
    const toggleEditLink = document.getElementById('toggle-exam-name-edit');
    let manualEdit = false;

    // Handle exam type change
    examTypeSelect.addEventListener('change', function() {
        if (!manualEdit && this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const examName = selectedOption.getAttribute('data-exam-name');
            if (examName) {
                examNameInput.value = examName;
            }
        } else if (!this.value) {
            examNameInput.value = '';
        }
    });

    // Handle manual edit toggle
    toggleEditLink.addEventListener('click', function(e) {
        e.preventDefault();
        manualEdit = !manualEdit;
        
        if (manualEdit) {
            examNameInput.removeAttribute('readonly');
            examNameInput.focus();
            this.textContent = 'Use auto-fill';
            examNameInput.placeholder = 'Enter custom exam name';
        } else {
            examNameInput.setAttribute('readonly', 'readonly');
            this.textContent = 'Click to edit manually';
            examNameInput.placeholder = 'Will be auto-filled when exam type is selected';
            
            // Re-populate from selected exam type
            if (examTypeSelect.value) {
                const selectedOption = examTypeSelect.options[examTypeSelect.selectedIndex];
                const examName = selectedOption.getAttribute('data-exam-name');
                if (examName) {
                    examNameInput.value = examName;
                }
            }
        }
    });

    // Initialize on page load if exam type is already selected
    if (examTypeSelect.value) {
        const selectedOption = examTypeSelect.options[examTypeSelect.selectedIndex];
        const examName = selectedOption.getAttribute('data-exam-name');
        if (examName && !examNameInput.value) {
            examNameInput.value = examName;
        }
    }
});
</script>
@stop