@extends('adminlte::page')

@section('title', 'Create Grading System')

@section('content_header')
    <h1>Create New Grade</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Grade Details</h3>
    </div>
    <form action="{{ route('grading-systems.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="grade">Grade *</label>
                        <input type="text" name="grade" id="grade" class="form-control @error('grade') is-invalid @enderror" 
                               value="{{ old('grade') }}" placeholder="e.g., A+, B, C" required>
                        @error('grade')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Grade Name *</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="e.g., Excellent, Good, Average" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="min_percentage">Minimum Percentage *</label>
                        <input type="number" name="min_percentage" id="min_percentage" 
                               class="form-control @error('min_percentage') is-invalid @enderror" 
                               value="{{ old('min_percentage') }}" min="0" max="100" step="0.01" required>
                        @error('min_percentage')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="max_percentage">Maximum Percentage *</label>
                        <input type="number" name="max_percentage" id="max_percentage" 
                               class="form-control @error('max_percentage') is-invalid @enderror" 
                               value="{{ old('max_percentage') }}" min="0" max="100" step="0.01" required>
                        @error('max_percentage')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="grade_points">Grade Points</label>
                        <input type="number" name="grade_points" id="grade_points" 
                               class="form-control @error('grade_points') is-invalid @enderror" 
                               value="{{ old('grade_points') }}" min="0" max="10" step="0.01">
                        <small class="form-text text-muted">Optional: Used for GPA calculations</small>
                        @error('grade_points')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sort_order">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" 
                               class="form-control @error('sort_order') is-invalid @enderror" 
                               value="{{ old('sort_order', 0) }}" min="0">
                        <small class="form-text text-muted">Lower numbers appear first</small>
                        @error('sort_order')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="3" placeholder="Optional description for this grade">{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_passing" name="is_passing" value="1" 
                               {{ old('is_passing', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_passing">
                            This is a passing grade
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active (use in grading)
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Grade
            </button>
            <a href="{{ route('grading-systems.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </form>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Common Grade Templates</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <h6>Standard Letter Grades</h6>
                <ul class="list-unstyled">
                    <li>A+ (90-100%)</li>
                    <li>A (80-89%)</li>
                    <li>B+ (70-79%)</li>
                    <li>B (60-69%)</li>
                    <li>C+ (50-59%)</li>
                    <li>C (40-49%)</li>
                    <li>D (33-39%)</li>
                    <li>F (0-32%)</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>Descriptive Grades</h6>
                <ul class="list-unstyled">
                    <li>Excellent (90-100%)</li>
                    <li>Very Good (80-89%)</li>
                    <li>Good (70-79%)</li>
                    <li>Satisfactory (60-69%)</li>
                    <li>Needs Improvement (40-59%)</li>
                    <li>Unsatisfactory (0-39%)</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>Numeric Grades</h6>
                <ul class="list-unstyled">
                    <li>5 (90-100%)</li>
                    <li>4 (80-89%)</li>
                    <li>3 (70-79%)</li>
                    <li>2 (60-69%)</li>
                    <li>1 (0-59%)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop