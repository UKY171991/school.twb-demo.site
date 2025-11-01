@extends('layouts.admin')

@section('title', 'Attendance Management')
@section('page-title', 'Attendance')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Attendance</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Attendance Records</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Mark Attendance
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="attendance-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Date</th>
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

<!-- Attendance Modal -->
<div class="modal fade" id="attendance-modal" tabindex="-1" role="dialog" aria-labelledby="attendance-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendance-modal-label">Edit Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="attendance-form">
                    @csrf
                    <input type="hidden" name="_method" id="attendance-form-method">
                    <input type="hidden" name="id" id="attendance-id">
                    <div id="attendance-form-content"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-attendance-btn">Save</button>
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
    var table = $('#attendance-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.attendance.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'student.user.name', name: 'student.user.name' },
            { data: 'class_model.name', name: 'classModel.name' },
            { data: 'date', name: 'date' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', searchable: false, orderable: false }
        ]
    });

    // Edit Attendance button click
    $('#attendance-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#attendance-modal-label').text('Edit Attendance');
        $('#attendance-form-method').val('PUT');
        $('#attendance-form').attr('action', "{{ url('admin/attendance') }}/" + id);
        $('#attendance-id').val(id);
        $.get("{{ url('admin/attendance') }}/" + id + '/edit', function(data) {
            $('#attendance-form-content').html(data);
            $('#attendance-modal').modal('show');
        });
    });

    // Save Attendance button click
    $('#save-attendance-btn').on('click', function() {
        var form = $('#attendance-form');
        var url = form.attr('action');
        var method = $('#attendance-form-method').val();
        var data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#attendance-modal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'An error occurred.');
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var message = response.message || 'An error occurred.';
                toastr.error(message);
            }
        });
    });

    // Delete Attendance button click
    var deleteUrl;
    $('#attendance-table').on('click', '.delete-btn', function() {
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
});
</script>
@endpush
