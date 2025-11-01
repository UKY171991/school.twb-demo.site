@extends('layouts.admin')

@section('title', 'Attendance Details')
@section('page-title', 'Attendance Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Attendance</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <h3 class="profile-username text-center">{{ $attendance->student->user->name }}</h3>
                <p class="text-muted text-center">{{ $attendance->classModel->name }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Date</b> <a class="float-right">{{ $attendance->date->format('d M, Y') }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <a class="float-right">
                            <span class="badge 
                                @switch($attendance->status)
                                    @case('present') badge-success @break
                                    @case('absent') badge-danger @break
                                    @case('late') badge-warning @break
                                    @case('excused') badge-info @break
                                @endswitch
                            ">{{ ucfirst($attendance->status) }}</span>
                        </a>
                    </li>
                </ul>

                @if($attendance->remarks)
                <div class="mt-3">
                    <strong><i class="fas fa-file-alt mr-1"></i> Remarks</strong>
                    <p class="text-muted">{{ $attendance->remarks }}</p>
                </div>
                @endif

                <div class="text-right mt-3">
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">Back to List</a>
                    <a href="{{ route('admin.attendance.edit', $attendance) }}" class="btn btn-primary">Edit Attendance</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
