@extends('adminlte::page')

@section('title', 'Add Marks')

@section('content_header')
    <h1>Add Exam Marks</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Enter Marks Information</h3>
        </div>
        <div class="card-body">
            <!-- Grade Selection Form -->
            <form id="gradeForm" method="GET" action="{{ route('marks.create') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="grade_id">Select Grade/Class <span class="text-danger">*</span></label>
                            <select name="grade_id" id="grade_id" class="form-control" required>
                                <option value="">Select Grade</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }} @if($grade->section) - {{ $grade->section }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-info btn-block">Load</button>
                    </div>
                </div>
            </form>

            @if(count($students) > 0 && count($subjects) > 0)
                <!-- Marks Entry Form -->
                <hr>
                <form action="{{ route('marks.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student_id">Student <span class="text-danger">*</span></label>
                                <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="subject_id">Subject <span class="text-danger">*</span></label>
                                <select name="subject_id" id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" required>
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exam_type">Exam Type <span class="text-danger">*</span></label>
                                <select name="exam_type" id="exam_type" class="form-control @error('exam_type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    <option value="Midterm" {{ old('exam_type') == 'Midterm' ? 'selected' : '' }}>Midterm</option>
                                    <option value="Final" {{ old('exam_type') == 'Final' ? 'selected' : '' }}>Final</option>
                                    <option value="Quiz" {{ old('exam_type') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                    <option value="Assignment" {{ old('exam_type') == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                                </select>
                                @error('exam_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mark_obtained">Marks Obtained <span class="text-danger">*</span></label>
                                <input type="number" name="mark_obtained" id="mark_obtained" 
                                       class="form-control @error('mark_obtained') is-invalid @enderror" 
                                       value="{{ old('mark_obtained') }}" step="0.01" min="0" required>
                                @error('mark_obtained')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="total_marks">Total Marks <span class="text-danger">*</span></label>
                                <input type="number" name="total_marks" id="total_marks" 
                                       class="form-control @error('total_marks') is-invalid @enderror" 
                                       value="{{ old('total_marks', 100) }}" step="0.01" min="1" required>
                                @error('total_marks')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exam_date">Exam Date <span class="text-danger">*</span></label>
                                <input type="date" name="exam_date" id="exam_date" 
                                       class="form-control @error('exam_date') is-invalid @enderror" 
                                       value="{{ old('exam_date', date('Y-m-d')) }}" required>
                                @error('exam_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('marks.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            @elseif(request('grade_id'))
                <div class="alert alert-warning mt-3">
                    No students or subjects found in the selected grade.
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
