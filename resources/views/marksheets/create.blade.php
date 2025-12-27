@extends('adminlte::page')

@section('title', 'Create Marksheet')

@section('content_header')
    <h1>Create New Marksheet</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Marksheet Details</h3>
    </div>
    <form action="{{ route('marksheets.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="student_id">Student</label>
                        <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
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
                        <label for="exam_name">Exam Name</label>
                        <input type="text" name="exam_name" id="exam_name" class="form-control @error('exam_name') is-invalid @enderror" 
                               value="{{ old('exam_name') }}" placeholder="e.g., Mid Term, Final Term" required>
                        @error('exam_name')
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
                               value="{{ old('exam_date') }}" required>
                        @error('exam_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="academic_year">Academic Year</label>
                        <input type="text" name="academic_year" id="academic_year" class="form-control @error('academic_year') is-invalid @enderror" 
                               value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}" placeholder="e.g., 2024-2025" required>
                        @error('academic_year')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
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
                            <tr>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->code }}</td>
                                <td>{{ $subject->max_marks }}</td>
                                <td>{{ $subject->pass_marks }}</td>
                                <td>
                                    <input type="number" name="marks[{{ $subject->id }}]" 
                                           class="form-control @error('marks.' . $subject->id) is-invalid @enderror" 
                                           min="0" max="{{ $subject->max_marks }}" 
                                           value="{{ old('marks.' . $subject->id) }}" required>
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
                <i class="fas fa-save"></i> Create Marksheet
            </button>
            <a href="{{ route('marksheets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </form>
</div>
@stop