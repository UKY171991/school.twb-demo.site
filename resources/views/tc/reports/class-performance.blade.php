@extends('layouts.tc')

@section('title', 'Class Performance Report')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Class Performance Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.reports') }}">Reports</a></li>
                        <li class="breadcrumb-item active">Class Performance</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-2"></i>
                            {{ $reportData['class']->full_name }} Performance Report
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-info">
                                {{ $reportData['period']['from'] }} to {{ $reportData['period']['to'] }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Report content will be displayed here -->
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="gradeDistributionChart" height="300"></canvas>
                            </div>
                            <div class="col-md-6">
                                <h5>Summary Statistics</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td>Total Students:</td>
                                        <td><strong>{{ $reportData['summary']['total_students'] }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Total Grades:</td>
                                        <td><strong>{{ $reportData['summary']['total_grades'] }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Average Grade:</td>
                                        <td><strong>{{ number_format($reportData['summary']['average_grade'], 1) }}%</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Passing Rate:</td>
                                        <td><strong>{{ number_format($reportData['summary']['passing_rate'], 1) }}%</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Create grade distribution chart
    const ctx = document.getElementById('gradeDistributionChart').getContext('2d');
    const gradeDistribution = @json($reportData['grade_distribution']);
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(gradeDistribution),
            datasets: [{
                data: Object.values(gradeDistribution).map(d => d.count),
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
            legend: {
                position: 'bottom'
            }
        }
    });
});
</script>
@stop