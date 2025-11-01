@extends('layouts.admin')

@section('title', 'Teacher Details')
@section('page-title', 'Teacher: ' . $teacher->user->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">Teachers</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{ $teacher->user->profile_photo_url ?? 'https://via.placeholder.com/128' }}"
                         alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{ $teacher->user->name }}</h3>
                <p class="text-muted text-center">Teacher</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Employee ID</b> <a class="float-right">{{ $teacher->employee_id }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>School</b> <a href="{{ route('admin.schools.show', $teacher->school) }}" class="float-right">{{ $teacher->school->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <a class="float-right">
                            <span class="badge {{ $teacher->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </a>
                    </li>
                </ul>

                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-primary btn-block"><b>Edit Profile</b></a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#about" data-toggle="tab">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#classes" data-toggle="tab">Assigned Classes</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="about">
                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted">{{ $teacher->user->email }}</p>
                        <hr>

                        <strong><i class="fas fa-phone mr-1"></i> Phone</strong>
                        <p class="text-muted">{{ $teacher->phone }}</p>
                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                        <p class="text-muted">{{ $teacher->address }}</p>
                        <hr>

                        <strong><i class="fas fa-graduation-cap mr-1"></i> Qualification</strong>
                        <p class="text-muted">{{ $teacher->qualification }}</p>
                        <hr>

                        <strong><i class="fas fa-book mr-1"></i> Subject Specialization</strong>
                        <p class="text-muted">{{ $teacher->subject_specialization }}</p>
                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <strong><i class="fas fa-briefcase mr-1"></i> Experience</strong>
                                <p class="text-muted">{{ $teacher->experience_years }} years</p>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-dollar-sign mr-1"></i> Salary</strong>
                                <p class="text-muted">{{ number_format($teacher->salary, 2) }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-calendar-alt mr-1"></i> Joining Date</strong>
                                <p class="text-muted">{{ $teacher->joining_date->format('d M, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="classes">
                        @if($teacher->classes->count() > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Class Name</th>
                                        <th>Subject</th>
                                        <th>Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacher->classes as $class)
                                        <tr>
                                            <td>{{ $class->name }}</td>
                                            <td>{{ $class->subject->name ?? 'N/A' }}</td>
                                            <td>{{ $class->students->count() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center">This teacher is not assigned to any classes yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mt-3">
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
