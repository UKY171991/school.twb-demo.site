@extends('layouts.superadmin')

@section('title', 'Users Management')

@section('content-header')
    @include('layouts.partials.content-header', [
        'title' => 'Users Management',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'url' => route('superadmin.dashboard')],
            ['text' => 'Users', 'active' => true]
        ]
    ])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Users</h3>
                <div class="card-tools">
                    <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New User
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" id="bulkActionsBtn" disabled>
                        <i class="fas fa-cogs"></i> Bulk Actions
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select id="schoolFilter" class="form-control">
                            <option value="">All Schools</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="userTypeFilter" class="form-control">
                            <option value="">All Types</option>
                            @foreach($userTypes as $type)
                                <option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="statusFilter" class="form-control">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button type="button" id="refreshTable" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button type="button" id="clearFilters" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Users DataTable -->
                <div class="table-responsive">
                    <table id="usersTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>School</th>
                                <th>Roles</th>
                                <th>Status</th>
                                <th>Last Login</th>
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

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Select Action:</label>
                    <select id="bulkAction" class="form-control">
                        <option value="">Choose action...</option>
                        <option value="assign_roles">Assign Roles</option>
                        <option value="activate">Activate Users</option>
                        <option value="deactivate">Deactivate Users</option>
                    </select>
                </div>
                <div id="rolesSelection" class="form-group" style="display: none;">
                    <label>Select Roles:</label>
                    <div class="row">
                        @foreach(\Spatie\Permission\Models\Role::all() as $role)
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bulk_roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}">
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="alert alert-info">
                    <span id="selectedCount">0</span> users selected
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="executeBulkAction">Execute</button>
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
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
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
                <p id="statusMessage">Are you sure you want to change the status of this user?</p>
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
    const table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("superadmin.users.index") }}',
            data: function(d) {
                d.school_id = $('#schoolFilter').val();
                d.user_type = $('#userTypeFilter').val();
                d.status = $('#statusFilter').val();
            }
        },
        columns: [
            { 
                data: 'id', 
                name: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `<input type="checkbox" class="user-checkbox" value="${data}">`;
                }
            },
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'user_type_badge', name: 'user_type', orderable: false, searchable: false },
            { data: 'school_name', name: 'school_id' },
            { data: 'roles', name: 'roles', orderable: false, searchable: false },
            { data: 'status_badge', name: 'is_active', orderable: false, searchable: false },
            { data: 'last_login', name: 'last_login_at' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });

    // Filter changes
    $('#schoolFilter, #userTypeFilter, #statusFilter').on('change', function() {
        table.draw();
    });

    // Refresh table
    $('#refreshTable').on('click', function() {
        table.ajax.reload();
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#schoolFilter, #userTypeFilter, #statusFilter').val('');
        table.draw();
    });

    // Select all checkbox
    $('#selectAll').on('change', function() {
        $('.user-checkbox').prop('checked', this.checked);
        updateBulkActionsButton();
    });

    // Individual checkbox change
    $(document).on('change', '.user-checkbox', function() {
        updateBulkActionsButton();
        
        // Update select all checkbox
        const totalCheckboxes = $('.user-checkbox').length;
        const checkedCheckboxes = $('.user-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // Update bulk actions button
    function updateBulkActionsButton() {
        const checkedCount = $('.user-checkbox:checked').length;
        $('#bulkActionsBtn').prop('disabled', checkedCount === 0);
        $('#selectedCount').text(checkedCount);
    }

    // Bulk actions
    $('#bulkActionsBtn').on('click', function() {
        $('#bulkActionsModal').modal('show');
    });

    $('#bulkAction').on('change', function() {
        if ($(this).val() === 'assign_roles') {
            $('#rolesSelection').show();
        } else {
            $('#rolesSelection').hide();
        }
    });

    $('#executeBulkAction').on('click', function() {
        const action = $('#bulkAction').val();
        const selectedUsers = $('.user-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (!action || selectedUsers.length === 0) {
            toastr.error('Please select an action and users');
            return;
        }

        let data = {
            _token: '{{ csrf_token() }}',
            user_ids: selectedUsers
        };

        let url = '';
        let method = 'POST';

        switch (action) {
            case 'assign_roles':
                const selectedRoles = $('input[name="bulk_roles[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                
                if (selectedRoles.length === 0) {
                    toastr.error('Please select at least one role');
                    return;
                }
                
                data.roles = selectedRoles;
                url = '{{ route("superadmin.users.bulk-assign-roles") }}';
                break;
            case 'activate':
            case 'deactivate':
                // These would need separate endpoints
                toastr.info('Feature coming soon');
                return;
        }

        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(response) {
                $('#bulkActionsModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    table.ajax.reload();
                    $('.user-checkbox').prop('checked', false);
                    $('#selectAll').prop('checked', false);
                    updateBulkActionsButton();
 