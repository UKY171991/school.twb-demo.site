@extends('adminlte::page')

@section('title', 'Student Fees')

@section('content_header')
    <h1><i class="fas fa-money-bill-wave"></i> Student Fees Management</h1>
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
            <h3 class="card-title"><i class="fas fa-list"></i> List of Student Fees</h3>
            <div class="card-tools">
                <a href="{{ route('student-fees.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Add Fee
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="studentFeesTable">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Month/Year</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fees as $fee)
                        <tr>
                            <td>{{ $fee->student->name ?? 'N/A' }}</td>
                            <td>{{ $fee->student->grade->name ?? 'N/A' }}</td>
                            <td>{{ $fee->month_name }} {{ $fee->fee_year }}</td>
                            <td>₹{{ number_format($fee->total_amount, 2) }}</td>
                            <td>₹{{ number_format($fee->paid_amount, 2) }}</td>
                            <td>₹{{ number_format($fee->balance, 2) }}</td>
                            <td>{!! $fee->status_badge !!}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('student-fees.edit', $fee) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('student-fees.print-slip', $fee) }}" class="btn btn-info" target="_blank" title="Print Slip">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <form action="{{ route('student-fees.destroy', $fee) }}" method="POST" style="display:inline-block;" 
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
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-money-bill-alt text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No fee records found.</p>
                                <a href="{{ route('student-fees.create') }}" class="btn btn-success btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Add First Fee
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($fees->hasPages())
        <div class="card-footer">
            {{ $fees->links() }}
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
            var table = $('#studentFeesTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[2, 'desc']], // Order by Month/Year (approx) or ID
                columnDefs: [
                    { orderable: false, targets: [7] }, // Disable sorting on Actions column
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search fees...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    infoFiltered: "(filtered from _MAX_ total records)",
                    zeroRecords: "No matching records found",
                    emptyTable: "No fee records available"
                },
                initComplete: function () {
                    // Add filter row
                    var filterRow = $('<tr class="filter-row"></tr>');
                    
                    this.api().columns().every(function (index) {
                        var column = this;
                        var th = $('<th></th>');
                        
                        // Skip Actions column
                        if (index === 7) {
                            th.html('');
                            filterRow.append(th);
                            return;
                        }
                        
                        // For Status column (index 6 - verify index!)
                        // Columns: 0:Student, 1:Class, 2:Month/Year, 3:Total, 4:Paid, 5:Balance, 6:Status, 7:Actions
                        if (index === 6) {
                            // Status filter
                           var select = $('<select><option value="">All</option><option value="Paid">Paid</option><option value="Partial">Partial</option><option value="Unpaid">Unpaid</option></select>')
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    // Status usually in badge, usually simple text inside
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
