@extends('layouts.admin')

@section('title', 'Attendance Reports')
@section('page-title', 'Attendance Reports')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Attendance</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Attendance</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.attendance') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="class_id">Class</label>
                                <select class="form-control select2" name="class_id">
                                    <option value="">All Classes</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status">
                                    <option value="">All</option>
                                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Excused</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_from">Date From</label>
                                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_to">Date To</label>
                                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Attendance Records</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.reports.exportAttendance', request()->query()) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->student->user->name }}</td>
                                    <td>{{ $attendance->classModel->name }}</td>
                                    <td>{{ $attendance->date->format('d M, Y') }}</td>
                                    <td>
                                        <span class="badge 
                                            @switch($attendance->status)
                                                @case('present') badge-success @break
                                                @case('absent') badge-danger @break
                                                @case('late') badge-warning @break
                                                @case('excused') badge-info @break
                                            @endswitch
                                        ">{{ ucfirst($attendance->status) }}</span>
                                    </td>
                                    <td>{{ $attendance->remarks }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No attendance records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $attendances->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
