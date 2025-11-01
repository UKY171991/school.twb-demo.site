@extends('layouts.admin')

@section('title', 'Reports & Statistics')
@section('page-title', 'Reports & Statistics')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_schools'] }}</h3>
                <p>Total Schools</p>
            </div>
            <div class="icon">
                <i class="fas fa-school"></i>
            </div>
            <a href="{{ route('admin.schools.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['total_teachers'] }}</h3>
                <p>Total Teachers</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <a href="{{ route('admin.teachers.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['total_students'] }}</h3>
                <p>Total Students</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('admin.students.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['total_parents'] }}</h3>
                <p>Total Parents</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.parents.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Quick Links</h3>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('admin.reports.students') }}">Student Reports</a></li>
                    <li class="list-group-item"><a href="{{ route('admin.reports.teachers') }}">Teacher Reports</a></li>
                    <li class="list-group-item"><a href="{{ route('admin.reports.attendance') }}">Attendance Reports</a></li>
                    <li class="list-group-item"><a href="{{ route('admin.reports.grades') }}">Grade Reports</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">System Overview</h3>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Total Classes:</strong> {{ $stats['total_classes'] }}</li>
                    <li class="list-group-item"><strong>Total Subjects:</strong> {{ $stats['total_subjects'] }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
