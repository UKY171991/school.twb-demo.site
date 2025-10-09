@extends('layouts.adminlte')

@section('title', 'Admin Dashboard')
@section('page-title', 'School Admin Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<!-- School Info Card -->
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <h3 class="card-title"><i class="fas fa-school mr-2"></i>{{ $school->name }}</h3>
                <p class="mb-0"><strong>Code:</strong> {{ $school->code }} | <strong>Email:</strong> {{ $school->email }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <!-- Total Students -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalStudents }}</h3>
                <p>Total Students</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('admin.students.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Teachers -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalTeachers }}</h3>
                <p>Total Teachers</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <a href="{{ route('admin.teachers.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Classes -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalClasses }}</h3>
                <p>Total Classes</p>
            </div>
            <div class="icon">
                <i class="fas fa-school"></i>
            </div>
            <a href="{{ route('admin.classes.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions & Welcome -->
<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bolt mr-2"></i>Quick Actions</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.students.create') }}" class="btn btn-info btn-block">
                    <i class="fas fa-user-plus mr-2"></i> Add New Student
                </a>
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-success btn-block">
                    <i class="fas fa-user-plus mr-2"></i> Add New Teacher
                </a>
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-warning btn-block">
                    <i class="fas fa-clipboard-check mr-2"></i> Mark Attendance
                </a>
            </div>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Welcome</h3>
            </div>
            <div class="card-body">
                <p>You are logged in as the School Administrator for <strong>{{ $school->name }}</strong>.</p>
                <table class="table table-sm">
                    <tr>
                        <td><strong>User:</strong></td>
                        <td>{{ auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td><span class="badge badge-primary">{{ auth()->user()->role->name }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>School:</strong></td>
                        <td>{{ $school->name }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

