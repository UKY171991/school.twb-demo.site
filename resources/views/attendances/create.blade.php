@extends('adminlte::page')

@section('title', 'Mark Attendance')

@section('content_header')
    <h1>Mark Attendance</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Select Grade and Mark Attendance</h3>
        </div>
        <div class="card-body">
            <!-- Grade Selection Form -->
            <form id="gradeForm" method="GET" action="{{ route('attendances.create') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="grade_id">Select Grade/Class <span class="text-danger">*</span></label>
                            <select name="grade_id" id="grade_id" class="form-control" required>
                                <option value="">Select Grade</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }} @if($grade->section) - {{ $grade->section }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-info btn-block">Load Students</button>
                    </div>
                </div>
            </form>

            @if(count($students) > 0)
                <!-- Attendance Form -->
                <hr>
                <form action="{{ route('attendances.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="attendance_date">Attendance Date <span class="text-danger">*</span></label>
                                <input type="date" name="attendance_date" id="attendance_date" 
                                       class="form-control @error('attendance_date') is-invalid @enderror" 
                                       value="{{ old('attendance_date', date('Y-m-d')) }}" required>
                                @error('attendance_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Status</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>
                                    {{ $student->name }}
                                    <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                                </td>
                                <td>
                                    <select name="status[{{ $student->id }}]" class="form-control" required>
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="late">Late</option>
                                        <option value="excused">Excused</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="note[{{ $student->id }}]" class="form-control" placeholder="Optional note">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit Attendance</button>
                        <a href="{{ route('attendances.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            @elseif(request('grade_id'))
                <div class="alert alert-warning mt-3">
                    No students found in the selected grade.
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
