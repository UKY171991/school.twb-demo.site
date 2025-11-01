@extends('layouts.student')

@section('title', 'Student Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['subjects_enrolled'] ?? 0 }}</h3>
                <p>Subjects Enrolled</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{ route('student.subjects') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['attendance_percentage'] ?? 0 }}<sup style="font-size: 20px">%</sup></h3>
                <p>Attendance</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <a href="{{ route('student.attendance') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['average_grade'] ?? 'N/A' }}</h3>
                <p>Average Grade</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
            <a href="{{ route('student.grades') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Subjects</h3>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($subjects as $subject)
                        <li class="list-group-item">{{ $subject->name }} ({{ $subject->teacher->user->name }})</li>
                    @empty
                        <li class="list-group-item text-center">No subjects found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Grades</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Exam</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grades as $grade)
                                <tr>
                                    <td>{{ $grade->subject->name }}</td>
                                    <td>{{ ucfirst($grade->exam_type) }}</td>
                                    <td><span class="badge badge-primary">{{ $grade->grade }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No recent grades found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
