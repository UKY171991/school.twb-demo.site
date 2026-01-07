@extends('adminlte::page')

@section('title', 'Exam Types')

@section('content_header')
    <h1><i class="fas fa-clipboard-list"></i> Exam Types Management</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-list"></i> List of Exam Types</h3>
        <div class="card-tools">
            <a href="{{ route('exam-types.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Exam Type
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="examTypesTable">
                <thead>
                    <tr>
                        <th width="20%">Name</th>
                        <th width="10%">Code</th>
                        <th width="15%">Duration</th>
                        <th width="10%">Weightage</th>
                        <th width="10%">Status</th>
                        <th width="10%">Sort Order</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($examTypes as $examType)
                        <tr class="{{ $examType->is_active ? '' : 'table-secondary' }}">
                            <td>
                                <strong>{{ $examType->name }}</strong>
                                @if($examType->description)
                                    <br><small class="text-muted">{{ $examType->description }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $examType->code }}</span>
                            </td>
                            <td>
                                @if($examType->duration_days)
                                    {{ $examType->duration_days }} days
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>{{ $examType->weightage }}%</td>
                            <td>
                                <span class="badge badge-{{ $examType->is_active ? 'success' : 'secondary' }}">
                                    {{ $examType->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $examType->sort_order }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('exam-types.edit', $examType) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('exam-types.toggle-status', $examType) }}" 
                                       class="btn btn-{{ $examType->is_active ? 'secondary' : 'success' }}" 
                                       title="{{ $examType->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $examType->is_active ? 'pause' : 'play' }}"></i>
                                    </a>
                                    <form action="{{ route('exam-types.destroy', $examType) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this exam type?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-clipboard-list text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No exam types found.</p>
                                <a href="{{ route('exam-types.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Create First Exam Type
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($examTypes->count() > 0)
            <div class="mt-4">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Exam Types Overview</h5>
                    <div class="row">
                        @foreach($examTypes->where('is_active', true) as $examType)
                            <div class="col-md-3 mb-2">
                                <div class="text-center p-2 border rounded bg-light">
                                    <strong>{{ $examType->name }}</strong><br>
                                    <small>{{ $examType->code }} ({{ $examType->weightage }}%)</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@stop

@section('css')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <style>
        .dataTables_filter {
            float: right;
        }
        .dataTables_length {
            float: left;
        }
        .dataTables_wrapper .row {
            margin-bottom: 10px;
        }
        /* Column filter styling */
        .filter-row input,
        .filter-row select {
            width: 100%;
            padding: 4px;
            font-size: 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .filter-row th {
            padding: 5px !important;
            background-color: #f8f9fa;
        }
    </style>
@stop

@section('js')
    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#examTypesTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[5, 'asc']], // Order by Sort Order by default
                columnDefs: [
                    { orderable: false, targets: [6] }, // Disable sorting on Actions column
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search exam types...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ exam types",
                    infoEmpty: "Showing 0 to 0 of 0 exam types",
                    infoFiltered: "(filtered from _MAX_ total exam types)",
                    zeroRecords: "No matching exam types found",
                    emptyTable: "No exam types available"
                },
                initComplete: function () {
                    // Add filter row
                    var filterRow = $('<tr class="filter-row"></tr>');
                    
                    this.api().columns().every(function (index) {
                        var column = this;
                        var th = $('<th></th>');
                        
                        // Skip Actions column
                        if (index === 6) {
                            th.html('');
                            filterRow.append(th);
                            return;
                        }
                        
                        // For Status column (index 4)
                        if (index === 4) {
                            var select = $('<select><option value="">All</option><option value="Active">Active</option><option value="Inactive">Inactive</option></select>')
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });
                            th.append(select);
                        } else {
                            // For other columns, create text inputs
                            var input = $('<input type="text" placeholder="Filter..." />')
                                .on('keyup change', function () {
                                    if (column.search() !== this.value) {
                                        column.search(this.value).draw();
                                    }
                                });
                            th.append(input);
                        }
                        
                        filterRow.append(th);
                    });
                    
                    $(this.api().table().header()).append(filterRow);
                }
            });
        });
    </script>
@stop