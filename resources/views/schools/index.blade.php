@extends('layouts.app')

@section('title', 'Schools Management')

@section('adminlte_css_pre')
    @parent
@stop

@section('content_header')
    <h1><i class="fas fa-school"></i> Schools Management</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Success!</strong> {{ $message }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Error!</strong> {{ $message }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> List of Schools</h3>
            <div class="card-tools">
                <button onclick="openAjaxModal('{{ route('schools.create') }}', 'Add New School')" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New School
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="schoolsTable">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="8%">Logo</th>
                            <th width="18%">Name</th>
                            <th width="8%">Code</th>
                            <th width="12%">Principal</th>
                            <th width="15%">Contact</th>
                            <th width="8%">Status</th>
                            <th width="8%">Students</th>
                            <th width="8%">Teachers</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $school)
                        <tr>
                            <td><span class="badge badge-secondary">{{ $school->id }}</span></td>
                            <td class="text-center">
                                @if($school->logo)
                                    <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" 
                                         class="school-logo img-thumbnail">
                                @else
                                    <div class="logo-placeholder">
                                        <i class="fas fa-school"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $school->name }}</strong>
                                @if(session('current_school_id') == $school->id)
                                    <br><span class="badge badge-success"><i class="fas fa-check"></i> Current</span>
                                @endif
                            </td>
                            <td><code class="bg-light p-2 rounded">{{ $school->code }}</code></td>
                            <td>{{ $school->principal_name ?? '<span class="text-muted">N/A</span>' }}</td>
                            <td>
                                @if($school->phone || $school->email)
                                    @if($school->phone)
                                        <div><i class="fas fa-phone text-info"></i> {{ $school->phone }}</div>
                                    @endif
                                    @if($school->email)
                                        <div><i class="fas fa-envelope text-info"></i> <small>{{ $school->email }}</small></div>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($school->status == 'active')
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Active</span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-times-circle"></i> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    <i class="fas fa-users"></i> {{ $school->getActiveStudentsCount() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-warning">
                                    <i class="fas fa-chalkboard-teacher"></i> {{ $school->getActiveTeachersCount() }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="openAjaxModal('{{ route('schools.show', $school) }}', '{{ addslashes($school->name) }} Details')" class="btn btn-success btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="openAjaxModal('{{ route('schools.edit', $school) }}', 'Edit {{ addslashes($school->name) }}')" class="btn btn-info btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if(session('current_school_id') != $school->id)
                                        <form action="{{ route('schools.switch', $school) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm" title="Switch to this school">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <button onclick="deleteAjaxItem('{{ route('schools.destroy', $school) }}', '{{ addslashes($school->name) }}')" class="btn btn-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No schools found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($schools->hasPages())
        <div class="card-footer">
            {{ $schools->links() }}
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
        .school-logo {
            width: 100%;
            height: auto;
            object-fit: cover;
            max-width: 50px;
        }
        .logo-placeholder {
            width: 100%;
            height: auto;
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border-radius: 4px;
            margin: 0 auto;
            max-width: 50px;
            min-height: 50px;
        }
        .image-preview{
            width: 100%;
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
            var table = $('#schoolsTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [1, 9] }, // Disable sorting on Logo and Actions columns
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search schools...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ schools",
                    infoEmpty: "Showing 0 to 0 of 0 schools",
                    infoFiltered: "(filtered from _MAX_ total schools)",
                    zeroRecords: "No matching schools found",
                    emptyTable: "No schools available"
                },
                initComplete: function () {
                    // Add filter row
                    var filterRow = $('<tr class="filter-row"></tr>');
                    
                    this.api().columns().every(function (index) {
                        var column = this;
                        var th = $('<th></th>');
                        
                        // Skip Logo and Actions columns
                        if (index === 1 || index === 9) {
                            th.html('');
                            filterRow.append(th);
                            return;
                        }
                        
                        // For Status column, create dropdown
                        if (index === 6) { // Status
                            var select = $('<select><option value="">All</option><option value="Active">Active</option><option value="Inactive">Inactive</option></select>')
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? val : '', true, false).draw();
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
