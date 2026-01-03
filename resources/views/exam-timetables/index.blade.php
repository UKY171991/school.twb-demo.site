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
                        
                        <div class="action-buttons d-flex gap-2 flex-wrap">
                            @if($timetable->has_subjects)
                                <a href="{{ route('exam-timetables.edit-group', ['exam_type' => $timetable->exam_type_id, 'class' => $timetable->class, 'section' => $timetable->section ?? '', 'academic_year' => $timetable->academic_year]) }}" 
                                   class="btn btn-warning btn-sm" title="Edit Timetable">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('exam-timetables.print', ['exam_type' => $timetable->exam_type_id, 'class' => $timetable->class, 'section' => $timetable->section ?? '', 'academic_year' => $timetable->academic_year]) }}" 
                                   class="btn btn-info btn-sm" target="_blank" title="Print Timetable">
                                    <i class="fas fa-print"></i> Print
                                </a>
                                <button type="button" class="btn btn-danger btn-sm delete-class-btn"
                                        data-exam-type="{{ $timetable->exam_type_id }}"
                                        data-class="{{ $timetable->class }}"
                                        data-section="{{ $timetable->section ?? '' }}"
                                        data-academic-year="{{ $timetable->academic_year }}"
                                        title="Delete Class">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            @else
                                <a href="{{ route('exam-timetables.add-subjects', ['exam_type' => $timetable->exam_type_id, 'class' => $timetable->class, 'section' => $timetable->section ?? '', 'academic_year' => $timetable->academic_year]) }}" 
                                   class="btn btn-success btn-sm" title="Add Subjects">
                                    <i class="fas fa-plus"></i> Add Subjects
                                </a>
                                <button type="button" class="btn btn-danger btn-sm delete-class-btn"
                                        data-exam-type="{{ $timetable->exam_type_id }}"
                                        data-class="{{ $timetable->class }}"
                                        data-section="{{ $timetable->section ?? '' }}"
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
        @endif
    </form>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Select/Deselect all functionality
    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', $(this).prop('checked')).trigger('change');
    });

    // Update selected count and button states
    $(document).on('change', '.row-checkbox', function() {
        updateSelectionState();
    });

    function updateSelectionState() {
        var selectedCount = $('.row-checkbox:checked').length;
        var totalCount = $('.row-checkbox').length;
        
        $('#selectedCount').text(selectedCount + ' selected');
        $('#bulkEditBtn, #bulkDeleteBtn').prop('disabled', selectedCount === 0);
        
        $('#selectAll').prop('checked', selectedCount === totalCount && totalCount > 0);
        $('#selectAll').prop('indeterminate', selectedCount > 0 && selectedCount < totalCount);
    }

    // Bulk Edit action
    $('#bulkEditBtn').on('click', function(e) {
        e.preventDefault();
        var selectedData = [];
        $('.row-checkbox:checked').each(function() {
            selectedData.push($(this).val());
        });
        
        if (selectedData.length > 0) {
            window.location.href = '{{ route("exam-timetables.bulk-edit") }}?selected=' + encodeURIComponent(selectedData.join(','));
        }
    });

    // Bulk Delete action
    $('#bulkDeleteBtn').on('click', function(e) {
        e.preventDefault();
        var selectedCount = $('.row-checkbox:checked').length;
        if (confirm('Are you sure you want to delete ' + selectedCount + ' selected timetable(s)? This action cannot be undone.')) {
            $('#bulkActionForm').attr('action', '{{ route("exam-timetables.bulk-delete") }}').submit();
        }
    });

    // Delete single timetable
    $(document).on('click', '.delete-class-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $btn = $(this);
        var examType = $btn.attr('data-exam-type');
        var className = $btn.attr('data-class');
        var section = $btn.attr('data-section') || '';
        var academicYear = $btn.attr('data-academic-year');
        
        if (confirm('Are you sure you want to delete this timetable for ' + className + ' ' + (section || 'All') + '? This action cannot be undone.')) {
            var $form = $('<form>', {
                method: 'POST',
                action: '{{ route("exam-timetables.delete-group") }}'
            });
            
            $form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
            $form.append($('<input>', { type: 'hidden', name: '_method', value: 'DELETE' }));
            $form.append($('<input>', { type: 'hidden', name: 'exam_type', value: examType }));
            $form.append($('<input>', { type: 'hidden', name: 'class', value: className }));
            $form.append($('<input>', { type: 'hidden', name: 'section', value: section }));
            $form.append($('<input>', { type: 'hidden', name: 'academic_year', value: academicYear }));
            
            $form.appendTo('body').submit();
        }
    });

    // Initialize selection state on page load
    updateSelectionState();
});
</script>
@endsection