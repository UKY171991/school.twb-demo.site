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
                <div class="btn-group">
                    <a href="{{ route('marksheets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Marksheet
                    </a>
                    <a href="{{ route('marksheets.search') }}" class="btn btn-info">
                        <i class="fas fa-search"></i> Advanced Search
                    </a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-cogs"></i> Actions
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('marksheets.recalculate-positions') }}" 
                               onclick="return confirm('This will recalculate class positions for all marksheets. Continue?')">
                                <i class="fas fa-calculator"></i> Recalculate Positions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Quick Search Form -->
        <div class="row mb-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('marksheets.search') }}" class="form-inline">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <input type="text" name="roll_number" class="form-control" placeholder="Quick search by roll number">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="ml-2">
                        <select name="exam_type_id" class="form-control form-control-sm" style="width: 150px;">
                            <option value="">All Exam Types</option>
                            @foreach(\App\Models\ExamType::getActiveTypes() as $examType)
                                <option value="{{ $examType->id }}">{{ $examType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ml-2">
                        <select name="result" class="form-control form-control-sm" style="width: 100px;">
                            <option value="">All Results</option>
                            <option value="PASS">Pass</option>
                            <option value="FAIL">Fail</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

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
                                    <div class="text-center">
                                        <span class="badge badge-lg badge-{{ $marksheet->class_position <= 3 ? 'warning' : 'info' }}">
                                            #{{ $marksheet->class_position }}
                                        </span>
                                        @if($marksheet->total_students)
                                            <br><small class="text-muted">of {{ $marksheet->total_students }}</small>
                                        @endif
                                    </div>
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
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('marksheets.print', $marksheet) }}" target="_blank">
                                                <i class="fas fa-file-alt"></i> All Exams
                                            </a>
                                            <a class="dropdown-item" href="{{ route('marksheets.print-single', $marksheet) }}" target="_blank">
                                                <i class="fas fa-file"></i> Single Exam
                                            </a>
                                        </div>
                                    </div>
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
                            <td colspan="11" class="text-center">No marksheets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $marksheets->links() }}
    </div>
</div>
@stop