@extends('adminlte::page')

@section('title', 'Marks/Exams')

@section('content_header')
    <h1>Marks & Exam Management</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Exam Results</h3>
            <div class="card-tools">
                <a href="{{ route('marks.create') }}" class="btn btn-primary btn-sm">Add Marks</a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form action="{{ route('marks.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grade_id">Filter by Grade</label>
                            <select name="grade_id" id="grade_id" class="form-control">
                                <option value="">All Grades</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }} @if($grade->section) - {{ $grade->section }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exam_type">Filter by Exam Type</label>
                            <select name="exam_type" id="exam_type" class="form-control">
                                <option value="">All Types</option>
                                <option value="Midterm" {{ request('exam_type') == 'Midterm' ? 'selected' : '' }}>Midterm</option>
                                <option value="Final" {{ request('exam_type') == 'Final' ? 'selected' : '' }}>Final</option>
                                <option value="Quiz" {{ request('exam_type') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                <option value="Assignment" {{ request('exam_type') == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-info btn-block">Filter</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Grade</th>
                        <th>Subject</th>
                        <th>Exam Type</th>
                        <th>Marks</th>
                        <th>Percentage</th>
                        <th>Exam Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marks as $mark)
                    <tr>
                        <td>{{ $mark->id }}</td>
                        <td>{{ $mark->student->name }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ $mark->student->grade->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $mark->subject->name }}</td>
                        <td><span class="badge badge-primary">{{ $mark->exam_type }}</span></td>
                        <td>{{ $mark->mark_obtained }} / {{ $mark->total_marks }}</td>
                        <td>
                            @php
                                $percentage = ($mark->mark_obtained / $mark->total_marks) * 100;
                            @endphp
                            <span class="badge 
                                @if($percentage >= 90) badge-success
                                @elseif($percentage >= 75) badge-info
                                @elseif($percentage >= 60) badge-warning
                                @else badge-danger
                                @endif
                            ">
                                {{ number_format($percentage, 2) }}%
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($mark->exam_date)->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('marks.edit', $mark->id) }}" class="btn btn-info btn-sm">Edit</a>
                            <form action="{{ route('marks.destroy', $mark->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No marks found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
