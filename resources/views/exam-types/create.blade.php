@extends('adminlte::page')

@section('title', 'Create Exam Type')

@section('content_header')
    <h1>Create New Exam Type</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Exam Type Details</h3>
    </div>
    <form action="{{ route('exam-types.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Exam Type Name *</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="e.g., Half Yearly, Annual, Unit Test" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Exam Code *</label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" 
                               value="{{ old('code') }}" placeholder="e.g., HY, AN, UT1" required>
                        @error('code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="duration_days">Duration (Days)</label>
                        <input type="number" name="duration_days" id="duration_days" 
                               class="form-control @error('duration_days') is-invalid @enderror" 
                               value="{{ old('duration_days') }}" min="1" placeholder="e.g., 7">
                        <small class="form-text text-muted">Number of days the exam will run</small>
                        @error('duration_days')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="weightage">Weightage (%) *</label>
                        <input type="number" name="weightage" id="weightage" 
                               class="form-control @error('weightage') is-invalid @enderror" 
                               value="{{ old('weightage', 100) }}" min="0" max="100" step="0.01" required>
                        <small class="form-text text-muted">Percentage weightage for final grade calculation</small>
                        @error('weightage')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sort_order">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" 
                               class="form-control @error('sort_order') is-invalid @enderror" 
                               value="{{ old('sort_order', 0) }}" min="0">
                        <small class="form-text text-muted">Lower numbers appear first in lists</small>
                        @error('sort_order')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (available for use)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="3" placeholder="Optional description for this exam type">{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Exam Type
            </button>
            <a href="{{ route('exam-types.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </form>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Common Exam Types</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <h6>Annual Exams</h6>
                <ul class="list-unstyled">
                    <li>Half Yearly (HY) - 50%</li>
                    <li>Annual (AN) - 50%</li>
                    <li>Pre-Board (PB) - 100%</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>Term Exams</h6>
                <ul class="list-unstyled">
                    <li>First Term (T1) - 50%</li>
                    <li>Second Term (T2) - 50%</li>
                    <li>Third Term (T3) - 50%</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>Unit Tests</h6>
                <ul class="list-unstyled">
                    <li>Unit Test 1 (UT1) - 20%</li>
                    <li>Unit Test 2 (UT2) - 20%</li>
                    <li>Monthly Test (MT) - 15%</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop