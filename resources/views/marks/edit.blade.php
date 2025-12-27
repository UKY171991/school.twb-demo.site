@extends('adminlte::page')

@section('title', 'Edit Marks')

@section('content_header')
    <h1>Edit Exam Marks</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Update Marks Information</h3>
        </div>
        <form action="{{ route('marks.update', $mark->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Student Name</label>
                            <input type="text" class="form-control" value="{{ $mark->student->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subject_id">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" required>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $mark->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
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
                            <label for="exam_type">Exam Type <span class="text-danger">*</span></label>
                            <select name="exam_type" id="exam_type" class="form-control @error('exam_type') is-invalid @enderror" required>
                                <option value="Midterm" {{ old('exam_type', $mark->exam_type) == 'Midterm' ? 'selected' : '' }}>Midterm</option>
                                <option value="Final" {{ old('exam_type', $mark->exam_type) == 'Final' ? 'selected' : '' }}>Final</option>
                                <option value="Quiz" {{ old('exam_type', $mark->exam_type) == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                <option value="Assignment" {{ old('exam_type', $mark->exam_type) == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                            </select>
                            @error('exam_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mark_obtained">Marks Obtained <span class="text-danger">*</span></label>
                            <input type="number" name="mark_obtained" id="mark_obtained" 
                                   class="form-control @error('mark_obtained') is-invalid @enderror" 
                                   value="{{ old('mark_obtained', $mark->mark_obtained) }}" step="0.01" min="0" required>
                            @error('mark_obtained')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_marks">Total Marks <span class="text-danger">*</span></label>
                            <input type="number" name="total_marks" id="total_marks" 
                                   class="form-control @error('total_marks') is-invalid @enderror" 
                                   value="{{ old('total_marks', $mark->total_marks) }}" step="0.01" min="1" required>
                            @error('total_marks')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exam_date">Exam Date <span class="text-danger">*</span></label>
                            <input type="date" name="exam_date" id="exam_date" 
                                   class="form-control @error('exam_date') is-invalid @enderror" 
                                   value="{{ old('exam_date', $mark->exam_date) }}" required>
                            @error('exam_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('marks.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
