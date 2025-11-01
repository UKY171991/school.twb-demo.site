@extends('layouts.admin')

@section('title', 'Grades Management')
@section('page-title', 'Grades')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Grades</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Grades</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="add-grade-btn">
                        <i class="fas fa-plus"></i> Add New Grade
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="grades-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Subject</th>
                                <th>Class</th>
                                <th>Marks</th>
                                <th>Grade</th>
                                <th>Exam Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grade Modal -->
<div class="modal fade" id="grade-modal" tabindex="-1" role="dialog" aria-labelledby="grade-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="grade-modal-label">Add New Grade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="grade-form">
                    @csrf
                    <input type="hidden" name="_method" id="grade-form-method">
                    <input type="hidden" name="id" id="grade-id">
                    <div id="grade-form-content"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-grade-btn">Save</button>
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
    var table = $('#grades-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.grades.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'student.user.name', name: 'student.user.name' },
            { data: 'subject.name', name: 'subject.name' },
            { data: 'class_model.name', name: 'classModel.name' },
            { data: 'marks', name: 'marks', searchable: false, orderable: false },
            { data: 'grade', name: 'grade' },
            { data: 'exam_date', name: 'exam_date' },
            { data: 'actions', name: 'actions', searchable: false, orderable: false }
        ]
    });

    // Add Grade button click
    $('#add-grade-btn').on('click', function() {
        $('#grade-modal-label').text('Add New Grade');
        $('#grade-form-method').val('POST');
        $('#grade-form').attr('action', "{{ route('admin.grades.store') }}");
        $('#grade-id').val('');
        $.get("{{ route('admin.grades.create') }}", function(data) {
            $('#grade-form-content').html(data);
            $('.select2').select2();
            $('#grade-modal').modal('show');
        });
    });

    // Edit Grade button click
    $('#grades-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#grade-modal-label').text('Edit Grade');
        $('#grade-form-method').val('PUT');
        $('#grade-form').attr('action', "{{ url('admin/grades') }}/" + id);
        $('#grade-id').val(id);
        $.get("{{ url('admin/grades') }}/" + id + '/edit', function(data) {
            $('#grade-form-content').html(data);
            $('.select2').select2();
            $('#grade-modal').modal('show');
        });
    });

    // Save Grade button click
    $('#save-grade-btn').on('click', function() {
        var form = $('#grade-form');
        var url = form.attr('action');
        var method = $('#grade-form-method').val();
        var data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#grade-modal').modal('hide');
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

    // Delete Grade button click
    var deleteUrl;
    $('#grades-table').on('click', '.delete-btn', function() {
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
