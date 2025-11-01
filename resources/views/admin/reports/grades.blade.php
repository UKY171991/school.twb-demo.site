@extends('layouts.admin')

@section('title', 'Grade Reports')
@section('page-title', 'Grade Reports')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Grades</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Grades</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.grades') }}" method="GET">
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
                                <label for="subject_id">Subject</label>
                                <select class="form-control select2" name="subject_id">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="exam_type">Exam Type</label>
                                <select class="form-control" name="exam_type">
                                    <option value="">All</option>
                                    <option value="quiz" {{ request('exam_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                    <option value="midterm" {{ request('exam_type') == 'midterm' ? 'selected' : '' }}>Midterm</option>
                                    <option value="final" {{ request('exam_type') == 'final' ? 'selected' : '' }}>Final</option>
                                    <option value="assignment" {{ request('exam_type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                                    <option value="project" {{ request('exam_type') == 'project' ? 'selected' : '' }}>Project</option>
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
                <h3 class="card-title">Grade List</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.reports.exportGrades', request()->query()) }}" class="btn btn-success btn-sm">
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
                                <th>Subject</th>
                                <th>Class</th>
                                <th>Exam</th>
                                <th>Marks</th>
                                <th>Grade</th>
                                <th>Exam Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grades as $grade)
                                <tr>
                                    <td>{{ $grade->student->user->name }}</td>
                                    <td>{{ $grade->subject->name }}</td>
                                    <td>{{ $grade->classModel->name }}</td>
                                    <td>{{ ucfirst($grade->exam_type) }}</td>
                                    <td>{{ $grade->marks_obtained }} / {{ $grade->total_marks }}</td>
                                    <td><span class="badge badge-primary">{{ $grade->grade }}</span></td>
                                    <td>{{ $grade->exam_date->format('d M, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No grades found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $grades->appends(request()->query())->links() }}
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
