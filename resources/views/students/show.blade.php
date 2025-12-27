@extends('adminlte::page')

@section('title', 'Student Details')

@section('content_header')
    <h1>Student Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $student->name }}</h3>
            <div class="card-tools">
                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-info btn-sm">Edit</a>
                <a href="{{ route('students.index') }}" class="btn btn-default btn-sm">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Name</th>
                            <td>{{ $student->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $student->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $student->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ ucfirst($student->gender) }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Age</th>
                            <td>
                                @if($student->date_of_birth)
                                    {{ \Carbon\Carbon::parse($student->date_of_birth)->age }} years
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Grade/Class</th>
                            <td>
                                <span class="badge badge-info">
                                    {{ $student->grade->name ?? 'N/A' }}
                                    @if($student->grade && $student->grade->section)
                                        - {{ $student->grade->section }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $student->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Registered On</th>
                            <td>{{ $student->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $student->updated_at->format('d M Y H:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>0</h3>
                    <p>Attendance Records</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>0</h3>
                    <p>Exam Results</p>
                </div>
                <div class="icon">
                    <i class="fas fa-poll"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>N/A</h3>
                    <p>Average Grade</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
