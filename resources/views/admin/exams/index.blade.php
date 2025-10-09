@extends('layouts.adminlte')

@section('title', 'Exams Management')
@section('page-title', 'Exams')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Exams</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard-list mr-2"></i>All Exams</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.exams.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add New Exam
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Exam Name</th>
                            <th>Exam Type</th>
                            <th>Class</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No exams found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

