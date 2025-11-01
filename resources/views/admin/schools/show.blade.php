@extends('layouts.admin')

@section('title', 'School Details')
@section('page-title', 'School: ' . $school->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.schools.index') }}">Schools</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="text-center">
                    <h3 class="profile-username">{{ $school->name }}</h3>
                    <p class="text-muted">{{ $school->email }}</p>
                </div>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Address</b> <a class="float-right">{{ $school->address }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Phone</b> <a class="float-right">{{ $school->phone }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Principal</b> <a class="float-right">{{ $school->principal_name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Established Year</b> <a class="float-right">{{ $school->established_year }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <a class="float-right">
                            <span class="badge {{ $school->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $school->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>Teachers</b> <a class="float-right"><span class="badge badge-info">{{ $school->teachers->count() }}</span></a>
                    </li>
                    <li class="list-group-item">
                        <b>Students</b> <a class="float-right"><span class="badge badge-success">{{ $school->students->count() }}</span></a>
                    </li>
                </ul>

                @if($school->description)
                <div class="mt-3">
                    <strong><i class="fas fa-file-alt mr-1"></i> Description</strong>
                    <p class="text-muted">{{ $school->description }}</p>
                </div>
                @endif

                <div class="text-right mt-3">
                    <a href="{{ route('admin.schools.index') }}" class="btn btn-secondary">Back to List</a>
                    <a href="{{ route('admin.schools.edit', $school) }}" class="btn btn-primary">Edit School</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
