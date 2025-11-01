@extends('layouts.admin')

@section('title', 'Schools Management')
@section('page-title', 'Schools')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Schools</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Schools</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="add-school-btn">
                        <i class="fas fa-plus"></i> Add New School
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="schools-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Principal</th>
                                <th>Teachers</th>
                                <th>Students</th>
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

<!-- Add/Edit School Modal -->
<div class="modal fade" id="school-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="school-modal-label">Add New School</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="school-form">
                @csrf
                <input type="hidden" name="_method" id="school-form-method" value="POST">
                <input type="hidden" name="id" id="school-id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">School Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="principal_name">Principal Name</label>
                                <input type="text" class="form-control" id="principal_name" name="principal_name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="established_year">Established Year</label>
                                <input type="number" class="form-control" id="established_year" name="established_year" min="1900" max="{{ date('Y') }}">
                            </div>
                        </div>
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
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-school-btn">Save</button>
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
                Are you sure you want to delete this school?
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
        var schools_table = $('#schools-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.schools.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'address', name: 'address' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'principal_name', name: 'principal_name' },
                { data: 'teachers_count', name: 'teachers_count', searchable: false },
                { data: 'students_count', name: 'students_count', searchable: false },
                { data: 'status', name: 'status', searchable: false, orderable: false },
                { data: 'actions', name: 'actions', searchable: false, orderable: false }
            ]
        });

        // Show add school modal
        $('#add-school-btn').on('click', function() {
            $('#school-form')[0].reset();
            $('#school-modal-label').text('Add New School');
            $('#school-form-method').val('POST');
            $('#school-form').attr('action', "{{ route('admin.schools.store') }}");
            $('#school-modal').modal('show');
        });

        // Show edit school modal
        $('#schools-table').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $.get("{{ url('/admin/schools') }}" + '/' + id + '/edit', function(data) {
                $('#school-modal-label').text('Edit School');
                $('#school-form-method').val('PUT');
                $('#school-form').attr('action', "{{ url('/admin/schools') }}" + '/' + id);
                $('#school-id').val(data.id);
                $('#name').val(data.name);
                $('#address').val(data.address);
                $('#phone').val(data.phone);
                $('#email').val(data.email);
                $('#principal_name').val(data.principal_name);
                $('#established_year').val(data.established_year);
                $('#description').val(data.description);
                $('#is_active').val(data.is_active ? 1 : 0);
                $('#school-modal').modal('show');
            });
        });

        // Save school (create/update)
        $('#save-school-btn').on('click', function() {
            var form = $('#school-form');
            var url = form.attr('action');
            var method = $('#school-form-method').val();
            var data = form.serialize();

            $.ajax({
                url: url,
                type: method,
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#school-modal').modal('hide');
                        schools_table.ajax.reload();
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
                        schools_table.ajax.reload();
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
                        schools_table.ajax.reload();
                        toastr.success(response.message);
                    }
                }
            });
        });
    });
</script>
@endpush