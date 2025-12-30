@extends('layouts.app')

@section('title', 'Exam Timetables')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Exam Timetables Management</h1>
        <div>
            <a href="{{ route('exam-timetables.bulk-create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus-circle"></i> Create Timetable
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="filter-section">
        <h5 class="mb-3"><i class="fas fa-filter mr-2"></i>Filter Timetables</h5>
        <form method="GET" class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Exam Type</label>
                    <select name="exam_type_id" class="form-control">
                        <option value="">All Exam Types</option>
                        @foreach($examTypes as $examType)
                            <option value="{{ $examType->id }}" {{ request('exam_type_id') == $examType->id ? 'selected' : '' }}>
                                {{ $examType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Class</label>
                    <select name="class" class="form-control">
                        <option value="">All Classes</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->name }}" {{ request('class') == $grade->name ? 'selected' : '' }}>
                                {{ $grade->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Section</label>
                    <input type="text" name="section" class="form-control" placeholder="Section" value="{{ request('section') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('exam-timetables.index') }}" class="btn btn-outline-secondary ml-2">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <form id="bulkActionForm" method="POST">
        @csrf
        <div class="bulk-actions">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check">
                            <input type="checkbox" id="selectAll" class="form-check-input custom-checkbox">
                            <label class="form-check-label text-white" for="selectAll">
                                Select All
                            </label>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-sm" id="bulkEditBtn" disabled>
                                <i class="fas fa-edit"></i> Bulk Edit Selected
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn" disabled>
                                <i class="fas fa-trash"></i> Bulk Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <span id="selectedCount" class="text-white">0 selected</span>
                </div>
            </div>
        </div>

        <!-- Timetables Grid -->
        <div class="timetable-grid">
            @forelse($timetables as $timetable)
                <div class="timetable-card">
                    <div class="timetable-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="exam-type-badge">{{ $timetable->examType->name }}</span>
                                <h4 class="mt-2 mb-1">{{ $timetable->class }} {{ $timetable->section ?? 'All' }}</h4>
                                <small>{{ $timetable->academic_year }}</small>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="selected_combinations[]" 
                                       value="{{ $timetable->exam_type_id }}|{{ $timetable->class }}|{{ $timetable->section ?? '' }}|{{ $timetable->academic_year }}" 
                                       class="form-check-input custom-checkbox row-checkbox">
                            </div>
                        </div>
                    </div>
                    
                    <div class="timetable-body">
                        <div class="class-info">
                            <span class="label">Class:</span>
                            <span>{{ $timetable->class }}</span>
                        </div>
                        <div class="class-info">
                            <span class="label">Section:</span>
                            <span>{{ $timetable->section ?? 'All' }}</span>
                        </div>
                        <div class="class-info">
                            <span class="label">Academic:</span>
                            <span>{{ $timetable->academic_year }}</span>
                        </div>
                        
                        <div class="mb-3">
                            @if($timetable->has_subjects)
                                <span class="subject-badge success">
                                    <i class="fas fa-book mr-1"></i>
                                    {{ $timetable->subject_count }} Subject{{ $timetable->subject_count > 1 ? 's' : '' }}
                                </span>
                            @else
                                <span class="subject-badge warning">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    No Subjects
                                </span>
                            @endif
                        </div>
                        
                        <div class="action-buttons">
                            @if($timetable->has_subjects)
                                <button type="button" class="btn btn-warning btn-sm edit-class-btn"
                                        data-exam-type="{{ $timetable->exam_type_id }}"
                                        data-class="{{ $timetable->class }}"
                                        data-section="{{ $timetable->section }}"
                                        data-academic-year="{{ $timetable->academic_year }}"
                                        title="Edit Timetable">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-info btn-sm print-class-btn"
                                        data-exam-type="{{ $timetable->exam_type_id }}"
                                        data-class="{{ $timetable->class }}"
                                        data-section="{{ $timetable->section }}"
                                        data-academic-year="{{ $timetable->academic_year }}"
                                        title="Print Timetable">
                                    <i class="fas fa-print"></i> Print
                                </button>
                                <button type="button" class="btn btn-danger btn-sm delete-class-btn"
                                        data-exam-type="{{ $timetable->exam_type_id }}"
                                        data-class="{{ $timetable->class }}"
                                        data-section="{{ $timetable->section }}"
                                        data-academic-year="{{ $timetable->academic_year }}"
                                        title="Delete Class">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            @else
                                <button type="button" class="btn btn-success btn-sm add-subjects-btn" 
                                        data-exam-type="{{ $timetable->exam_type_id }}"
                                        data-class="{{ $timetable->class }}"
                                        data-section="{{ $timetable->section }}"
                                        data-academic-year="{{ $timetable->academic_year }}"
                                        title="Add Subjects">
                                    <i class="fas fa-plus"></i> Add Subjects
                                </button>
                                <button type="button" class="btn btn-danger btn-sm delete-class-btn"
                                        data-exam-type="{{ $timetable->exam_type_id }}"
                                        data-class="{{ $timetable->class }}"
                                        data-section="{{ $timetable->section }}"
                                        data-academic-year="{{ $timetable->academic_year }}"
                                        title="Delete Class">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state col-12">
                    <i class="fas fa-calendar-times"></i>
                    <h4>No Exam Timetables Found</h4>
                    <p>Start by creating your first exam timetable using the button above.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($timetables->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $timetables->links() }}
            </div>
    </form>
</div>
@stop

@section('js')
    @parent
    <script>
    // Select/Deselect all functionality
    $(document).ready(function() {