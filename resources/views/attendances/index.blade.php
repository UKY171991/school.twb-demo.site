@extends('layouts.app')

@section('title', 'Attendance')

@section('content_header')
    <h1>Attendance Management</h1>
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
            <h3 class="card-title">Attendance Records</h3>
            <div class="card-tools">
                <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-sm">Mark Attendance</a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form action="{{ route('attendances.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date">Filter by Date</label>
                            <input type="date" name="date" id="date" class="form-control" 
                                   value="{{ request('date', date('Y-m-d')) }}">
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
                        <th>Student Name</th>
                        <th>Grade/Class</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->id }}</td>
                        <td>{{ $attendance->student->name }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ $attendance->student->grade->name ?? 'N/A' }}
                                @if($attendance->student->grade && $attendance->student->grade->section)
                                    - {{ $attendance->student->grade->section }}
                                @endif
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</td>
                        <td>
                            @if($attendance->status == 'present')
                                <span class="badge badge-success">Present</span>
                            @elseif($attendance->status == 'absent')
                                <span class="badge badge-danger">Absent</span>
                            @elseif($attendance->status == 'late')
                                <span class="badge badge-warning">Late</span>
                            @else
                                <span class="badge badge-info">Excused</span>
                            @endif
                        </td>
                        <td>{{ $attendance->note ?? '-' }}</td>
                        <td>
                            <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-info btn-sm">Edit</a>
                            <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No attendance records found for the selected date.</td>
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
