@extends('adminlte::page')

@section('title', 'School Details')

@section('content_header')
    <h1>{{ $school->name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">School Information</h3>
                    <div class="card-tools">
                        <a href="{{ route('schools.edit', $school) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('schools.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="150">School Name</th>
                                    <td>{{ $school->name }}</td>
                                </tr>
                                <tr>
                                    <th>School Code</th>
                                    <td><code>{{ $school->code }}</code></td>
                                </tr>
                                <tr>
                                    <th>Principal</th>
                                    <td>{{ $school->principal_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($school->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="150">Phone</th>
                                    <td>{{ $school->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $school->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td>
                                        @if($school->website)
                                            <a href="{{ $school->website }}" target="_blank">{{ $school->website }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created</th>
                                    <td>{{ $school->created_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($school->address)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Address</h5>
                            <p class="text-muted">{{ $school->address }}</p>
                        </div>
                    </div>
                    @endif

                    @if($school->description)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Description</h5>
                            <p class="text-muted">{{ $school->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['students_count'] }}</h3>
                            <p>Students</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <a href="{{ route('students.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['teachers_count'] }}</h3>
                            <p>Teachers</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <a href="{{ route('teachers.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['grades_count'] }}</h3>
                            <p>Grades/Classes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <a href="{{ route('grades.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['subjects_count'] }}</h3>
                            <p>Subjects</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('subjects.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop