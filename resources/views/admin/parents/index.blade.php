@extends('layouts.admin')

@section('title', 'Parents Management')
@section('page-title', 'Parents')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Parents</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Parents</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="add-parent-btn">
                        <i class="fas fa-plus"></i> Add New Parent
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="parents-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Children</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Parent Modal -->
<div class="modal fade" id="parent-modal" tabindex="-1" role="dialog" aria-labelledby="parent-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="parent-modal-label">Add New Parent</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="parent-form">
                    @csrf
                    <input type="hidden" name="_method" id="parent-form-method">
                    <input type="hidden" name="id" id="parent-id">
                    <div id="parent-form-content"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-parent-btn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete-modal-label">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
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
    var table = $('#parents-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.parents.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user.name', name: 'user.name' },
            { data: 'user.email', name: 'user.email' },
            { data: 'phone', name: 'phone' },
            { data: 'students_count', name: 'students_count', searchable: false },
            { data: 'status', name: 'status', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', searchable: false, orderable: false }
        ]
    });

    // Add Parent button click
    $('#add-parent-btn').on('click', function() {
        $('#parent-modal-label').text('Add New Parent');
        $('#parent-form-method').val('POST');
        $('#parent-form').attr('action', "{{ route('admin.parents.store') }}");
        $('#parent-id').val('');
        $.get("{{ route('admin.parents.create') }}", function(data) {
            $('#parent-form-content').html(data);
            $('.select2').select2();
            $('#parent-modal').modal('show');
        });
    });

    // Edit Parent button click
    $('#parents-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#parent-modal-label').text('Edit Parent');
        $('#parent-form-method').val('PUT');
        $('#parent-form').attr('action', "{{ url('admin/parents') }}/" + id);
        $('#parent-id').val(id);
        $.get("{{ url('admin/parents') }}/" + id + '/edit', function(data) {
            $('#parent-form-content').html(data);
            $('.select2').select2();
            $('#parent-modal').modal('show');
        });
    });

    // Save Parent button click
    $('#save-parent-btn').on('click', function() {
        var form = $('#parent-form');
        var url = form.attr('action');
        var method = $('#parent-form-method').val();
        var data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#parent-modal').modal('hide');
                    table.ajax.reload();
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

    // Delete Parent button click
    var deleteUrl;
    $('#parents-table').on('click', '.delete-btn', function() {
        deleteUrl = $(this).data('url');
        $('#delete-modal').modal('show');
    });

    $('#confirm-delete-btn').on('click', function() {
        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    $('#delete-modal').modal('hide');
                    table.ajax.reload();
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

    // Toggle Status button click
    $('#parents-table').on('click', '.toggle-status-btn', function() {
        var url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    table.ajax.reload();
                    toastr.success(response.message);
                }
            }
        });
    });
});
</script>
@endpush
