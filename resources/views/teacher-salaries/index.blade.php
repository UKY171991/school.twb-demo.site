@extends('adminlte::page')

@section('title', 'Teacher Salaries')

@section('content_header')
    <h1><i class="fas fa-hand-holding-usd"></i> Teacher Salary Management</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> List of Teacher Salaries</h3>
            <div class="card-tools">
                <a href="{{ route('teacher-salaries.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Add Salary
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="teacherSalariesTable">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Month/Year</th>
                            <th>Gross</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $salary)
                        <tr>
                            <td>{{ $salary->teacher->name ?? 'N/A' }}</td>
                            <td>{{ $salary->month_name }} {{ $salary->salary_year }}</td>
                            <td>₹{{ number_format($salary->gross_salary, 2) }}</td>
                            <td>₹{{ number_format($salary->total_deductions, 2) }}</td>
                            <td>₹{{ number_format($salary->net_salary, 2) }}</td>
                            <td>{!! $salary->status_badge !!}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('teacher-salaries.edit', $salary) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('teacher-salaries.print-slip', $salary) }}" class="btn btn-info" target="_blank" title="Print Slip">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <form action="{{ route('teacher-salaries.destroy', $salary) }}" method="POST" style="display:inline-block;" 
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-file-invoice-dollar text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No salary records found.</p>
                                <a href="{{ route('teacher-salaries.create') }}" class="btn btn-success btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Add First Salary
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($salaries->hasPages())
        <div class="card-footer">
            {{ $salaries->links() }}
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
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#teacherSalariesTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[1, 'desc']], // Order by Month/Year approx
                columnDefs: [
                    { orderable: false, targets: [6] }, // Disable sorting on Actions column
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search salaries...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    infoFiltered: "(filtered from _MAX_ total records)",
                    zeroRecords: "No matching records found",
                    emptyTable: "No salary records available"
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
                            var select = $('<select><option value="">All</option><option value="Paid">Paid</option><option value="Pending">Pending</option><option value="Cancelled">Cancelled</option></select>')
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
