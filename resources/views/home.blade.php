@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Dashboard</h1>
        @if(isset($currentSchool))
            <div class="badge badge-info badge-lg">
                <i class="fas fa-school"></i> {{ $currentSchool->name }}
            </div>
        @endif
    </div>
@stop

@section('content')
    <!-- Statistics Row -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['students'] }}</h3>
                    <p>Total Students</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="{{ route('students.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['teachers'] }}</h3>
                    <p>Total Teachers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('teachers.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['grades'] }}</h3>
                    <p>Total Grades/Classes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <a href="{{ route('grades.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['subjects'] }}</h3>
                    <p>Total Subjects</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('subjects.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ $stats['attendances_today'] }}</h3>
                    <p>Today's Attendance</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <a href="{{ route('attendances.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-teal">
                <div class="inner">
                    <h3>{{ $stats['marksheets'] }}</h3>
                    <p>Total Marksheets</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <a href="{{ route('marksheets.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('students.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Add Student
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('teachers.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-plus"></i> Add Teacher
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('grades.create') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-plus"></i> Add Grade
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('marksheets.create') }}" class="btn btn-info btn-block">
                                <i class="fas fa-plus"></i> Add Marksheet
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('schools.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-school"></i> Manage Schools
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Welcome to School Management System
                    </h3>
                </div>
                <div class="card-body">
                    <p>Welcome, <strong>{{ Auth::user()->name }}</strong>!</p>
                    <p>This is your admin dashboard where you can manage all aspects of the school including students, teachers, grades, subjects, attendance, and exam marks.</p>
                    <p>Use the sidebar navigation to access different modules or use the quick action buttons above to add new records.</p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
