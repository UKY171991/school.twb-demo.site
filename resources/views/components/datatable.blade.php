@props([
    'id' => 'datatable',
    'ajaxUrl' => null,
    'columns' => [],
    'buttons' => true,
    'responsive' => true,
    'serverSide' => true,
    'pageLength' => 25,
    'searching' => true,
    'ordering' => true,
    'info' => true,
    'lengthChange' => true
])

<div class="table-responsive">
    <table id="{{ $id }}" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column['title'] ?? $column['data'] ?? '' }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const tableConfig = {
        processing: true,
        @if($serverSide && $ajaxUrl)
        serverSide: true,
        ajax: {
            url: '{{ $ajaxUrl }}',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTable AJAX Error:', error);
                toastr.error('Failed to load table data');
            }
        },
        @endif
        columns: @json($columns),
        pageLength: {{ $pageLength }},
        responsive: {{ $responsive ? 'true' : 'false' }},
        searching: {{ $searching ? 'true' : 'false' }},
        ordering: {{ $ordering ? 'true' : 'false' }},
        info: {{ $info ? 'true' : 'false' }},
        lengthChange: {{ $lengthChange ? 'true' : 'false' }},
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...',
            emptyTable: 'No data available',
            zeroRecords: 'No matching records found',
            lengthMenu: 'Show _MENU_ entries per page',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            infoEmpty: 'Showing 0 to 0 of 0 entries',
            infoFiltered: '(filtered from _MAX_ total entries)',
            search: 'Search:',
            paginate: {
                first: 'First',
                last: 'Last',
                next: 'Next',
                previous: 'Previous'
            }
        },
        @if($buttons)
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'csv',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'excel',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'pdf',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'print',
                className: 'btn btn-secondary btn-sm'
            }
        ],
        @endif
        drawCallback: function(settings) {
            // Reinitialize tooltips and other UI elements after table redraw
            $('[data-toggle="tooltip"]').tooltip();
        }
    };
    
    const table = $('#{{ $id }}').DataTable(tableConfig);
    
    // Store table instance globally for external access
    window.datatables = window.datatables || {};
    window.datatables['{{ $id }}'] = table;
    
    // Custom refresh function
    window.refreshDataTable = function(tableId = '{{ $id }}') {
        if (window.datatables[tableId]) {
            window.datatables[tableId].ajax.reload(null, false);
        }
    };
});
</script>
@endpush