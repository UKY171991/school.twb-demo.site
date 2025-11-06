@extends('layouts.parent')

@section('title', $student->full_name . ' - Detailed Report')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $student->full_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('parent.children') }}">My Children</a></li>
                    <li class="breadcrumb-item active">{{ $student->full_name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Student Profile Header -->
        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ $student->photo_url }}"
                                 alt="Student profile picture"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        </div>

                        <h3 class="profile-username text-center">{{ $student->full_name }}</h3>
                        <p class="text-muted text-center">Student ID: {{ $student->student_id }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Class</b> <a class="float-right">{{ $student->classModel->full_name ?? 'Not Assigned' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>School</b> <a class="float-right">{{ $student->school->name ?? 'Unknown' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b> 
                                <span class="float-right">
                                    <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Academic Status -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-2"></i>
                            Academic Status
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="progress-group">
                            <span class="progress-text">Overall Performance</span>
                            <span class="float-right">
                                <b>{{ $monitoringData['academic_overview']['academic_status']['overall_performance'] }}</b>
                            </span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-{{ $monitoringData['academic_overview']['academic_status']['overall_performance'] === 'Excellent' ? 'success' : ($monitoringData['academic_overview']['academic_status']['overall_performance'] === 'Good' ? 'primary' : 'warning') }}" 
                                     style="width: {{ $monitoringData['academic_overview']['grade_stats']['average_grade'] }}%"></div>
                            </div>
                        </div>

                        <div class="progress-group">
                            <span class="progress-text">Attendance Rate</span>
                            <span class="float-right">
                                <b>{{ $monitoringData['academic_overview']['attendance_stats']['attendance_percentage'] }}%</b>
                            </span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-{{ $monitoringData['academic_overview']['attendance_stats']['attendance_percentage'] >= 90 ? 'success' : ($monitoringData['academic_overview']['attendance_stats']['attendance_percentage'] >= 75 ? 'warning' : 'danger') }}" 
                                     style="width: {{ $monitoringData['academic_overview']['attendance_stats']['attendance_percentage'] }}%"></div>
                            </div>
                        </div>

                        @if($monitoringData['academic_overview']['academic_status']['needs_attention'])
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Attention Required:</strong> This child may need additional academic support.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-8">
                <!-- Academic Overview -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            Academic Overview
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-book"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Subjects</span>
                                        <span class="info-box-number">{{ count($monitoringData['academic_overview']['subject_performance']) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success">
                                        <i class="fas fa-star"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Avg Grade</span>
                                        <span class="info-box-number">{{ number_format($monitoringData['academic_overview']['grade_stats']['average_grade'], 1) }}%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Attendance</span>
                                        <span class="info-box-number">{{ $monitoringData['academic_overview']['attendance_stats']['attendance_percentage'] }}%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Alerts</span>
                                        <span class="info-box-number">{{ count($monitoringData['academic_alerts']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subject Performance -->
                @if(count($monitoringData['academic_overview']['subject_performance']) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Subject Performance
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Average</th>
                                            <th>Assessments</th>
                                            <th>Trend</th>
                                            <th>Latest Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monitoringData['academic_overview']['subject_performance'] as $subject)
                                            <tr>
                                                <td><strong>{{ $subject['subject_name'] }}</strong></td>
                                                <td>
                                                    <span class="badge badge-{{ $subject['average'] >= 80 ? 'success' : ($subject['average'] >= 60 ? 'warning' : 'danger') }}">
                                                        {{ $subject['average'] }}%
                                                    </span>
                                                </td>
                                                <td>{{ $subject['count'] }}</td>
                                                <td>
                                                    @if($subject['trend'] === 'improving')
                                                        <i class="fas fa-arrow-up text-success" title="Improving"></i>
                                                    @elseif($subject['trend'] === 'declining')
                                                        <i class="fas fa-arrow-down text-danger" title="Declining"></i>
                                                    @else
                                                        <i class="fas fa-minus text-muted" title="Stable"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $subject['latest_grade'] }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Academic Alerts -->
                @if(count($monitoringData['academic_alerts']) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bell mr-2"></i>
                                Academic Alerts
                            </h3>
                        </div>
                        <div class="card-body">
                            @foreach($monitoringData['academic_alerts'] as $alert)
                                <div class="alert alert-{{ $alert['severity'] === 'high' ? 'danger' : 'warning' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-{{ $alert['type'] === 'attendance' ? 'calendar-times' : ($alert['type'] === 'grades' ? 'chart-line' : 'exclamation-triangle') }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $alert['title'] }}</h6>
                                            <p class="mb-0">{{ $alert['message'] }}</p>
                                            @if($alert['action_required'])
                                                <small class="text-muted">Action Required</small>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="badge badge-{{ $alert['severity'] === 'high' ? 'danger' : 'warning' }}">
                                                {{ ucfirst($alert['severity']) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tools mr-2"></i>
                            Detailed Analysis
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('parent.children.attendance-analysis', $student) }}" class="btn btn-info btn-block">
                                    <i class="fas fa-calendar-check mr-2"></i>
                                    Attendance Analysis
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('parent.children.grade-tracking', $student) }}" class="btn btn-success btn-block">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Grade Tracking
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <a href="{{ route('parent.children.performance-trends', $student) }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-trending-up mr-2"></i>
                                    Performance Trends
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('parent.children', $student) }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Back to Children
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[title]').tooltip();
});
</script>
@endpush