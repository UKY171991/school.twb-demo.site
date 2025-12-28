@extends('adminlte::page')

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

@section('css')
<style>
.main-content {
    padding: 1rem;
}

.timetable-card {
    transition: all 0.3s ease;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    overflow: hidden;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.timetable-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.timetable-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem;
}

.timetable-body {
    padding: 1.5rem;
}

.subject-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.subject-badge.success {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    color: #2d3748;
}

.subject-badge.warning {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #2d3748;
}

.exam-type-badge {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    color: #2d3748;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-buttons .btn {
    border-radius: 6px;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.action-buttons .btn:hover {
    transform: scale(1.05);
}

.filter-section {
    background: #f8f9fc;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #e3e6f0;
}

.bulk-actions {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.timetable-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.class-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.class-info .label {
    font-weight: 600;
    color: #4a5568;
    min-width: 80px;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #718096;
    grid-column: 1 / -1;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 1rem;
}

.checkbox-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 40px;
}

.custom-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

@media (max-width: 768px) {
    .timetable-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .filter-section .row {
        gap: 0.5rem;
    }
    
    .bulk-actions .row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .bulk-actions .col-md-8,
    .bulk-actions .col-md-4 {
        flex: 1;
        text-align: center !important;
    }
}
</style>
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
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Exam Type</label>
                <select name="exam_type_id" class="form-select">
                    <option value="">All Exam Types</option>
                    @foreach($examTypes as $examType)
                        <option value="{{ $examType->id }}" {{ request('exam_type_id') == $examType->id ? 'selected' : '' }}>
                            {{ $examType->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Class</label>
                <select name="class" class="form-select">
                    <option value="">All Classes</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->name }}" {{ request('class') == $grade->name ? 'selected' : '' }}>
                            {{ $grade->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Section</label>
                <input type="text" name="section" class="form-control" placeholder="Section" value="{{ request('section') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <a href="{{ route('exam-timetables.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
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
                                       value="{{ $timetable->exam_type_id }}-{{ $timetable->class }}-{{ $timetable->section ?? '' }}-{{ $timetable->academic_year }}" 
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
        @endif
    </form>
</div>
@stop

@section('js')
<script>
// Bulk operations functionality
$(document).ready(function() {
    // Select/Deselect all functionality
    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateBulkButtons();
        updateSelectedCount();
    });

    // Update bulk buttons when individual checkboxes change
    $('.row-checkbox').on('change', function() {
        updateSelectAllCheckbox();
        updateBulkButtons();
        updateSelectedCount();
    });

    // Bulk edit functionality
    $('#bulkEditBtn').on('click', function() {
        const selectedCombinations = getSelectedCombinations();
        if (selectedCombinations.length === 0) {
            alert('Please select at least one timetable entry to edit.');
            return;
        }

        const form = $('<form>', {
            method: 'POST',
            action: '{{ route("exam-timetables.bulk-edit") }}'
        });

        form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
        }));

        selectedCombinations.forEach(function(combination) {
            form.append($('<input>', {
                type: 'hidden',
                name: 'class_combinations[]',
                value: combination
            }));
        });

        $('body').append(form);
        form.submit();
    });

    // Bulk delete functionality
    $('#bulkDeleteBtn').on('click', function() {
        const selectedCombinations = getSelectedCombinations();
        if (selectedCombinations.length === 0) {
            alert('Please select at least one timetable entry to delete.');
            return;
        }

        if (!confirm(`Are you sure you want to delete ${selectedCombinations.length} selected timetable class groups? This will delete ALL timetable entries for these classes. This action cannot be undone.`)) {
            return;
        }

        const form = $('<form>', {
            method: 'POST',
            action: '{{ route("exam-timetables.bulk-delete") }}'
        });

        form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
        }));

        form.append($('<input>', {
            type: 'hidden',
            name: '_method',
            value: 'DELETE'
        }));

        selectedCombinations.forEach(function(combination) {
            form.append($('<input>', {
                type: 'hidden',
                name: 'class_combinations[]',
                value: combination
            }));
        });

        $('body').append(form);
        form.submit();
    });

    function getSelectedCombinations() {
        const selectedCombinations = [];
        $('.row-checkbox:checked').each(function() {
            selectedCombinations.push($(this).val());
        });
        return selectedCombinations;
    }

    function updateBulkButtons() {
        const selectedCount = $('.row-checkbox:checked').length;
        $('#bulkEditBtn, #bulkDeleteBtn').prop('disabled', selectedCount === 0);
        
        if (selectedCount > 0) {
            $('#bulkEditBtn').html(`<i class="fas fa-edit"></i> Bulk Edit Selected (${selectedCount})`);
            $('#bulkDeleteBtn').html(`<i class="fas fa-trash"></i> Bulk Delete Selected (${selectedCount})`);
        } else {
            $('#bulkEditBtn').html('<i class="fas fa-edit"></i> Bulk Edit Selected');
            $('#bulkDeleteBtn').html('<i class="fas fa-trash"></i> Bulk Delete Selected');
        }
    }

    function updateSelectedCount() {
        const selectedCount = $('.row-checkbox:checked').length;
        $('#selectedCount').text(`${selectedCount} selected`);
    }

    function updateSelectAllCheckbox() {
        const totalCheckboxes = $('.row-checkbox').length;
        const checkedCheckboxes = $('.row-checkbox:checked').length;
        
        if (checkedCheckboxes === 0) {
            $('#selectAll').prop('indeterminate', false).prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#selectAll').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#selectAll').prop('indeterminate', true);
        }
    }

    // Individual action buttons
    $('.add-subjects-btn').on('click', function() {
        const examType = $(this).data('exam-type');
        const className = $(this).data('class');
        const section = $(this).data('section');
        const academicYear = $(this).data('academic-year');
        
        // Redirect to bulk create with pre-filled data
        const url = '{{ route("exam-timetables.bulk-create") }}' + 
                   `?exam_type_id=${examType}&class=${className}&section=${section}&academic_year=${academicYear}`;
        window.location.href = url;
    });

    $('.delete-class-btn').on('click', function() {
        const examType = $(this).data('exam-type');
        const className = $(this).data('class');
        const section = $(this).data('section') || '';
        const academicYear = $(this).data('academic-year');
        
        const sectionText = section ? section : 'All';
        
        if (!confirm(`Are you sure you want to delete all timetable entries for Class ${className} Section ${sectionText}? This action cannot be undone.`)) {
            return;
        }
        
        const combination = `${examType}-${className}-${section}-${academicYear}`;
        
        const form = $('<form>', {
            method: 'POST',
            action: '{{ route("exam-timetables.bulk-delete") }}'
        });

        form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
        }));

        form.append($('<input>', {
            type: 'hidden',
            name: '_method',
            value: 'DELETE'
        }));

        form.append($('<input>', {
            type: 'hidden',
            name: 'class_combinations[]',
            value: combination
        }));

        $('body').append(form);
        form.submit();
    });

    $('.print-class-btn').on('click', function() {
        const examType = $(this).data('exam-type');
        const className = $(this).data('class');
        const section = $(this).data('section') || '';
        const academicYear = $(this).data('academic-year');
        
        // Build the print URL with parameters
        const printUrl = '{{ route("exam-timetables.print") }}' + 
                        `?exam_type_id=${examType}&class=${className}&section=${section}&academic_year=${academicYear}`;
        
        // Open in new window for printing
        window.open(printUrl, '_blank');
    });

    // Initialize selected count
    updateSelectedCount();
});
</script>
@stop