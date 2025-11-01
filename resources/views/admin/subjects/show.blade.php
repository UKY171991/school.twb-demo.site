@extends('layouts.admin')

@section('title', 'Subject Details')
@section('page-title', 'Subject: ' . $subject->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.subjects.index') }}">Subjects</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <h3 class="profile-username text-center">{{ $subject->name }}</h3>
                <p class="text-muted text-center">{{ $subject->code }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>School</b> <a href="{{ route('admin.schools.show', $subject->school) }}" class="float-right">{{ $subject->school->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Teacher</b> <a href="{{ route('admin.teachers.show', $subject->teacher) }}" class="float-right">{{ $subject->teacher->user->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Credits</b> <a class="float-right">{{ $subject->credits }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <a class="float-right">
                            <span class="badge {{ $subject->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $subject->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </a>
                    </li>
                </ul>

                @if($subject->description)
                <div class="mt-3">
                    <strong><i class="fas fa-file-alt mr-1"></i> Description</strong>
                    <p class="text-muted">{{ $subject->description }}</p>
                </div>
                @endif

                <div class="text-right mt-3">
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Back to List</a>
                    <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-primary">Edit Subject</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
