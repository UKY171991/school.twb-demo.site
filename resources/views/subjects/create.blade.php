@extends('adminlte::page')

@section('title', 'Add Subject')

@section('content_header')
    <h1>Add New Subject</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Subject Information</h3>
        </div>
        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" placeholder="e.g., Mathematics, Science" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">Subject Code</label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" placeholder="e.g., MATH101" value="{{ old('code') }}">
                            @error('code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="grade_id">Class <span class="text-danger">*</span></label>
                            <select name="grade_id" id="grade_id" class="form-control @error('grade_id') is-invalid @enderror" required>
                                <option value="">Select Class</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }} @if($grade->section) - {{ $grade->section }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('grade_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacher_id">Assign Teacher</label>
                            <select name="teacher_id" id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror">
                                <option value="">Select Teacher (Optional)</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Enter subject description">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="max_marks">Maximum Marks</label>
                            <input type="number" name="max_marks" class="form-control @error('max_marks') is-invalid @enderror" 
                                   id="max_marks" placeholder="e.g., 100" value="{{ old('max_marks') }}" min="1">
                            @error('max_marks')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pass_marks">Pass Marks</label>
                            <input type="number" name="pass_marks" class="form-control @error('pass_marks') is-invalid @enderror" 
                                   id="pass_marks" placeholder="e.g., 40" value="{{ old('pass_marks') }}" min="1">
                            @error('pass_marks')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('subjects.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
