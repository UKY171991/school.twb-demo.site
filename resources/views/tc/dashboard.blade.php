@extends('layouts.tc')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="total-classes">{{ $stats['total_classes'] ?? 0 }}</h3>
                <p>My Classes</p>
            </div>
            <div class="icon">
                <i class="fas fa-door-open"></i>
            </div>
            <a href="{{ route('teacher.classes') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="total-students">{{ $stats['total_students'] ?? 0 }}</h3>
                <p>My Students</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('teacher.students') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="total-subjects">{{ $stats['total_subjects'] ?? 0 }}</h3>
                <p>My Subjects</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{ route('teacher.subjects') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="total-grades">{{ $stats['total_grades_recorded'] ?? 0 }}</h3>
                <p>Grades Recorded</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
            <a href="{{ route('teacher.grades') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- My Classes -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Classes</h3>
            </div>
            <div class="card-body">
                @if($classes->count() > 0)
                    <div class="list-group">
                        @foreach($classes as $class)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="mb-1">{{ $class->name }}</h5>
                                    <p class="mb-1 text-muted">{{ $class->school->name ?? 'N/A' }}</p>
                                    <small class="text-muted">Room: {{ $class->room_number }} | Capacity: {{ $class->capacity }}</small>
                                </div>
                                <div class="col-auto">
                                    <span class="badge badge-primary">{{ $class->students->count() }} students</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No classes assigned to you yet.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('teacher.attendance.create') }}" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-calendar-check"></i> Mark Attendance
                        </a>
                        <a href="{{ route('teacher.grades.create') }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-star"></i> Record Grade
                        </a>
                        <a href="{{ route('teacher.schedule') }}" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-calendar-alt"></i> View Schedule
                        </a>
                        <a href="{{ route('teacher.profile') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-user"></i> Update Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Today's Schedule -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Today's Schedule</h3>
            </div>
            <div class="card-body">
                <div id="today-schedule">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin"></i> Loading schedule...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection