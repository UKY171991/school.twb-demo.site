@extends('layouts.admin')

@section('title', 'Parent Details')
@section('page-title', 'Parent: ' . $parent->user->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.parents.index') }}">Parents</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{ $parent->user->profile_photo_url ?? 'https://via.placeholder.com/128' }}"
                         alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{ $parent->user->name }}</h3>
                <p class="text-muted text-center">Parent</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Relationship</b> <a class="float-right">{{ $parent->relationship }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Children</b> <a class="float-right"><span class="badge badge-info">{{ $parent->students->count() }}</span></a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <a class="float-right">
                            <span class="badge {{ $parent->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $parent->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </a>
                    </li>
                </ul>

                <a href="{{ route('admin.parents.edit', $parent) }}" class="btn btn-primary btn-block"><b>Edit Profile</b></a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#about" data-toggle="tab">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#children" data-toggle="tab">Children</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="about">
                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted">{{ $parent->user->email }}</p>
                        <hr>

                        <strong><i class="fas fa-phone mr-1"></i> Phone</strong>
                        <p class="text-muted">{{ $parent->phone }}</p>
                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                        <p class="text-muted">{{ $parent->address }}</p>
                        <hr>

                        <strong><i class="fas fa-briefcase mr-1"></i> Occupation</strong>
                        <p class="text-muted">{{ $parent->occupation }}</p>
                    </div>

                    <div class="tab-pane" id="children">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>School</th>
                                        <th>Class</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($parent->students as $student)
                                        <tr>
                                            <td>{{ $student->student_id }}</td>
                                            <td>{{ $student->user->name }}</td>
                                            <td>{{ $student->school->name }}</td>
                                            <td>{{ $student->classModel->name }}</td>
                                            <td>
                                                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No children found for this parent.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mt-3">
            <a href="{{ route('admin.parents.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
