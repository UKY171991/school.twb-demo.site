@extends('layouts.adminlte')

@section('title', 'Visitor Book')
@section('page-title', 'Visitor Book')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item">Front Office</li>
<li class="breadcrumb-item active">Visitor Book</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-check mr-2"></i>Visitor Records</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add Visitor
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Visitor Name</th>
                            <th>Phone</th>
                            <th>Purpose</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>ID Card</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No visitor records found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

