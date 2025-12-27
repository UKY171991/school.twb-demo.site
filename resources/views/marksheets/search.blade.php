@extends('adminlte::page')

@section('title', 'Search Results')

@section('content_header')
    <h1>Search Student Results</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Search by Roll Number</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('marksheets.search') }}">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="roll_number">Roll Number</label>
                        <input type="text" name="roll_number" id="roll_number" class="form-control" 
                               value="{{ request('roll_number') }}" placeholder="Enter student roll number" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(isset($student))
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h4>Student Information</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <td>{{ $student->name }}</td>
                                <th>Roll Number</th>
                                <td>{{ $student->roll_number }}</td>
                            </tr>
                            <tr>
                                <th>Class</th>
                                <td>{{ $student->class }}-{{ $student->section }}</td>
                                <th>Father's Name</th>
                                <td>{{ $student->father_name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            @if($marksheets->count() > 0)
                <h4>Marksheets</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Exam Name</th>
                                <th>Exam Date</th>
                                <th>Academic Year</th>
                                <th>Total Marks</th>
                                <th>Obtained Marks</th>
                                <th>Percentage</th>
                                <th>Grade</th>
                                <th>Result</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($marksheets as $marksheet)
                                <tr>
                                    <td>{{ $marksheet->exam_name }}</td>
                                    <td>{{ $marksheet->exam_date->format('d M Y') }}</td>
                                    <td>{{ $marksheet->academic_year }}</td>
                                    <td>{{ $marksheet->total_marks }}</td>
                                    <td>{{ $marksheet->obtained_marks }}</td>
                                    <td>{{ $marksheet->percentage }}%</td>
                                    <td>
                                        <span class="badge badge-{{ $marksheet->grade == 'F' ? 'danger' : 'success' }}">
                                            {{ $marksheet->grade }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $marksheet->result == 'PASS' ? 'success' : 'danger' }}">
                                            {{ $marksheet->result }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('marksheets.show', $marksheet) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('marksheets.print', $marksheet) }}" class="btn btn-sm btn-secondary" target="_blank">
                                                <i class="fas fa-print"></i> Print
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Subject-wise details for the latest marksheet -->
                @if($marksheets->first())
                    @php $latestMarksheet = $marksheets->first(); @endphp
                    <h5>Latest Exam Details ({{ $latestMarksheet->exam_name }})</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Max Marks</th>
                                    <th>Obtained Marks</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestMarksheet->marks as $mark)
                                    <tr>
                                        <td>{{ $mark->subject->name }}</td>
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
                        </table>
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> No Results Found</h5>
                    No marksheets found for this student.
                </div>
            @endif
        @endif
    </div>
</div>
@stop