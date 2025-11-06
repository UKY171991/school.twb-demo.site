@extends('layouts.student')

@section('title', 'My Grades')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Grades</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.academic.index') }}">Academic Progress</a></li>
                        <li class="breadcrumb-item active">Grades</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-filter mr-2"></i>
                            Filter Grades
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('student.academic.grades') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="subject_id">Subject</label>
                                        <select class="form-control" id="subject_id" name="subject_id">
                                            <option value="">All Subjects</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_from">From Date</label>
                                        <input type="date" class="form-control" id="date_from" name="date_from" 
                                               value="{{ request('date_from', now()->subMonths(6)->format('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_to">To Date</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to" 
                                               value="{{ request('date_to', now()->format('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search mr-2"></i>
                                                Filter
                                            </button>
                                            <a href="{{ route('student.academic.grades') }}" class="btn btn-secondary">
                                                <i class="fas fa-times mr-2"></i>
                                                Clear
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Performance Overview -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Subject Performance Overview
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(count($gradeAnalytics['subject_performance']) > 0)
                            <div class="row">
                                @foreach($gradeAnalytics['subject_performance'] as $subject)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-{{ $subject['average'] >= 80 ? 'success' : ($subject['average'] >= 60 ? 'warning' : 'danger') }}">
                                                <i class="fas fa-book"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">{{ $subject['subject_name'] }}</span>
                                                <span class="info-box-number">{{ $subject['average'] }}%</span>
                                                <div class="progress">
                                                    <div class="progress-bar bg-{{ $subject['average'] >= 80 ? 'success' : ($subject['average'] >= 60 ? 'warning' : 'danger') }}" 
                                                         style="width: {{ $subject['average'] }}%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    {{ $subject['count'] }} exams | 
                                                    <i class="fas fa-{{ $subject['trend'] === 'improving' ? 'arrow-up text-success' : ($subject['trend'] === 'declining' ? 'arrow-down text-danger' : 'minus text-muted') }}"></i>
                                                    {{ ucfirst($subject['trend']) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <p>No grade data available for the selected period</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Trends Chart -->
        @if(count($gradeAnalytics['grade_trends']) > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-line-chart mr-2"></i>
                            Grade Trends Over Time
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="gradeTrendsChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Detailed Grades Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-2"></i>
                            Detailed Grade History
                        </h3>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-success" onclick="exportGrades()">
                                <i class="fas fa-download mr-2"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($grades->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="gradesTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Subject</th>
                                            <th>Exam Type</th>
                                            <th>Marks</th>
                                            <th>Percentage</th>
                                            <th>Grade</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grades as $grade)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($grade->exam_date)->format('M d, Y') }}</td>
                                                <td>
                                                    <strong>{{ $grade->subject->name ?? 'Unknown' }}</strong>
                                                    <br><small class="text-muted">{{ $grade->subject->code ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">{{ ucfirst($grade->exam_type) }}</span>
                                                </td>
                                                <td>{{ $grade->marks_obtained }}/{{ $grade->total_marks }}</td>
                                                <td>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-{{ $grade->percentage >= 80 ? 'success' : ($grade->percentage >= 60 ? 'warning' : 'danger') }}" 
                                                             style="width: {{ $grade->percentage }}%"></div>
                                                    </div>
                                                    <small>{{ $grade->percentage }}%</small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $grade->percentage >= 90 ? 'success' : ($grade->percentage >= 80 ? 'primary' : ($grade->percentage >= 70 ? 'warning' : ($grade->percentage >= 60 ? 'secondary' : 'danger'))) }}">
                                                        @if($grade->percentage >= 90) A
                                                        @elseif($grade->percentage >= 80) B
                                                        @elseif($grade->percentage >= 70) C
                                                        @elseif($grade->percentage >= 60) D
                                                        @else F
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($grade->percentage >= 60)
                                                        <span class="badge badge-success">Pass</span>
                                                    @else
                                                        <span class="badge badge-danger">Fail</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $grades->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-star fa-2x mb-2"></i>
                                <p>No grades found for the selected criteria</p>
                                <a href="{{ route('student.academic.grades') }}" class="btn btn-primary">
                                    View All Grades
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Statistics -->
        @if($grades->count() > 0)
        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-calculator"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Exams</span>
                        <span class="info-box-number">{{ $grades->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success">
                        <i class="fas fa-chart-line"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Average Grade</span>
                        <span class="info-box-number">{{ round($grades->avg('percentage'), 1) }}%</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning">
                        <i class="fas fa-trophy"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Highest Grade</span>
                        <span class="info-box-number">{{ $grades->max('percentage') }}%</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-danger">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pass Rate</span>
                        <span class="info-box-number">{{ round(($grades->where('percentage', '>=', 60)->count() / $grades->count()) * 100, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
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
        
        .progress-sm {
            height: 10px;
        }
        
        #gradesTable th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .table td {
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#gradesTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "searching": false,
        "paging": false,
        "info": false,
        "ordering": true,
        "order": [[ 0, "desc" ]]
    });

    @if(count($gradeAnalytics['grade_trends']) > 0)
    // Create grade trends chart
    const ctx = document.getElementById('gradeTrendsChart').getContext('2d');
    const gradeTrends = @json($gradeAnalytics['grade_trends']);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: Object.keys(gradeTrends),
            datasets: [{
                label: 'Average Grade (%)',
                data: Object.values(gradeTrends),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
    @endif
});

function exportGrades() {
    // AJAX call to export grades
    const params = new URLSearchParams(window.location.search);
    const exportUrl = '{{ route("student.academic.export-grades") }}?' + params.toString();
    
    toastr.info('Export functionality will be implemented');
    // window.open(exportUrl, '_blank');
}
</script>
@stop