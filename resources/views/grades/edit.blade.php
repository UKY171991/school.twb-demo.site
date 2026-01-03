@extends('layouts.app')

@section('title', 'Edit Class')

@section('content_header')
    <h1>Edit Class</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Class Information</h3>
        </div>
        <form action="{{ route('grades.update', $grade->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Class Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" placeholder="e.g., Class 1, Class 10" value="{{ old('name', $grade->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="section">Section</label>
                            <input type="text" name="section" class="form-control @error('section') is-invalid @enderror" 
                                   id="section" placeholder="e.g., A, B, C" value="{{ old('section', $grade->section) }}">
                            @error('section')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher_id">Class Teacher</label>
                            <select name="teacher_id" id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror">
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $grade->teacher_id) == $teacher->id ? 'selected' : '' }}>
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
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('grades.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
