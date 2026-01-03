
@extends('layouts.app')

@section('title', 'Leave Management')

@section('content_header')
    <h1>Leave Management</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Student Leaves</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" onclick="openLeaveForm('create')">New Leave</button>
            </div>
        </div>
        <div class="card-body">
            <div id="ajax-messages"></div>
            <div id="leaves-table-container">
                <div class="text-center py-4">
                    <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                    <p>Loading leaves...</p>
                </div>
            </div>
        </div>
    </div>
    
@section('js')
<script>
function showAjaxMessage(message, type='success'){
    const html = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${message}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>`;
    $('#ajax-messages').html(html);
    setTimeout(()=>$('#ajax-messages .alert').fadeOut(),5000);
}

function loadLeaves(page = 1){
    $('#leaves-table-container').html(`<div class="text-center py-4"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div><p>Loading leaves...</p></div>`);
    $.ajax({
        url: '{{ route("leaves.index") }}',
        method: 'GET',
        data: { page: page },
        success: function(resp){
            renderLeavesTable(resp);
        },
        error: function(xhr){
            $('#leaves-table-container').html(`<div class="alert alert-danger">Error loading leaves: ${xhr.responseJSON?.message || 'Unknown'}</div>`);
        }
    });
}

function renderLeavesTable(resp){
    const data = resp.data || resp;
    let html = `<table class="table table-bordered table-hover"><thead><tr><th>ID</th><th>Student</th><th>Grade</th><th>From</th><th>To</th><th>Type</th><th>Reason</th><th>Actions</th></tr></thead><tbody>`;
    if (!data || data.length === 0) {
        html += `<tr><td colspan="8" class="text-center">No leaves found.</td></tr>`;
    } else {
        data.forEach(function(item){
            const start = item.start_date || '';
            const end = item.end_date || '';
            const student = item.student ? (item.student.name || '-') : '-';
            const grade = item.grade ? (item.grade.name || '-') : '-';
            const reason = item.reason ? (item.reason.length > 60 ? item.reason.substring(0,60) + '...' : item.reason) : '';
            html += `<tr id="leave-row-${item.id}"><td>${item.id}</td><td>${student}</td><td>${grade}</td><td>${start}</td><td>${end}</td><td>${(item.type||'')}</td><td>${reason}</td><td>
                <button class="btn btn-sm btn-info" onclick="openLeaveForm('edit', ${item.id})">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteLeave(${item.id})">Delete</button>
            </td></tr>`;
        });
    }
    html += `</tbody></table>`;

    // pagination
    if (resp.last_page && resp.last_page > 1) {
        html += `<nav><ul class="pagination">`;
        for(let p=1;p<=resp.last_page;p++){
            html += `<li class="page-item ${p===resp.current_page? 'active' : ''}"><a href="#" class="page-link" onclick="loadLeaves(${p});return false;">${p}</a></li>`;
        }
        html += `</ul></nav>`;
    }

    $('#leaves-table-container').html(html);
}

function openLeaveForm(mode, id){
    let url = '';
    if (mode === 'create') url = '{{ route("leaves.create") }}';
    else url = '{{ url("admin/leaves") }}' + '/' + id + '/edit';

    $.ajax({
        url: url,
        method: 'GET',
        success: function(html){
            // show form inside modal
            $('#leaveModal .modal-body').html(`<form id="leave-form-wrapper" method="POST" action="${mode==='create' ? '{{ route("leaves.store") }}' : '{{ url("admin/leaves") }}' + '/' + id }">` + html + `</form>`);
            $('#leaveModal').modal('show');
        },
        error: function(xhr){
            showAjaxMessage('Error loading form: ' + (xhr.responseJSON?.message || 'Unknown'), 'danger');
        }
    });
}

// submit handler for dynamic form
$(document).on('submit', '#leave-form-wrapper', function(e){
    e.preventDefault();
    const $form = $(this);
    const url = $form.attr('action');
    const method = $form.find('input[name="_method"]').val() || 'POST';
    $.ajax({
        url: url,
        method: 'POST',
        data: $form.serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(resp){
            if (resp.success) {
                $('#leaveModal').modal('hide');
                showAjaxMessage('Saved successfully', 'success');
                loadLeaves();
            } else {
                showAjaxMessage(resp.message || 'Save failed', 'danger');
            }
        },
        error: function(xhr){
            showAjaxMessage('Error saving: ' + (xhr.responseJSON?.message || 'Unknown'), 'danger');
        }
    });
});

function deleteLeave(id){
    if (!confirm('Delete this leave?')) return;
    $.ajax({
        url: '{{ url("admin/leaves") }}/' + id,
        method: 'POST',
        data: { _method: 'DELETE' },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(resp){
            if (resp.success){
                $('#leave-row-' + id).remove();
                showAjaxMessage('Deleted successfully', 'success');
            } else {
                showAjaxMessage(resp.message || 'Delete failed', 'danger');
            }
        },
        error: function(xhr){
            showAjaxMessage('Error deleting: ' + (xhr.responseJSON?.message || 'Unknown'), 'danger');
        }
    });
}

// initial load
$(function(){ loadLeaves(); });
</script>

<!-- Modal to host create/edit form -->
<div class="modal fade" id="leaveModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Leave</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="text-center py-4"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>
      </div>
    </div>
  </div>
</div>

@stop
