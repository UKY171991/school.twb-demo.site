@extends('layouts.admin')

@section('title', 'Student Reports')
@section('page-title', 'Student Reports')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Students</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Students</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.students') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="school_id">School</label>
                                <select class="form-control select2" name="school_id">
                                    <option value="">All Schools</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" name="gender">
                                    <option value="">All</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status">
                                    <option value="">All</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
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
                <h3 class="card-title">Student List</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.reports.exportStudents', request()->query()) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>School</th>
                                <th>Class</th>
                                <th>Gender</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td>{{ $student->student_id }}</td>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->user->email }}</td>
                                    <td>{{ $student->school->name }}</td>
                                    <td>{{ $student->classModel->name }}</td>
                                    <td>{{ ucfirst($student->gender) }}</td>
                                    <td>
                                        <span class="badge {{ $student->is_active ? 'badge-success' : 'badge-danger' }}">
                                            {{ $student->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No students found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $students->appends(request()->query())->links() }}
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
