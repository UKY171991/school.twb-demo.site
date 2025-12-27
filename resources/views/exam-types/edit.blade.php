@extends('adminlte::page')

@section('title', 'Edit Exam Type')

@section('content_header')
    <h1>Edit Exam Type: {{ $examType->name }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Exam Type Details</h3>
    </div>
    <form action="{{ route('exam-types.update', $examType) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Exam Type Name *</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $examType->name) }}" placeholder="e.g., Half Yearly, Annual, Unit Test" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Exam Code *</label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" 
                               value="{{ old('code', $examType->code) }}" placeholder="e.g., HY, AN, UT1" required>
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
                               value="{{ old('duration_days', $examType->duration_days) }}" min="1" placeholder="e.g., 7">
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
                               value="{{ old('weightage', $examType->weightage) }}" min="0" max="100" step="0.01" required>
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
                               value="{{ old('sort_order', $examType->sort_order) }}" min="0">
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
                                   {{ old('is_active', $examType->is_active) ? 'checked' : '' }}>
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
                          rows="3" placeholder="Optional description for this exam type">{{ old('description', $examType->description) }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Exam Type
            </button>
            <a href="{{ route('exam-types.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </form>
</div>
@stop