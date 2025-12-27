@extends('adminlte::page')

@section('title', 'Marksheet Details')

@section('content_header')
    <h1>Marksheet Details</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">{{ $marksheet->student->name }} - {{ $marksheet->exam_name }}</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('marksheets.print', $marksheet) }}" class="btn btn-secondary" target="_blank">
                    <i class="fas fa-print"></i> Print
                </a>
                <a href="{{ route('marksheets.edit', $marksheet) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('marksheets.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Student Information</h5>
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $marksheet->student->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Roll Number:</strong></td>
                        <td>{{ $marksheet->student->roll_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Class:</strong></td>
                        <td>{{ $marksheet->class }}-{{ $marksheet->section }}</td>
                    </tr>
                    <tr>
                        <td><strong>Father's Name:</strong></td>
                        <td>{{ $marksheet->student->father_name }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Exam Information</h5>
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Exam Name:</strong></td>
                        <td>{{ $marksheet->exam_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Exam Type:</strong></td>
                        <td>
                            @if($marksheet->examType)
                                {{ $marksheet->examType->name }} ({{ $marksheet->examType->code }})
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Exam Date:</strong></td>
                        <td>{{ $marksheet->exam_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Academic Year:</strong></td>
                        <td>{{ $marksheet->academic_year }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <h5>Subject-wise Marks</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Subject Code</th>
                        <th>Max Marks</th>
                        <th>Obtained Marks</th>
                        <th>Grade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marksheet->marks as $mark)
                        <tr>
                            <td>{{ $mark->subject->name }}</td>
                            <td>{{ $mark->subject->code }}</td>
                            <td>{{ $mark->subject->max_marks }}</td>
                            <td>{{ $mark->obtained_marks }}</td>
                            <td>
                                <span class="badge badge-{{ $mark->grade == 'F' ? 'danger' : 'success' }}">
                                    {{ $mark->grade }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $mark->isPassed() ? 'success' : 'danger' }}">
                                    {{ $mark->isPassed() ? 'PASS' : 'FAIL' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <th colspan="2">Total</th>
                        <th>{{ $marksheet->total_marks }}</th>
                        <th>{{ $marksheet->obtained_marks }}</th>
                        <th>
                            <span class="badge badge-{{ $marksheet->grade == 'F' ? 'danger' : 'success' }} badge-lg">
                                {{ $marksheet->grade }}
                            </span>
                        </th>
                        <th>
                            <span class="badge badge-{{ $marksheet->result == 'PASS' ? 'success' : 'danger' }} badge-lg">
                                {{ $marksheet->result }}
                            </span>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Result Summary</h5>
                    <p><strong>Total Marks:</strong> {{ $marksheet->obtained_marks }} / {{ $marksheet->total_marks }}</p>
                    <p><strong>Percentage:</strong> {{ $marksheet->percentage }}%</p>
                    <p><strong>Overall Grade:</strong> {{ $marksheet->grade }}</p>
                    @if($marksheet->class_position)
                        <p><strong>Class Position:</strong> {{ $marksheet->class_position }}
                            @if($marksheet->total_students)
                                out of {{ $marksheet->total_students }} students
                            @endif
                        </p>
                    @endif
                    <p><strong>Result:</strong> 
                        <span class="badge badge-{{ $marksheet->result == 'PASS' ? 'success' : 'danger' }}">
                            {{ $marksheet->result }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop