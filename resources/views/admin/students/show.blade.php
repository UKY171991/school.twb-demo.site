@extends('layouts.admin')

@section('title', 'Student Details')
@section('page-title', 'Student: ' . $student->user->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{ $student->user->profile_photo_url ?? 'https://via.placeholder.com/128' }}"
                         alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{ $student->user->name }}</h3>
                <p class="text-muted text-center">Student</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Student ID</b> <a class="float-right">{{ $student->student_id }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>School</b> <a href="{{ route('admin.schools.show', $student->school) }}" class="float-right">{{ $student->school->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Class</b> <a class="float-right">{{ $student->classModel->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <a class="float-right">
                            <span class="badge {{ $student->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $student->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </a>
                    </li>
                </ul>

                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary btn-block"><b>Edit Profile</b></a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#about" data-toggle="tab">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#attendance" data-toggle="tab">Attendance</a></li>
                    <li class="nav-item"><a class="nav-link" href="#grades" data-toggle="tab">Grades</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="about">
                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted">{{ $student->user->email }}</p>
                        <hr>

                        <strong><i class="fas fa-phone mr-1"></i> Phone</strong>
                        <p class="text-muted">{{ $student->phone }}</p>
                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                        <p class="text-muted">{{ $student->address }}</p>
                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <strong><i class="fas fa-venus-mars mr-1"></i> Gender</strong>
                                <p class="text-muted">{{ ucfirst($student->gender) }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-birthday-cake mr-1"></i> Date of Birth</strong>
                                <p class="text-muted">{{ $student->date_of_birth->format('d M, Y') }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-calendar-alt mr-1"></i> Admission Date</strong>
                                <p class="text-muted">{{ $student->admission_date->format('d M, Y') }}</p>
                            </div>
                        </div>
                        <hr>
                        
                        <strong><i class="fas fa-user-shield mr-1"></i> Guardian</strong>
                        <p class="text-muted">
                            {{ $student->guardian_name }} ({{ $student->guardian_phone }})
                        </p>
                    </div>

                    <div class="tab-pane" id="attendance">
                        <!-- Attendance records will be loaded here via AJAX -->
                        <p class="text-center">Attendance records coming soon.</p>
                    </div>

                    <div class="tab-pane" id="grades">
                        <!-- Grades will be loaded here via AJAX -->
                        <p class="text-center">Grade records coming soon.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mt-3">
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
