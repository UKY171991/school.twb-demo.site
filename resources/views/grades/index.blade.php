@extends('layouts.app')

@section('title', 'Classes Management')

@section('adminlte_css_pre')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@stop

@section('content_header')
    <h1><i class="fas fa-layer-group"></i> Classes Management</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card grade-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> List of Classes</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
                <button onclick="openAjaxModal('{{ route('grades.create') }}', 'Add New Class')" class="btn btn-primary btn-sm ml-2">
                    <i class="fas fa-plus"></i> Add New Class
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover grades-table" id="gradesTable">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Class</th>
                            <th width="15%">Section</th>
                            <th width="20%">Students Count</th>
                            <th width="15%">Class Teacher</th>
                            <th width="15%">Status</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                        <tr>
                            <td>
                                {{ $grade->id }}
                            </td>
                            <td>
                                <div class="grade-badge grade-{{ $grade->id <= 12 ? $grade->id : '1' }}">
                                    {{ $grade->name }}
                                </div>
                            </td>
                            <td>
                                @if($grade->section)
                                    <div class="section-badge">{{ $grade->section }}</div>
                                @else
                                    <div class="section-badge no-section">N/A</div>
                                @endif
                            </td>
                            <td>
                                <div class="student-count {{ ($grade->students_count ?? 0) == 0 ? 'zero' : '' }}">
                                    <i class="fas fa-user-graduate"></i>
                                    <span class="count">{{ $grade->students_count ?? 0 }}</span>
                                    <span>students</span>
                                </div>
                            </td>
                            <td>
                                @if($grade->teacher)
                                    <span class="text-muted">
                                        <i class="fas fa-chalkboard-teacher text-info"></i> {{ $grade->teacher->name }}
                                    </span>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Active
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-success btn-sm" data-grade-id="{{ $grade->id }}" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="openAjaxModal('{{ route('grades.edit', $grade->id) }}', 'Edit {{ addslashes($grade->name) }}')" class="btn btn-info btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteAjaxItem('{{ route('grades.destroy', $grade->id) }}', '{{ addslashes($grade->name) }}')" class="btn btn-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h3>No Classes Found</h3>
                                    <p>Start by adding your first class to begin organizing your students.</p>
                                    <button onclick="openAjaxModal('{{ route('grades.create') }}', 'Add New Class')" class="btn btn-primary btn-sm mt-3">
                                        <i class="fas fa-plus"></i> Add First Class
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($grades->hasPages())
        <div class="card-footer">
            {{ $grades->links() }}
        </div>
        @endif
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
        .grade-badge {
            font-weight: bold;
        }
        .section-badge {
            display: inline-block;
            padding: 2px 8px;
            background: #e9ecef;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .student-count i {
            color: #17a2b8;
            margin-right: 5px;
        }
        .empty-state {
            text-align: center;
            padding: 20px;
        }
        .empty-state .icon {
            font-size: 3em;
            color: #dee2e6;
            margin-bottom: 15px;
        }
    </style>
@stop

@section('js')
    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    
    {{-- AJAX CRUD --}}
    <script src="{{ asset('js/ajax-crud.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#gradesTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[1, 'asc']], // Order by Class name by default
                columnDefs: [
                    { orderable: false, targets: [6] }, // Disable sorting on Actions column
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search classes...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ classes",
                    infoEmpty: "Showing 0 to 0 of 0 classes",
                    infoFiltered: "(filtered from _MAX_ total classes)",
                    zeroRecords: "No matching classes found",
                    emptyTable: "No classes available"
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
                        
                        // For Status column (index 5)
                        if (index === 5) {
                            var select = $('<select><option value="">All</option><option value="Active">Active</option></select>')
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

