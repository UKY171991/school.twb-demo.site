@extends('adminlte::page')

@section('title', 'Marksheets')

@section('content_header')
    <h1>Marksheets</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">All Marksheets</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('marksheets.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Marksheet
                </a>
                <a href="{{ route('marksheets.search') }}" class="btn btn-info">
                    <i class="fas fa-search"></i> Search by Roll Number
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Roll Number</th>
                        <th>Exam Name</th>
                        <th>Exam Type</th>
                        <th>Class</th>
                        <th>Percentage</th>
                        <th>Grade</th>
                        <th>Position</th>
                        <th>Result</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marksheets as $marksheet)
                        <tr>
                            <td>{{ $marksheet->id }}</td>
                            <td>{{ $marksheet->student->name }}</td>
                            <td>{{ $marksheet->student->roll_number }}</td>
                            <td>{{ $marksheet->exam_name }}</td>
                            <td>
                                @if($marksheet->examType)
                                    <span class="badge badge-primary">{{ $marksheet->examType->name }}</span>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>{{ $marksheet->class }}-{{ $marksheet->section }}</td>
                            <td>{{ $marksheet->percentage }}%</td>
                            <td>
                                <span class="badge badge-{{ $marksheet->grade == 'F' ? 'danger' : 'success' }}">
                                    {{ $marksheet->grade }}
                                </span>
                            </td>
                            <td>
                                @if($marksheet->class_position)
                                    <strong>{{ $marksheet->class_position }}</strong>
                                    @if($marksheet->total_students)
                                        / {{ $marksheet->total_students }}
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $marksheet->result == 'PASS' ? 'success' : 'danger' }}">
                                    {{ $marksheet->result }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('marksheets.show', $marksheet) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('marksheets.print', $marksheet) }}" class="btn btn-sm btn-secondary" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <a href="{{ route('marksheets.edit', $marksheet) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('marksheets.destroy', $marksheet) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No marksheets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $marksheets->links() }}
    </div>
</div>
@stop