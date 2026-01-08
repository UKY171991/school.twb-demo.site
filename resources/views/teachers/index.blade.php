@extends('layouts.app')

@section('title', 'Teachers')

@section('content_header')
    <h1><i class="fas fa-chalkboard-teacher"></i> Teachers Management</h1>
@stop

@section('content')
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
                <button id="add-teacher-btn" data-url="{{ route('teachers.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Teacher
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="teachersTable" style="width:100%">
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
                        {{-- Data will be loaded by DataTables --}}
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
@stop

@section('js')
    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.bootstrap4.min.js"></script>
    
    {{-- Teachers Page JS --}}
    <script src="{{ asset('js/teachers.js') }}"></script>
@stop