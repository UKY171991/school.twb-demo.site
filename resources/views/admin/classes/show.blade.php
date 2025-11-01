@extends('layouts.admin')

@section('title', 'Class Details')
@section('page-title', 'Class: ' . $class->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.classes.index') }}">Classes</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <h3 class="profile-username text-center">{{ $class->name }}</h3>
                <p class="text-muted text-center">{{ $class->school->name }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Teacher</b> <a href="{{ route('admin.teachers.show', $class->teacher) }}" class="float-right">{{ $class->teacher->user->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Room Number</b> <a class="float-right">{{ $class->room_number }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Capacity</b> <a class="float-right">{{ $class->capacity }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Students Enrolled</b> <a class="float-right"><span class="badge badge-info">{{ $class->students->count() }}</span></a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <a class="float-right">
                            <span class="badge {{ $class->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $class->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </a>
                    </li>
                </ul>

                @if($class->description)
                <div class="mt-3">
                    <strong><i class="fas fa-file-alt mr-1"></i> Description</strong>
                    <p class="text-muted">{{ $class->description }}</p>
                </div>
                @endif

                <div class="text-right mt-3">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Back to List</a>
                    <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-primary">Edit Class</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Students in this Class</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($class->students as $student)
                                <tr>
                                    <td>{{ $student->student_id }}</td>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->user->email }}</td>
                                    <td>{{ $student->phone }}</td>
                                    <td>
                                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No students found in this class.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
