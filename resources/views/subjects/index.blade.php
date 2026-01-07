@extends('layouts.app')

@section('title', 'Subjects')

@section('content_header')
    <h1>Subjects Management</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List of Subjects</h3>
            <div class="card-tools">
                <button onclick="openAjaxModal('{{ route('subjects.create') }}', 'Add New Subject')" class="btn btn-primary btn-sm">Add New Subject</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover" id="subjectsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject Name</th>
                        <th>Code</th>
                        <th>Class</th>
                        <th>Teacher</th>
                        <th>Max/Pass Marks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr>
                        <td>{{ $subject->id }}</td>
                        <td>{{ $subject->name }}</td>
                        <td>{{ $subject->code ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ $subject->grade->name ?? 'N/A' }}
                                @if($subject->grade && $subject->grade->section)
                                    - {{ $subject->grade->section }}
                                @endif
                            </span>
                        </td>
                        <td>{{ $subject->teacher->name ?? 'Not Assigned' }}</td>
                        <td>
                            @if($subject->max_marks || $subject->pass_marks)
                                <span class="badge badge-secondary">{{ $subject->max_marks ?? 'N/A' }}/{{ $subject->pass_marks ?? 'N/A' }}</span>
                            @else
                                <span class="text-muted">Not Set</span>
                            @endif
                        </td>
                        <td>
                            <button onclick="openAjaxModal('{{ route('subjects.edit', $subject->id) }}', 'Edit {{ addslashes($subject->name) }}')" class="btn btn-info btn-sm">Edit</button>
                            <button onclick="deleteAjaxItem('{{ route('subjects.destroy', $subject->id) }}', '{{ addslashes($subject->name) }}')" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No subjects found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
    
    {{-- AJAX CRUD --}}
    <script src="{{ asset('js/ajax-crud.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#subjectsTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [6] }, // Disable sorting on Actions column
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search subjects...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ subjects",
                    infoEmpty: "Showing 0 to 0 of 0 subjects",
                    infoFiltered: "(filtered from _MAX_ total subjects)",
                    zeroRecords: "No matching subjects found",
                    emptyTable: "No subjects available"
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
                        
                        // For all columns, create text inputs
                        var input = $('<input type="text" placeholder="Filter..." />')
                            .on('keyup change', function () {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                        th.append(input);
                        
                        filterRow.append(th);
                    });
                    
                    $(this.api().table().header()).append(filterRow);
                }
            });
        });
    </script>
@stop
