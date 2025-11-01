@extends('layouts.admin')

@section('title', 'Grade Details')
@section('page-title', 'Grade Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.grades.index') }}">Grades</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <h3 class="profile-username text-center">{{ $grade->student->user->name }}</h3>
                <p class="text-muted text-center">{{ $grade->subject->name }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Class</b> <a class="float-right">{{ $grade->classModel->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Exam Type</b> <a class="float-right">{{ ucfirst($grade->exam_type) }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Exam Date</b> <a class="float-right">{{ $grade->exam_date->format('d M, Y') }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Marks</b> <a class="float-right">{{ $grade->marks_obtained }} / {{ $grade->total_marks }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Grade</b> <a class="float-right"><span class="badge badge-primary">{{ $grade->grade }}</span></a>
                    </li>
                </ul>

                @if($grade->remarks)
                <div class="mt-3">
                    <strong><i class="fas fa-file-alt mr-1"></i> Remarks</strong>
                    <p class="text-muted">{{ $grade->remarks }}</p>
                </div>
                @endif

                <div class="text-right mt-3">
                    <a href="{{ route('admin.grades.index') }}" class="btn btn-secondary">Back to List</a>
                    <a href="{{ route('admin.grades.edit', $grade) }}" class="btn btn-primary">Edit Grade</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
