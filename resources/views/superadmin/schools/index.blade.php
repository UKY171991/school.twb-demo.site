@extends('layouts.superadmin')

@section('title', 'Schools Management')

@section('content-header')
    @include('layouts.partials.content-header', [
        'title' => 'Schools Management',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'url' => route('superadmin.dashboard')],
            ['text' => 'Schools', 'active' => true]
        ]
    ])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Schools</h3>
                <div class="card-tools">
                    <a href="{{ route('superadmin.schools.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New School
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select id="statusFilter" class="form-control">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <button type="button" id="refreshTable" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Schools DataTable -->
                <div class="table-responsive">
                    <table id="schoolsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Email</th>
                                <th>Principal</th>
                                <th>Students</th>
                                <th>Teachers</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this school? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Toggle Confirmation Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="statusMessage">Are you sure you want to change the status of this school?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusChange">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#schoolsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("superadmin.schools.index") }}',
            data: function(d) {
                d.status = $('#statusFilter').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'code', name: 'code' },
            { data: 'email', name: 'email' },
            { data: 'principal_name', name: 'principal_name' },
            { data: 'students_count', name: 'students_count', orderable: false, searchable: false },
            { data: 'teachers_count', name: 'teachers_count', orderable: false, searchable: false },
            { data: 'status_badge', name: 'is_active', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });

    // Status filter change
    $('#statusFilter').on('change', function() {
        table.draw();
    });

    // Refresh table
    $('#refreshTable').on('click', function() {
        table.ajax.reload();
    });

    // Delete functionality
    let deleteUrl = '';
    $(document).on('click', '.delete-btn', function() {
        deleteUrl = $(this).data('url');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    table.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                $('#deleteModal').modal('hide');
                const response = xhr.responseJSON;
                toastr.error(response?.message || 'An error occurred');
            }
        });
    });

    // Status toggle functionality
    let statusUrl = '';
    $(document).on('click', '.toggle-status-btn', function() {
        statusUrl = $(this).data('url');
        const isActive = $(this).hasClass('btn-warning');
        const action = isActive ? 'deactivate' : 'activate';
        $('#statusMessage').text(`Are you sure you want to ${action} this school?`);
        $('#statusModal').modal('show');
    });

    $('#confirmStatusChange').on('click', function() {
        $.ajax({
            url: statusUrl,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#statusModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    table.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                $('#statusModal').modal('hide');
                const response = xhr.responseJSON;
                toastr.error(response?.message || 'An error occurred');
            }
        });
    });
});
</script>
@endpush