@extends('layouts.app')

@section('title', 'Teachers')

@section('content_header')
    <h1><i class="fas fa-chalkboard-teacher"></i> Teachers Management</h1>
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
            <h3 class="card-title"><i class="fas fa-list"></i> List of Teachers</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
                <button onclick="openAjaxModal('{{ route('teachers.create') }}', 'Add New Teacher')" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Teacher
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="teachersTable">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="8%">Photo</th>
                            <th width="20%">Name</th>
                            <th width="18%">Email</th>
                            <th width="12%">Phone</th>
                            <th width="8%">Gender</th>
                            <th width="10%">Status</th>
                            <th width="10%">School</th>
                            <th width="9%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                        <tr>
                            <td><span class="badge badge-secondary">{{ $teacher->id }}</span></td>
                            <td class="text-center">
                                @if($teacher->image)
                                    <img src="{{ $teacher->image_url }}" alt="{{ $teacher->name }}" 
                                         class="teacher-photo img-thumbnail" 
                                         style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%; cursor: pointer;">
                                @else
                                    <div class="teacher-photo-placeholder" 
                                         style="width: 45px; height: 45px; background-color: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $teacher->name }}</strong>
                                @if($teacher->date_of_joining)
                                    <br><small class="text-muted">Joined: {{ $teacher->date_of_joining->format('M Y') }}</small>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-envelope text-info"></i> {{ $teacher->email }}
                            </td>
                            <td>
                                @if($teacher->phone)
                                    <i class="fas fa-phone text-info"></i> {{ $teacher->phone }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $teacher->gender == 'male' ? 'bg-primary' : ($teacher->gender == 'female' ? 'bg-pink' : 'bg-secondary') }}">
                                    <i class="fas fa-{{ $teacher->gender == 'male' ? 'mars' : ($teacher->gender == 'female' ? 'venus' : 'genderless') }}"></i>
                                    {{ ucfirst($teacher->gender) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Active
                                </span>
                            </td>
                            <td>
                                @if($teacher->school)
                                    <small class="text-muted">{{ $teacher->school->name }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button onclick="openAjaxModal('{{ route('teachers.show', $teacher->id) }}', 'View {{ addslashes($teacher->name) }}')" class="btn btn-success" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="openAjaxModal('{{ route('teachers.edit', $teacher->id) }}', 'Edit {{ addslashes($teacher->name) }}')" class="btn btn-info" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteAjaxItem('{{ route('teachers.destroy', $teacher->id) }}', '{{ addslashes($teacher->name) }}')" class="btn btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-user-tie text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No teachers found.</p>
                                <button onclick="openAjaxModal('{{ route('teachers.create') }}', 'Add New Teacher')" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Add First Teacher
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($teachers->hasPages())
        <div class="card-footer">
            {{ $teachers->links() }}
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
    </style>
@stop

@section('js')
    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    
    {{-- AJAX CRUD JS --}}
    <script src="{{ asset('js/ajax-crud.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#teachersTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [1, 8] }, // Disable sorting on Photo and Actions columns
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search teachers...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ teachers",
                    infoEmpty: "Showing 0 to 0 of 0 teachers",
                    infoFiltered: "(filtered from _MAX_ total teachers)",
                    zeroRecords: "No matching teachers found",
                    emptyTable: "No teachers available"
                },
                initComplete: function () {
                    // Add filter row
                    var filterRow = $('<tr class="filter-row"></tr>');
                    
                    this.api().columns().every(function (index) {
                        var column = this;
                        var th = $('<th></th>');
                        
                        // Skip Photo and Actions columns
                        if (index === 1 || index === 8) {
                            th.html('');
                            filterRow.append(th);
                            return;
                        }
                        
                        // For Gender and Status columns, create dropdowns
                        if (index === 5) { // Gender
                            var select = $('<select><option value="">All</option><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option></select>')
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });
                            th.append(select);
                        } else if (index === 6) { // Status
                            var select = $('<select><option value="">All</option><option value="Active">Active</option></select>')
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

