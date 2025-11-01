@extends('layouts.admin')

@section('title', 'Teachers Management')
@section('page-title', 'Teachers')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Teachers</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Teachers</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="add-teacher-btn">
                        <i class="fas fa-plus"></i> Add New Teacher
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="teachers-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>School</th>
                                <th>Phone</th>
                                <th>Status</th>
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

<!-- Add/Edit Teacher Modal -->
<div class="modal fade" id="teacher-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teacher-modal-label">Add New Teacher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="teacher-form">
                @csrf
                <input type="hidden" name="_method" id="teacher-form-method" value="POST">
                <input type="hidden" name="id" id="teacher-id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_name">Name *</label>
                                <input type="text" class="form-control" id="user_name" name="user_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_email">Email *</label>
                                <input type="email" class="form-control" id="user_email" name="user_email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="school_id">School *</label>
                                <select class="form-control select2" id="school_id" name="school_id" style="width: 100%;" required>
                                    <option value="">Select School</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="qualification">Qualification</label>
                        <textarea class="form-control" id="qualification" name="qualification" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_active">Status</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-teacher-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this teacher?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var teachers_table = $('#teachers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.teachers.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'user.name', name: 'user.name' },
                { data: 'user.email', name: 'user.email' },
                { data: 'school.name', name: 'school.name' },
                { data: 'phone', name: 'phone' },
                { data: 'status', name: 'status', searchable: false, orderable: false },
                { data: 'actions', name: 'actions', searchable: false, orderable: false }
            ]
        });

        // Show add teacher modal
        $('#add-teacher-btn').on('click', function() {
            $('#teacher-form')[0].reset();
            $('.select2').val('').trigger('change');
            $('#teacher-modal-label').text('Add New Teacher');
            $('#teacher-form-method').val('POST');
            $('#teacher-form').attr('action', "{{ route('admin.teachers.store') }}");
            $('#teacher-modal').modal('show');
        });

        // Show edit teacher modal
        $('#teachers-table').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $.get("{{ url('/admin/teachers') }}" + '/' + id + '/edit', function(data) {
                $('#teacher-modal-label').text('Edit Teacher');
                $('#teacher-form-method').val('PUT');
                $('#teacher-form').attr('action', "{{ url('/admin/teachers') }}" + '/' + id);
                $('#teacher-id').val(data.id);
                $('#user_name').val(data.user.name);
                $('#user_email').val(data.user.email);
                $('#phone').val(data.phone);
                $('#school_id').val(data.school_id).trigger('change');
                $('#gender').val(data.gender);
                $('#date_of_birth').val(data.date_of_birth);
                $('#address').val(data.address);
                $('#qualification').val(data.qualification);
                $('#is_active').val(data.is_active ? 1 : 0);
                $('#teacher-modal').modal('show');
            });
        });

        // Save teacher (create/update)
        $('#save-teacher-btn').on('click', function() {
            var form = $('#teacher-form');
            var url = form.attr('action');
            var method = $('#teacher-form-method').val();
            var data = form.serialize();

            $.ajax({
                url: url,
                type: method,
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#teacher-modal').modal('hide');
                        teachers_table.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        var errors = response.errors;
                        var errorMessages = '';
                        $.each(errors, function(key, value) {
                            errorMessages += value[0] + '<br>';
                        });
                        toastr.error(errorMessages);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    var message = response.message || 'An error occurred.';
                    toastr.error(message);
                }
            });
        });

        // Show delete confirmation
        var deleteUrl;
        $(document).on('click', '.delete-btn', function() {
            deleteUrl = $(this).data('url');
            $('#delete-modal').modal('show');
        });

        // Confirm delete
        $('#confirm-delete-btn').on('click', function() {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        $('#delete-modal').modal('hide');
                        teachers_table.ajax.reload();
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    var message = response.message || 'An error occurred.';
                    toastr.error(message);
                }
            });
        });

        // Toggle status
        $(document).on('click', '.toggle-status-btn', function() {
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        teachers_table.ajax.reload();
                        toastr.success(response.message);
                    }
                }
            });
        });
    });
</script>
@endpush