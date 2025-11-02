@extends('layouts.school-admin')

@section('title', 'Student Management')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Student Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Students</li>
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
                            <p>Total Students</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
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
                            <h3>{{ $statistics['graduated'] }}</h3>
                            <p>Graduated</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-secondary">
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
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="graduated">Graduated</option>
                                            <option value="transferred">Transferred</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="genderFilter">Gender</label>
                                        <select class="form-control" id="genderFilter" name="gender">
                                            <option value="">All Genders</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
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
                                            <a href="{{ route('admin.students.create') }}" class="btn btn-success">
                                                <i class="fas fa-plus"></i> Add Student
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users mr-2"></i>
                                Students List
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
                                <table class="table table-bordered table-striped" id="studentsTable">
                                    <thead>
                                        <tr>
                                            <th width="30">
                                                <input type="checkbox" id="selectAll">
                                            </th>
                                            <th>Photo</th>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th>Gender</th>
                                            <th>Contact</th>
                                            <th>Parent</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentsTableBody">
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
                            <option value="activate">Activate Students</option>
                            <option value="deactivate">Deactivate Students</option>
                            <option value="transfer_class">Transfer to Class</option>
                            <option value="delete" class="text-danger">Delete Students</option>
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
                        <span id="selectedCount">0</span> students selected for this action.
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
.student-photo {
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
    let selectedStudents = [];
    
    // Load students data
    loadStudents();
    
    // Filter form submission
    $('#applyFilters').click(function() {
        currentPage = 1;
        loadStudents();
    });
    
    // Clear filters
    $('#clearFilters').click(function() {
        $('#filterForm')[0].reset();
        currentPage = 1;
        loadStudents();
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
        $('.student-checkbox').prop('checked', isChecked);
        updateSelectedStudents();
    });
    
    // Individual checkbox change
    $(document).on('change', '.student-checkbox', function() {
        updateSelectedStudents();
    });
    
    // Select all button
    $('#selectAllBtn').click(function() {
        $('.student-checkbox').prop('checked', true);
        $('#selectAll').prop('checked', true);
        updateSelectedStudents();
    });
    
    // Bulk actions button
    $('#bulkActionsBtn').click(function() {
        if (selectedStudents.length === 0) {
            showError('Please select at least one student');
            return;
        }
        $('#selectedCount').text(selectedStudents.length);
        $('#bulkActionsModal').modal('show');
    });
    
    // Bulk action selection change
    $('#bulkAction').change(function() {
        const action = $(this).val();
        if (action === 'transfer_class') {
            $('#classSelectionGroup').show();
            $('#bulkClassId').prop('required', true);
        } else {
            $('#classSelectionGroup').hide();
            $('#bulkClassId').prop('required', false);
        }
    });
    
    // Bulk actions form submission
    $('#bulkActionsForm').submit(function(e) {
        e.preventDefault();
        
        const action = $('#bulkAction').val();
        const classId = $('#bulkClassId').val();
        
        if (action === 'transfer_class' && !classId) {
            showError('Please select a class');
            return;
        }
        
        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete the selected students? This action cannot be undone.')) {
                return;
            }
        }
        
        const data = {
            action: action,
            student_ids: selectedStudents,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        if (classId) {
            data.class_id = classId;
        }
        
        $.ajax({
            url: '{{ route("admin.students.bulk-action") }}',
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    $('#bulkActionsModal').modal('hide');
                    showSuccess(response.message);
                    loadStudents();
                    selectedStudents = [];
                    updateSelectedStudents();
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
        const studentId = $(this).data('student-id');
        const currentStatus = $(this).data('current-status');
        
        $.ajax({
            url: `/admin/students/${studentId}/toggle-status`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showSuccess(response.message);
                    loadStudents();
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr) {
                showError('Error updating student status');
            }
        });
    });
    
    // Delete student
    $(document).on('click', '.delete-student', function() {
        const studentId = $(this).data('student-id');
        const studentName = $(this).data('student-name');
        
        if (confirm(`Are you sure you want to delete ${studentName}? This action cannot be undone.`)) {
            $.ajax({
                url: `/admin/students/${studentId}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showSuccess(response.message);
                        loadStudents();
                    } else {
                        showError(response.message);
                    }
                },
                error: function(xhr) {
                    showError('Error deleting student');
                }
            });
        }
    });
    
    function loadStudents() {
        const formData = $('#filterForm').serialize() + `&page=${currentPage}`;
        
        $.ajax({
            url: '{{ route("admin.students.data") }}',
            method: 'GET',
            data: formData,
            beforeSend: function() {
                $('#studentsTableBody').html('<tr><td colspan="10" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
            },
            success: function(response) {
                if (response.success) {
                    renderStudentsTable(response.data.data);
                    renderPagination(response.data.pagination);
                } else {
                    showError('Error loading students data');
                }
            },
            error: function(xhr) {
                showError('Error loading students data');
                console.error(xhr);
            }
        });
    }
    
    function renderStudentsTable(students) {
        let html = '';
        
        if (students.length === 0) {
            html = '<tr><td colspan="10" class="text-center">No students found</td></tr>';
        } else {
            students.forEach(student => {
                const statusClass = getStatusClass(student.status);
                const photoUrl = student.photo_url || '{{ asset("vendor/adminlte/dist/img/user2-160x160.jpg") }}';
                
                html += `
                    <tr>
                        <td>
                            <input type="checkbox" class="student-checkbox" value="${student.id}">
                        </td>
                        <td>
                            <img src="${photoUrl}" alt="Photo" class="student-photo">
                        </td>
                        <td><strong>${student.student_id}</strong></td>
                        <td>
                            <a href="/admin/students/${student.id}" class="text-decoration-none">
                                ${student.full_name}
                            </a>
                            ${student.email ? `<br><small class="text-muted">${student.email}</small>` : ''}
                        </td>
                        <td>${student.class_model ? student.class_model.name : 'Not Assigned'}</td>
                        <td>
                            <span class="badge badge-${student.gender === 'male' ? 'primary' : (student.gender === 'female' ? 'pink' : 'secondary')}">
                                ${student.gender.charAt(0).toUpperCase() + student.gender.slice(1)}
                            </span>
                        </td>
                        <td>
                            ${student.phone ? `<i class="fas fa-phone"></i> ${student.phone}` : 'N/A'}
                        </td>
                        <td>
                            ${student.parent && student.parent.user ? student.parent.user.name : 'Not Assigned'}
                        </td>
                        <td>
                            <span class="badge badge-${statusClass} status-badge">${student.status.charAt(0).toUpperCase() + student.status.slice(1)}</span>
                        </td>
                        <td>
                            <div class="btn-group-actions">
                                <a href="/admin/students/${student.id}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/admin/students/${student.id}/edit" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-${student.status === 'active' ? 'secondary' : 'success'} toggle-status" 
                                        data-student-id="${student.id}" data-current-status="${student.status}" title="Toggle Status">
                                    <i class="fas fa-${student.status === 'active' ? 'pause' : 'play'}"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-student" 
                                        data-student-id="${student.id}" data-student-name="${student.full_name}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }
        
        $('#studentsTableBody').html(html);
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
                loadStudents();
            }
        });
    }
    
    function updateSelectedStudents() {
        selectedStudents = [];
        $('.student-checkbox:checked').each(function() {
            selectedStudents.push($(this).val());
        });
        
        $('#bulkActionsBtn').prop('disabled', selectedStudents.length === 0);
        
        // Update select all checkbox
        const totalCheckboxes = $('.student-checkbox').length;
        const checkedCheckboxes = $('.student-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
    }
    
    function getStatusClass(status) {
        switch (status) {
            case 'active': return 'success';
            case 'inactive': return 'secondary';
            case 'graduated': return 'primary';
            case 'transferred': return 'warning';
            default: return 'light';
        }
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