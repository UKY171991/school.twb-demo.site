@extends('layouts.student')

@section('title', 'Academic Progress')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Academic Progress</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Academic Progress</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Performance Overview -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-2"></i>
                            Performance Analytics
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-{{ $academicData['performance_analytics']['overall_performance'] === 'excellent' ? 'success' : ($academicData['performance_analytics']['overall_performance'] === 'good' ? 'primary' : ($academicData['performance_analytics']['overall_performance'] === 'satisfactory' ? 'warning' : 'danger')) }}">
                                        <i class="fas fa-star"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Overall Average</span>
                                        <span class="info-box-number">{{ $academicData['performance_analytics']['overall_average'] ?? 0 }}%</span>
                                        <div class="progress">
                                            <div class="progress-bar bg-{{ $academicData['performance_analytics']['overall_performance'] === 'excellent' ? 'success' : ($academicData['performance_analytics']['overall_performance'] === 'good' ? 'primary' : ($academicData['performance_analytics']['overall_performance'] === 'satisfactory' ? 'warning' : 'danger')) }}" 
                                                 style="width: {{ $academicData['performance_analytics']['overall_average'] ?? 0 }}%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ ucfirst(str_replace('_', ' ', $academicData['performance_analytics']['overall_performance'])) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-{{ $academicData['performance_analytics']['performance_trend'] === 'improving' ? 'arrow-up' : ($academicData['performance_analytics']['performance_trend'] === 'declining' ? 'arrow-down' : 'minus') }}"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Performance Trend</span>
                                        <span class="info-box-number">{{ ucfirst($academicData['performance_analytics']['performance_trend']) }}</span>
                                        <div class="progress">
                                            <div class="progress-bar bg-{{ $academicData['performance_analytics']['performance_trend'] === 'improving' ? 'success' : ($academicData['performance_analytics']['performance_trend'] === 'declining' ? 'danger' : 'warning') }}" 
                                                 style="width: {{ $academicData['performance_analytics']['consistency_score'] ?? 50 }}%"></div>
                                        </div>
                                        <span class="progress-description">
                                            Consistency Score: {{ round($academicData['performance_analytics']['consistency_score'] ?? 0, 1) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Grade Distribution Chart -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Grade Distribution</h5>
                                <canvas id="gradeDistributionChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Attendance Overview
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="progress-group">
                                    <span class="progress-text">This Month</span>
                                    <span class="float-right"><b>{{ $academicData['attendance_summary']['monthly']['present'] }}/{{ $academicData['attendance_summary']['monthly']['total'] }}</b></span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary" style="width: {{ $academicData['attendance_summary']['monthly']['percentage'] }}%"></div>
                                    </div>
                                </div>
                                <div class="progress-group">
                                    <span class="progress-text">This Semester</span>
                                    <span class="float-right"><b>{{ $academicData['attendance_summary']['semester']['present'] }}/{{ $academicData['attendance_summary']['semester']['total'] }}</b></span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: {{ $academicData['attendance_summary']['semester']['percentage'] }}%"></div>
                                    </div>
                                </div>
                                <div class="progress-group">
                                    <span class="progress-text">This Year</span>
                                    <span class="float-right"><b>{{ $academicData['attendance_summary']['yearly']['present'] }}/{{ $academicData['attendance_summary']['yearly']['total'] }}</b></span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-warning" style="width: {{ $academicData['attendance_summary']['yearly']['percentage'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('student.academic.attendance') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-eye mr-2"></i>
                                View Detailed Attendance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Performance -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-trophy mr-2"></i>
                            Your Strengths
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(count($academicData['performance_analytics']['strengths']) > 0)
                            @foreach($academicData['performance_analytics']['strengths'] as $strength)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="font-weight-bold">{{ $strength['subject'] }}</span>
                                    <span class="badge badge-success">{{ round($strength['average'], 1) }}%</span>
                                </div>
                                <div class="progress progress-sm mb-3">
                                    <div class="progress-bar bg-success" style="width: {{ $strength['average'] }}%"></div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-star fa-2x mb-2"></i>
                                <p>Keep working hard to identify your strengths!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bullseye mr-2"></i>
                            Areas for Improvement
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(count($academicData['performance_analytics']['areas_for_improvement']) > 0)
                            @foreach($academicData['performance_analytics']['areas_for_improvement'] as $improvement)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="font-weight-bold">{{ $improvement['subject'] }}</span>
                                    <span class="badge badge-warning">{{ round($improvement['average'], 1) }}%</span>
                                </div>
                                <div class="progress progress-sm mb-3">
                                    <div class="progress-bar bg-warning" style="width: {{ $improvement['average'] }}%"></div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                                <p>Great job! No areas needing immediate attention.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Improvement Suggestions -->
        @if(count($academicData['improvement_suggestions']) > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Personalized Recommendations
                        </h3>
                    </div>
                    <div class="card-body">
                        @foreach($academicData['improvement_suggestions'] as $suggestion)
                            <div class="alert alert-{{ $suggestion['priority'] === 'high' ? 'danger' : ($suggestion['priority'] === 'medium' ? 'warning' : 'info') }}">
                                <h6>
                                    <i class="fas fa-{{ $suggestion['type'] === 'academic' ? 'book' : 'calendar-check' }} mr-2"></i>
                                    {{ $suggestion['subject'] }}
                                </h6>
                                <p class="mb-0">{{ $suggestion['message'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Academic Timeline -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-2"></i>
                            Recent Academic Activity
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('student.academic.grades') }}" class="btn btn-sm btn-primary">
                                View All Grades
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(count($academicData['academic_timeline']) > 0)
                            <div class="timeline">
                                @foreach($academicData['academic_timeline'] as $event)
                                    <div class="time-label">
                                        <span class="bg-{{ $event['color'] }}">{{ \Carbon\Carbon::parse($event['date'])->format('M d') }}</span>
                                    </div>
                                    <div>
                                        <i class="{{ $event['icon'] }} bg-{{ $event['color'] }}"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($event['date'])->diffForHumans() }}</span>
                                            <h3 class="timeline-header">{{ $event['title'] }}</h3>
                                            <div class="timeline-body">
                                                {{ $event['description'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-history fa-2x mb-2"></i>
                                <p>No recent academic activity to display</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-3">
                <a href="{{ route('student.academic.grades') }}" class="btn btn-primary btn-block">
                    <i class="fas fa-star mr-2"></i>
                    View All Grades
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('student.academic.attendance') }}" class="btn btn-success btn-block">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Attendance Details
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('student.academic.progress-reports') }}" class="btn btn-warning btn-block">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Progress Reports
                </a>
            </div>
            <div class="col-md-3">
                <button class="btn btn-info btn-block" onclick="exportAcademicData()">
                    <i class="fas fa-download mr-2"></i>
                    Export Data
                </button>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .info-box {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            border-radius: .25rem;
            background-color: #fff;
            display: flex;
            margin-bottom: 1rem;
            min-height: 80px;
            padding: .5rem;
            position: relative;
            width: 100%;
        }
        
        .progress-group {
            margin-bottom: 1rem;
        }
        
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ddd;
            left: 31px;
            margin: 0;
            border-radius: 2px;
        }
        
        .timeline > .time-label > span {
            font-weight: 600;
            color: #fff;
            font-size: 12px;
            padding: 5px 10px;
            display: inline-block;
            border-radius: 4px;
        }
        
        .timeline-item {
            background: #fff;
            border-radius: 3px;
            width: calc(100% - 45px);
            margin-left: 45px;
            margin-top: 10px;
            color: #444;
            padding: 10px;
            position: relative;
            border-left: 3px solid #007bff;
        }
        
        .timeline-header {
            margin: 0;
            color: #555;
            font-size: 16px;
            font-weight: 600;
        }
        
        .timeline-body {
            padding-top: 10px;
        }
        
        .time {
            color: #999;
            float: right;
            font-size: 12px;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Create grade distribution chart
    const ctx = document.getElementById('gradeDistributionChart').getContext('2d');
    const gradeDistribution = @json($academicData['performance_analytics']['grade_distribution']);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(gradeDistribution),
            datasets: [{
                label: 'Number of Grades',
                data: Object.values(gradeDistribution),
                backgroundColor: [
                    '#28a745', // A - Green
                    '#17a2b8', // B - Cyan
                    '#ffc107', // C - Yellow
                    '#fd7e14', // D - Orange
                    '#dc3545'  // F - Red
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});

function exportAcademicData() {
    // AJAX call to export academic data
    toastr.info('Export functionality will be implemented');
}
</script>
@stop