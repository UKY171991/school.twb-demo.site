@extends('layouts.school-admin')

@section('title', 'Teacher Management')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Teacher Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Teachers</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $statistics['total'] }}</h3>
                            <p>Total Teachers</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $statistics['active'] }}</h3>
                            <p>Active</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $statistics['inactive'] }}</h3>
                            <p>Inactive</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-times"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $statistics['new_this_month'] }}</h3>
                            <p>New This Month</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ round($statistics['avg_experience'], 1) }}</h3>
                            <p>Avg Experience</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-medal"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3 class="text-dark">{{ $statistics['male'] }}/{{ $statistics['female'] }}</h3>
                            <p class="text-dark">Male/Female</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-venus-mars text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-2"></i>
                                Filters & Actions
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="filterForm" class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="searchInput">Search</label>
                                        <input type="text" class="form-control" id="searchInput" name="search" 
                                               placeholder="Name, ID, Email...">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="subjectFilter">Subject</label>
                                        <select class="form-control" id="subjectFilter" name="subject_id">
                                            <option value="">All Subjects</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="classFilter">Class</label>
                                        <select class="form-control" id="classFilter" name="class_id">
                                            <option value="">All Classes</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="statusFilter">Status</label>
                                        <select class="form-control" id="statusFilter" name="status">
                                            <option value="">All Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="btn-group d-block">
                                            <button type="button" class="btn btn-primary" id="applyFilters">
                                                <i class="fas fa-search"></i> Search
                                            </button>
                                            <button type="button" class="btn btn-secondary" id="clearFilters">
                                                <i class="fas fa-times"></i> Clear
                                            </button>
                                            <a href="{{ route('admin.teachers.create') }}" class="btn btn-success">
                                                <i class="fas fa-plus"></i> Add Teacher
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teachers Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>
                                Teachers List
                            </h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn">
                                        <i class="fas fa-check-square"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="bulkActionsBtn" disabled>
                                        <i class="fas fa-cogs"></i> Bulk Actions
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="teachersTable">
                                    <thead>
                                        <tr>
                                            <th width="30">
                                                <input type="checkbox" id="selectAll">
                                            </th>
                                            <th>Photo</th>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Subjects</th>
                                            <th>Classes</th>
                                            <th>Experience</th>
                                            <th>Contact</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="teachersTableBody">
                                        <!-- Data loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="row mt-3">
                                <div class="col-sm-5">
                                    <div class="dataTables_info" id="tableInfo"></div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="tablePagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bulk Actions</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="bulkActionsForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bulkAction">Select Action</label>
                        <select class="form-control" id="bulkAction" name="action" required>
                            <option value="">Choose an action...</option>
                            <option value="activate">Activate Teachers</option>
                            <option value="deactivate">Deactivate Teachers</option>
                            <option value="assign_subject">Assign to Subject</option>
                            <option value="assign_class">Assign to Class</option>
                            <option value="delete" class="text-danger">Delete Teachers</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="subjectSelectionGroup" style="display: none;">
                        <label for="bulkSubjectId">Select Subject</label>
                        <select class="form-control" id="bulkSubjectId" name="subject_id">
                            <option value="">Choose a subject...</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group" id="classSelectionGroup" style="display: none;">
                        <label for="bulkClassId">Select Class</label>
                        <select class="form-control" id="bulkClassId" name="class_id">
                            <option value="">Choose a class...</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span id="selectedCount">0</span> teachers selected for this action.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Execute Action</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.teacher-photo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.status-badge {
    font-size: 0.8rem;
}

.table th {
    vertical-align: middle;
}

.table td {
    vertical-align: middle;
}

.btn-group-actions {
    display: flex;
    gap: 2px;
}

.btn-group-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.subject-badge, .class-badge {
    font-size: 0.75rem;
    margin: 1px;
}

.pagination {
    margin-bottom: 0;
}

