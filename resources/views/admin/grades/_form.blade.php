<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="student_id">Student</label>
            <select class="form-control select2" id="student_id" name="student_id" required>
                <option value="">Select Student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ (isset($grade) && $grade->student_id == $student->id) ? 'selected' : '' }}>
                        {{ $student->user->name }} ({{ $student->student_id }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="subject_id">Subject</label>
            <select class="form-control select2" id="subject_id" name="subject_id" required>
                <option value="">Select Subject</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ (isset($grade) && $grade->subject_id == $subject->id) ? 'selected' : '' }}>
                        {{ $subject->name }} ({{ $subject->code }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="class_id">Class</label>
            <select class="form-control select2" id="class_id" name="class_id" required>
                <option value="">Select Class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ (isset($grade) && $grade->class_id == $class->id) ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="exam_type">Exam Type</label>
            <select class="form-control" id="exam_type" name="exam_type" required>
                <option value="quiz" {{ (isset($grade) && $grade->exam_type == 'quiz') ? 'selected' : '' }}>Quiz</option>
                <option value="midterm" {{ (isset($grade) && $grade->exam_type == 'midterm') ? 'selected' : '' }}>Midterm</option>
                <option value="final" {{ (isset($grade) && $grade->exam_type == 'final') ? 'selected' : '' }}>Final</option>
                <option value="assignment" {{ (isset($grade) && $grade->exam_type == 'assignment') ? 'selected' : '' }}>Assignment</option>
                <option value="project" {{ (isset($grade) && $grade->exam_type == 'project') ? 'selected' : '' }}>Project</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="marks_obtained">Marks Obtained</label>
            <input type="number" class="form-control" id="marks_obtained" name="marks_obtained" value="{{ old('marks_obtained', $grade->marks_obtained ?? '') }}" required min="0">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="total_marks">Total Marks</label>
            <input type="number" class="form-control" id="total_marks" name="total_marks" value="{{ old('total_marks', $grade->total_marks ?? '') }}" required min="1">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="grade">Grade</label>
            <input type="text" class="form-control" id="grade" name="grade" value="{{ old('grade', $grade->grade ?? '') }}" required>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="exam_date">Exam Date</label>
    <input type="date" class="form-control" id="exam_date" name="exam_date" value="{{ old('exam_date', isset($grade) ? $grade->exam_date->format('Y-m-d') : '') }}" required>
</div>

<div class="form-group">
    <label for="remarks">Remarks</label>
    <textarea class="form-control" id="remarks" name="remarks" rows="3">{{ old('remarks', $grade->remarks ?? '') }}</textarea>
</div>
