@extends('layouts.app')

@section('title', 'User Roles')

@section('content_header')
    <h1><i class="fas fa-user-tag"></i> User Roles</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> List of Roles</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="rolesTable">
                    <thead>
                        <tr>
                            <th width="20%">Role</th>
                            <th width="80%">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td>
                                @if($role['name'] == 'Master')
                                    <span class="badge badge-danger">Master</span>
                                @elseif($role['name'] == 'Admin')
                                    <span class="badge badge-warning">Admin</span>
                                @else
                                    <span class="badge badge-info">User</span>
                                @endif
                            </td>
                            <td>{{ $role['description'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-4">
                                <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No roles found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
            $('#rolesTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                order: [[0, 'asc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search roles...",
                    zeroRecords: "No matching roles found",
                    emptyTable: "No roles available"
                }
            });
        });
    </script>
@stop
