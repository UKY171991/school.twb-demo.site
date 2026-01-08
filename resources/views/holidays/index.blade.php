
@extends('layouts.app')

@section('title', 'Holidays')

@section('content_header')
    <h1>Holidays</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Holiday Calendar</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" onclick="openHolidayForm('create')">New Holiday</button>
            </div>
        </div>
        <div class="card-body">
            <div id="ajax-messages"></div>
            <div id="holidays-table-container">
                <div class="text-center py-4">
                    <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                    <p>Loading holidays...</p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
function showAjaxMessage(message, type='success'){
    const html = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${message}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>`;
    $('#ajax-messages').html(html);
    setTimeout(()=>$('#ajax-messages .alert').fadeOut(),5000);
}

function loadHolidays(page = 1){
    $('#holidays-table-container').html(`<div class="text-center py-4"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div><p>Loading holidays...</p></div>`);
    $.ajax({
        url: '{{ route("holidays.index") }}',
        method: 'GET',
        data: { page: page },
        success: function(resp){
            renderHolidaysTable(resp);
        },
        error: function(xhr){
            $('#holidays-table-container').html(`<div class="alert alert-danger">Error loading holidays: ${xhr.responseJSON?.message || 'Unknown'}</div>`);
        }
    });
}

function renderHolidaysTable(resp){
    const data = resp.data || resp;
    let html = `<table class="table table-bordered table-hover"><thead><tr><th>ID</th><th>Title</th><th>From</th><th>To</th><th>Description</th><th>Actions</th></tr></thead><tbody>`;
    if (!data || data.length === 0) {
        html += `<tr><td colspan="6" class="text-center">No holidays found.</td></tr>`;
    } else {
        data.forEach(function(item){
            const start = item.start_date || '';
            const end = item.end_date || '';
            const desc = item.description ? (item.description.length > 80 ? item.description.substring(0,80) + '...' : item.description) : '';
            html += `<tr id="holiday-row-${item.id}"><td>${item.id}</td><td>${item.title}</td><td>${start}</td><td>${end}</td><td>${desc}</td><td>
                <button class="btn btn-sm btn-info" onclick="openHolidayForm('edit', ${item.id})">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteHoliday(${item.id})">Delete</button>
            </td></tr>`;
        });
    }
    html += `</tbody></table>`;

    if (resp.last_page && resp.last_page > 1) {
        html += `<nav><ul class="pagination">`;
        for(let p=1;p<=resp.last_page;p++){
            html += `<li class="page-item ${p===resp.current_page? 'active' : ''}"><a href="#" class="page-link" onclick="loadHolidays(${p});return false;">${p}</a></li>`;
        }
        html += `</ul></nav>`;
    }

    // html = `<div class="mb-2"><button class="btn btn-primary" id="new-holiday-btn">New Holiday</button></div>` + html; // Removed duplicate button

    $('#holidays-table-container').html(html);
    $('#new-holiday-btn').click(function(){ openHolidayForm('create'); });
}

function openHolidayForm(mode, id){
    let url = '';
    if (mode === 'create') url = '{{ route("holidays.create") }}';
    else url = '{{ url("admin/holidays") }}' + '/' + id + '/edit';

    $.ajax({
        url: url,
        method: 'GET',
        success: function(html){
            $('#holidayModal .modal-body').html(`<form id="holiday-form-wrapper" method="POST" action="${mode==='create' ? '{{ route("holidays.store") }}' : '{{ url("admin/holidays") }}' + '/' + id }">` + html + `</form>`);
            $('#holidayModal').modal('show');
        },
        error: function(xhr){
            showAjaxMessage('Error loading form: ' + (xhr.responseJSON?.message || 'Unknown'), 'danger');
        }
    });
}

$(document).on('submit', '#holiday-form-wrapper', function(e){
    e.preventDefault();
    const $form = $(this);
    const url = $form.attr('action');
    $.ajax({
        url: url,
        method: 'POST',
        data: $form.serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(resp){
            if (resp.success){
                $('#holidayModal').modal('hide');
                showAjaxMessage('Saved successfully', 'success');
                loadHolidays();
            } else {
                showAjaxMessage(resp.message || 'Save failed', 'danger');
            }
        },
        error: function(xhr){
            showAjaxMessage('Error saving: ' + (xhr.responseJSON?.message || 'Unknown'), 'danger');
        }
    });
});

function deleteHoliday(id){
    if (!confirm('Delete this holiday?')) return;
    $.ajax({
        url: '{{ url("admin/holidays") }}/' + id,
        method: 'POST',
        data: { _method: 'DELETE' },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(resp){
            if (resp.success){
                $('#holiday-row-' + id).remove();
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

$(function(){ loadHolidays(); });
</script>

<!-- modal -->
<div class="modal fade" id="holidayModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Holiday</h5>
        <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="text-center py-4"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>
      </div>
    </div>
  </div>
</div>

@stop
