@extends('adminlte::page')

@section('title', 'Add Grade/Class')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('css/grades.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@stop

@section('content_header')
    <div class="grades-header">
        <h1><i class="fas fa-plus-circle"></i> Add New Grade/Class</h1>
        <p class="subtitle">Create a new academic grade or class section</p>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card grade-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-edit"></i> Grade Information</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <form action="{{ route('grades.store') }}" method="POST" id="gradeForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-graduation-cap"></i> Grade Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" placeholder="e.g., Grade 1, Class 10, Nursery" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Enter the grade name as it will appear in reports</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="section" class="form-label">
                                        <i class="fas fa-th-large"></i> Section
                                    </label>
                                    <input type="text" name="section" class="form-control @error('section') is-invalid @enderror" 
                                           id="section" placeholder="e.g., A, B, C" value="{{ old('section') }}" maxlength="1">
                                    @error('section')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Single letter section identifier (optional)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="capacity" class="form-label">
                                        <i class="fas fa-users"></i> Maximum Capacity
                                    </label>
                                    <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" placeholder="e.g., 40" value="{{ old('capacity') ?? 40 }}" min="1" max="100">
                                    @error('capacity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Maximum number of students allowed</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_number" class="form-label">
                                        <i class="fas fa-door-open"></i> Room Number
                                    </label>
                                    <input type="text" name="room_number" class="form-control @error('room_number') is-invalid @enderror" 
                                           id="room_number" placeholder="e.g., 101, A-205" value="{{ old('room_number') }}">
                                    @error('room_number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Classroom or room identifier</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left"></i> Description
                                    </label>
                                    <textarea name="description" id="description" rows="3" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              placeholder="Enter grade description, curriculum details, or special notes...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Optional description for internal reference</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-palette"></i> Grade Color Theme
                                    </label>
                                    <div class="grade-color-selector">
                                        @for($i = 1; $i <= 12; $i++)
                                            <div class="color-option grade-{{ $i }}" data-grade="{{ $i }}" title="Grade {{ $i }}">
                                                {{ $i }}
                                            </div>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="grade_theme" id="gradeTheme" value="1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on"></i> Status
                                    </label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Set the initial status of this grade</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-add-grade" id="submitBtn">
                            <i class="fas fa-save"></i> Create Grade
                        </button>
                        <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="reset" class="btn btn-warning">
                            <i class="fas fa-redo"></i> Reset Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Quick Tips Card -->
            <div class="card grade-card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-lightbulb"></i> Quick Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Use clear, consistent naming (e.g., "Grade 1", "Class 10A")
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Sections help organize multiple classes of the same grade
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Set realistic capacity limits for effective classroom management
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Room numbers help with scheduling and logistics
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success"></i>
                            Choose a color theme for easy visual identification
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Recent Grades Card -->
            <div class="card grade-card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-history"></i> Recent Grades</h5>
                </div>
                <div class="card-body">
                    <div class="recent-grades">
                        <div class="recent-grade-item">
                            <div class="grade-badge grade-1">G1</div>
                            <div class="grade-info">
                                <strong>Grade 1</strong>
                                <small class="text-muted">Section A • 25 students</small>
                            </div>
                        </div>
                        <div class="recent-grade-item">
                            <div class="grade-badge grade-2">G2</div>
                            <div class="grade-info">
                                <strong>Grade 2</strong>
                                <small class="text-muted">Section B • 30 students</small>
                            </div>
                        </div>
                        <div class="recent-grade-item">
                            <div class="grade-badge grade-3">G3</div>
                            <div class="grade-info">
                                <strong>Grade 3</strong>
                                <small class="text-muted">No section • 28 students</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/grades.js') }}"></script>
@stop
