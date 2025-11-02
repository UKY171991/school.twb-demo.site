@extends('layouts.admin')

@section('title', 'Student Academic Report')
@section('page-title', 'Student Academic Report')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
<li class="breadcrumb-item active">Student Reports</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Student Academic Performance Report</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{ route('admin.reports.students', array_merge($filters, ['format' => 'pdf'])) }}" 
                           class="btn btn-sm btn-danger">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('admin.reports.students', array_merge($filters, ['format' => 'excel'])) }}" 
                           class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                        <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Report Header -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4>{{ $school->name }}</h4>
                        <p class="text-muted">
                            Generated on: {{ $report_date->format('F d, Y \a\t g:i A') }}<br>
                            @if($filters['date_from'] && $filters['date_to'])
                                Period: {{ \Carbon\Carbon::parse($filters['date_from'])->format('M d, Y') }} - 
                                        {{ \Carbon\Carbon::parse($filters['date_to'])->format('M d, Y') }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="info-box-content">
                            <span class="info-box-text">Total Students</span>
                            <span class="info-box-number">{{ $students_data->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Summary Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Average Grade</span>
                                <span class="info-box-number">
                                    {{ number_format($students_data->avg('academic_performance.average_grade'), 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-percentage"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pass Rate</span>
                                <span class="info-box-number">
                                    {{ number_format($students_data->avg('academic_performance.pass_rate'), 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-calendar-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Avg Attendance</span>
                                <span class="info-box-number">
                                    {{ number_format($students_data->avg('attendance_summary.attendance_rate'), 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-clipboard-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Assessments</span>
                                <span class="info-box-number">
                                    {{ $students_data->sum('academic_performance.total_grades') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Individual Student Reports -->
                @foreach($students_data as $data)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <img src="{{ $data['student']->photo_url }}" alt="Student Photo" 
                                 class="img-circle elevation-2" style="width: 40px; height: 40px;">
                            {{ $data['student']->full_name }}
                            @if($data['student']->class)
                                <span class="badge badge-info ml-2">{{ $data['student']->class->full_name }}</span>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Academic Performance -->
                            <div class="col-md-6">
                                <h6><i class="fas fa-chart-bar"></i> Academic Performance</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Total Assessments:</td>
                                            <td><strong>{{ $data['academic_performance']['total_grades'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Average Grade:</td>
                                            <td><strong>{{ number_format($data['academic_performance']['average_grade'], 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Highest Grade:</td>
                                            <td><strong>{{ $data['academic_performance']['highest_grade'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Lowest Grade:</td>
                                            <td><strong>{{ $data['academic_performance']['lowest_grade'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Pass Rate:</td>
                                            <td>
                                                <strong>{{ number_format($data['academic_performance']['pass_rate'], 1) }}%</strong>
                                                @if($data['academic_performance']['pass_rate'] >= 80)
                                                    <span class="badge badge-success">Excellent</span>
                                                @elseif($data['academic_performance']['pass_rate'] >= 60)
                                                    <span class="badge badge-warning">Good</span>
                                                @else
                                                    <span class="badge badge-danger">Needs Improvement</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Grade Distribution -->
                                @if($data['academic_performance']['total_grades'] > 0)
                                <div class="mt-3">
                                    <h6>Grade Distribution</h6>
                                    @foreach($data['academic_performance']['grade_distribution'] as $grade => $count)
                                        @if($count > 0)
                                        <div class="progress mb-1" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ ($count / $data['academic_performance']['total_grades']) * 100 }}%">
                                                {{ $grade }}: {{ $count }}
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <!-- Attendance Summary -->
                            <div class="col-md-6">
                                <h6><i class="fas fa-calendar-check"></i> Attendance Summary</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Total Days:</td>
                                            <td><strong>{{ $data['attendance_summary']['total_days'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Present Days:</td>
                                            <td><strong class="text-success">{{ $data['attendance_summary']['present_days'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Absent Days:</td>
                                            <td><strong class="text-danger">{{ $data['attendance_summary']['absent_days'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Late Days:</td>
                                            <td><strong class="text-warning">{{ $data['attendance_summary']['late_days'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Attendance Rate:</td>
                                            <td>
                                                <strong>{{ number_format($data['attendance_summary']['attendance_rate'], 1) }}%</strong>
                                                @if($data['attendance_summary']['attendance_rate'] >= 95)
                                                    <span class="badge badge-success">Excellent</span>
                                                @elseif($data['attendance_summary']['attendance_rate'] >= 85)
                                                    <span class="badge badge-warning">Good</span>
                                                @else
                                                    <span class="badge badge-danger">Poor</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Attendance Chart -->
                                @if($data['attendance_summary']['total_days'] > 0)
                                <div class="mt-3">
                                    <canvas id="attendance-chart-{{ $data['student']->id }}" width="200" height="100"></canvas>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($students_data->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    No student data found for the selected criteria.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Create attendance charts for each student
    @foreach($students_data as $data)
        @if($data['attendance_summary']['total_days'] > 0)
        const ctx{{ $data['student']->id }} = document.getElementById('attendance-chart-{{ $data['student']->id }}');
        new Chart(ctx{{ $data['student']->id }}, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Late'],
                datasets: [{
                    data: [
                        {{ $data['attendance_summary']['present_days'] }},
                        {{ $data['attendance_summary']['absent_days'] }},
                        {{ $data['attendance_summary']['late_days'] }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545',
                        '#ffc107'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            fontSize: 10
                        }
                    }
                }
            }
        });
        @endif
    @endforeach
});
</script>
@endpush