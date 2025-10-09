@extends('layouts.adminlte')

@section('title', 'Super Admin Dashboard')
@section('page-title', 'Super Admin Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('sidebar')
<x-adminlte-superadmin-sidebar />
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row">
    <!-- Total Schools -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalSchools }}</h3>
                <p>Total Schools</p>
            </div>
            <div class="icon">
                <i class="fas fa-school"></i>
            </div>
            <a href="{{ route('superadmin.schools.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Users -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalUsers }}</h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('superadmin.users.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Students -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalStudents }}</h3>
                <p>Total Students</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="#" class="small-box-footer">
                Across all schools
            </a>
        </div>
    </div>

    <!-- System Status -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Active</h3>
                <p>System Status</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="#" class="small-box-footer">
                All systems operational
            </a>
        </div>
    </div>
</div>

<!-- Welcome Card -->
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-crown mr-2"></i>Welcome to Super Admin Dashboard</h3>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    You have full access to manage all schools, users, and system settings. Use the sidebar to navigate through different sections.
                </p>
                <table class="table table-sm table-bordered">
                    <tr>
                        <td width="150"><strong>Logged in as:</strong></td>
                        <td>{{ auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td><span class="badge badge-danger">{{ auth()->user()->role->name }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Active Schools:</strong></td>
                        <td>{{ $activeSchools }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

