@extends('layouts.adminlte')

@section('title', 'Departments')
@section('page-title', 'Departments')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item">Human Resource</li>
<li class="breadcrumb-item active">Departments</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-building mr-2"></i>All Departments</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add Department
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Department Name</th>
                            <th>Department Head</th>
                            <th>Staff Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No departments found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

