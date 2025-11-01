@extends('layouts.admin')

@section('title', 'Classes Management')
@section('page-title', 'Classes')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Classes</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Classes</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="add-class-btn">
                        <i class="fas fa-plus"></i> Add New Class
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="classes-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>School</th>
                                <th>Teacher</th>
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

<!-- Add/Edit Class Modal -->
<div class="modal fade" id="class-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="class-modal-label">Add New Class</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="class-form">
                @csrf
                <input type="hidden" name="_method" id="class-form-method" value="POST">
                <input type="hidden" name="id" id="class-id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Class Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
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
                                <label for="teacher_id">Teacher *</label>
                                <select class="form-control select2" id="teacher_id" name="teacher_id" style="width: 100%;" required>
                                    <option value="">Select Teacher</option>
                                </select>
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
                    <button type="button" class="btn btn-primary" id="save-class-btn">Save</button>
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
                Are you sure you want to delete this class?
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
        var classes_table = $('#classes-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.classes.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'school.name', name: 'school.name' },
                { data: 'teacher.user.name', name: 'teacher.user.name' },
                { data: 'students_count', name: 'students_count', searchable: false },
                { data: 'status', name: 'status', searchable: false, orderable: false },
                { data: 'actions', name: 'actions', searchable: false, orderable: false }
            ]
        });

        // Show add class modal
        $('#add-class-btn').on('click', function() {
            $('#class-form')[0].reset();
            $('.select2').val('').trigger('change');
            $('#class-modal-label').text('Add New Class');
            $('#class-form-method').val('POST');
            $('#class-form').attr('action', "{{ route('admin.classes.store') }}");
            $('#class-modal').modal('show');
        });

        // Load teachers based on selected school
        $('#school_id').on('change', function() {
            var schoolId = $(this).val();
            if (schoolId) {
                $.ajax({
                    url: '/admin/schools/' + schoolId + '/teachers',
                    type: 'GET',
                    success: function(data) {
                        $('#teacher_id').empty();
                        $('#teacher_id').append('<option value="">Select Teacher</option>');
                        $.each(data, function(key, value) {
                            $('#teacher_id').append('<option value="' + value.id + '">' + value.user.name + '</option>');
                        });
                    }
                });
            } else {
                $('#teacher_id').empty();
                $('#teacher_id').append('<option value="">Select Teacher</option>');
            }
        });

        // Show edit class modal
        $('#classes-table').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $.get("{{ url('/admin/classes') }}" + '/' + id + '/edit', function(data) {
                $('#class-modal-label').text('Edit Class');
                $('#class-form-method').val('PUT');
                $('#class-form').attr('action', "{{ url('/admin/classes') }}" + '/' + id);
                $('#class-id').val(data.id);
                $('#name').val(data.name);
                $('#school_id').val(data.school_id).trigger('change');
                setTimeout(function() {
                    $('#teacher_id').val(data.teacher_id).trigger('change');
                }, 500);
                $('#is_active').val(data.is_active ? 1 : 0);
                $('#description').val(data.description);
                $('#class-modal').modal('show');
            });
        });

        // Save class (create/update)
        $('#save-class-btn').on('click', function() {
            var form = $('#class-form');
            var url = form.attr('action');
            var method = $('#class-form-method').val();
            var data = form.serialize();

            $.ajax({
                url: url,
                type: method,
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#class-modal').modal('hide');
                        classes_table.ajax.reload();
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
                        classes_table.ajax.reload();
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
                        classes_table.ajax.reload();
                        toastr.success(response.message);
                    }
                }
            });
        });
    });
</script>
@endpush