.dataTables_info {
    padding-top: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let currentPage = 1;
    let selectedTeachers = [];
    
    // Load teachers data
    loadTeachers();
    
    // Filter form submission
    $('#applyFilters').click(function() {
        currentPage = 1;
        loadTeachers();
    });
    
    // Clear filters
    $('#clearFilters').click(function() {
        $('#filterForm')[0].reset();
        currentPage = 1;
        loadTeachers();
    });
    
    // Search on enter
    $('#searchInput').keypress(function(e) {
        if (e.which === 13) {
            $('#applyFilters').click();
        }
    });
    
    // Select all checkbox
    $('#selectAll').change(function() {
        const isChecked = $(this).is(':checked');
        $('.teacher-checkbox').prop('checked', isChecked);
        updateSelectedTeachers();
    });
    
    // Individual checkbox change
    $(document).on('change', '.teacher-checkbox', function() {
        updateSelectedTeachers();
    });
    
    // Select all button
    $('#selectAllBtn').click(function() {
        $('.teacher-checkbox').prop('checked', true);
        $('#selectAll').prop('checked', true);
        updateSelectedTeachers();
    });
    
    // Bulk actions button
    $('#bulkActionsBtn').click(function() {
        if (selectedTeachers.length === 0) {
            showError('Please select at least one teacher');
            return;
        }
        $('#selectedCount').text(selectedTeachers.length);
        $('#bulkActionsModal').modal('show');
    });
    
    // Bulk action selection change
    $('#bulkAction').change(function() {
        const action = $(this).val();
        
        // Hide all selection groups
        $('#subjectSelectionGroup, #classSelectionGroup').hide();
        $('#bulkSubjectId, #bulkClassId').prop('required', false);
        
        if (action === 'assign_subject') {
            $('#subjectSelectionGroup').show();
            $('#bulkSubjectId').prop('required', true);
        } else if (action === 'assign_class') {
            $('#classSelectionGroup').show();
            $('#bulkClassId').prop('required', true);
        }
    });
    
    // Bulk actions form submission
    $('#bulkActionsForm').submit(function(e) {
        e.preventDefault();
        
        const action = $('#bulkAction').val();
        const subjectId = $('#bulkSubjectId').val();
        const classId = $('#bulkClassId').val();
        
        if (action === 'assign_subject' && !subjectId) {
            showError('Please select a subject');
            return;
        }
        
        if (action === 'assign_class' && !classId) {
            showError('Please select a class');
            return;
        }
        
        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete the selected teachers? This action cannot be undone.')) {
                return;
            }
        }
        
        const data = {
            action: action,
            teacher_ids: selectedTeachers,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        if (subjectId) data.subject_id = subjectId;
        if (classId) data.class_id = classId;
        
        $.ajax({
            url: '{{ route("admin.teachers.bulk-action") }}',
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    $('#bulkActionsModal').modal('hide');
                    showSuccess(response.message);
                    loadTeachers();
                    selectedTeachers = [];
                    updateSelectedTeachers();
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr) {
                showError('Error performing bulk action');
                console.error(xhr);
            }
        });
    });
    
    // Toggle status
    $(document).on('click', '.toggle-status', function() {
        const teacherId = $(this).data('teacher-id');
        
        $.ajax({
            url: `/admin/teachers/${teacherId}/toggle-status`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showSuccess(response.message);
                    loadTeachers();
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr) {
                showError('Error updating teacher status');
            }
        });
    });
    
    // Delete teacher
    $(document).on('click', '.delete-teacher', function() {
        const teacherId = $(this).data('teacher-id');
        const teacherName = $(this).data('teacher-name');
        
        if (confirm(`Are you sure you want to delete ${teacherName}? This action cannot be undone.`)) {
            $.ajax({
                url: `/admin/teachers/${teacherId}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showSuccess(response.message);
                        loadTeachers();
                    } else {
                        showError(response.message);
                    }
                },
                error: function(xhr) {
                    showError('Error deleting teacher');
                }
            });
        }
    });
    
    function loadTeachers() {
        const formData = $('#filterForm').serialize() + `&page=${currentPage}`;
        
        $.ajax({
            url: '{{ route("admin.teachers.data") }}',
            method: 'GET',
            data: formData,
            beforeSend: function() {
                $('#teachersTableBody').html('<tr><td colspan="10" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
            },
            success: function(response) {
                if (response.success) {
                    renderTeachersTable(response.data.data);
                    renderPagination(response.data.pagination);
                } else {
                    showError('Error loading teachers data');
                }
            },
            error: function(xhr) {
                showError('Error loading teachers data');
                console.error(xhr);
            }
        });
    }
    
    function renderTeachersTable(teachers) {
        let html = '';
        
        if (teachers.length === 0) {
            html = '<tr><td colspan="10" class="text-center">No teachers found</td></tr>';
        } else {
            teachers.forEach(teacher => {
                const photoUrl = teacher.photo_url || '{{ asset("vendor/adminlte/dist/img/user2-160x160.jpg") }}';
                const subjects = teacher.subjects.map(s => `<span class="badge badge-primary subject-badge">${s.name}</span>`).join(' ');
                const classes = teacher.classes.map(c => `<span class="badge badge-info class-badge">${c.name}</span>`).join(' ');
                
                html += `
                    <tr>
                        <td>
                            <input type="checkbox" class="teacher-checkbox" value="${teacher.id}">
                        </td>
                        <td>
                            <img src="${photoUrl}" alt="Photo" class="teacher-photo">
                        </td>
                        <td><strong>${teacher.employee_id}</strong></td>
                        <td>
                            <a href="/admin/teachers/${teacher.id}" class="text-decoration-none">
                                ${teacher.full_name}
                            </a>
                            ${teacher.email ? `<br><small class="text-muted">${teacher.email}</small>` : ''}
                        </td>
                        <td>${subjects || '<span class="text-muted">None</span>'}</td>
                        <td>${classes || '<span class="text-muted">None</span>'}</td>
                        <td>
                            <span class="badge badge-secondary">${teacher.experience} years</span>
                        </td>
                        <td>
                            ${teacher.phone ? `<i class="fas fa-phone"></i> ${teacher.phone}` : 'N/A'}
                        </td>
                        <td>
                            <span class="badge badge-${teacher.is_active ? 'success' : 'secondary'} status-badge">
                                ${teacher.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group-actions">
                                <a href="/admin/teachers/${teacher.id}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/admin/teachers/${teacher.id}/edit" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-${teacher.is_active ? 'secondary' : 'success'} toggle-status" 
                                        data-teacher-id="${teacher.id}" title="Toggle Status">
                                    <i class="fas fa-${teacher.is_active ? 'pause' : 'play'}"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-teacher" 
                                        data-teacher-id="${teacher.id}" data-teacher-name="${teacher.full_name}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }
        
        $('#teachersTableBody').html(html);
    }
    
    function renderPagination(pagination) {
        const info = `Showing ${((pagination.current_page - 1) * pagination.per_page) + 1} to ${Math.min(pagination.current_page * pagination.per_page, pagination.total)} of ${pagination.total} entries`;
        $('#tableInfo').text(info);
        
        let paginationHtml = '<ul class="pagination pagination-sm m-0 float-right">';
        
        // Previous button
        if (pagination.current_page > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">Previous</a></li>`;
        }
        
        // Page numbers
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === pagination.current_page ? 'active' : '';
            paginationHtml += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        }
        
        // Next button
        if (pagination.current_page < pagination.last_page) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">Next</a></li>`;
        }
        
        paginationHtml += '</ul>';
        $('#tablePagination').html(paginationHtml);
        
        // Pagination click handler
        $('.page-link').click(function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page) {
                currentPage = page;
                loadTeachers();
            }
        });
    }
    
    function updateSelectedTeachers() {
        selectedTeachers = [];
        $('.teacher-checkbox:checked').each(function() {
            selectedTeachers.push($(this).val());
        });
        
        $('#bulkActionsBtn').prop('disabled', selectedTeachers.length === 0);
        
        // Update select all checkbox
        const totalCheckboxes = $('.teacher-checkbox').length;
        const checkedCheckboxes = $('.teacher-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
    }
    
    function showSuccess(message) {
        toastr.success(message);
    }
    
    function showError(message) {
        toastr.error(message);
    }
});
</script>
@endpush