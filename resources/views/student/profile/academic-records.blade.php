@extends('layouts.student')

@section('title', 'Academic Records')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Academic Records</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.profile.show') }}">Profile</a></li>
                    <li class="breadcrumb-item active">Academic Records</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Student Information Header -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <img src="{{ $student->photo_url }}" 
                             alt="Student Photo" 
                             class="img-fluid img-circle"
                             style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                    <div class="col-md-6">
                        <h4 class="mb-1">{{ $student->full_name }}</h4>
                        <p class="mb-1"><strong>Student ID:</strong> {{ $student->student_id }}</p>
                        <p class="mb-0"><strong>Class:</strong> {{ $student->classModel->full_name ?? 'Not Assigned' }}</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <button class="btn btn-primary" onclick="printTranscript()">
                            <i class="fas fa-print mr-1"></i> Print Transcript
                        </button>
                        <button class="btn btn-success" onclick="exportTranscript()">
                            <i class="fas fa-download mr-1"></i> Export PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Academic Transcript -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            Academic Transcript
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(count($academicRecords['transcript']) > 0)
                            @foreach($academicRecords['transcript'] as $subject)
                                <div class="subject-record mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="mb-0">
                                            {{ $subject['subject_name'] }}
                                            <small class="text-muted">({{ $subject['subject_code'] }})</small>
                                        </h5>
                                        <div>
                                            <span class="badge badge-lg badge-{{ $subject['average_percentage'] >= 80 ? 'success' : ($subject['average_percentage'] >= 60 ? 'warning' : 'danger') }}">
                                                {{ $subject['grade_letter'] }} - {{ $subject['average_percentage'] }}%
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <small class="text-muted">Total Assessments</small>
                                            <div class="font-weight-bold">{{ $subject['total_assessments'] }}</div>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">Highest Grade</small>
                                            <div class="font-weight-bold text-success">{{ $subject['highest_grade'] }}%</div>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">Lowest Grade</small>
                                            <div class="font-weight-bold text-danger">{{ $subject['lowest_grade'] }}%</div>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">Latest Grade</small>
                                            <div class="font-weight-bold">{{ $subject['latest_grade'] }}%</div>
                                        </div>
                                    </div>

                                    <!-- Assessment Details -->
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Assessment Type</th>
                                                    <th>Date</th>
                                                    <th>Score</th>
                                                    <th>Percentage</th>
                                                    <th>Teacher</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(array_slice($subject['assessments'], 0, 5) as $assessment)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-info">{{ ucfirst($assessment['exam_type']) }}</span>
                                                        </td>
                                                        <td>{{ Carbon\Carbon::parse($assessment['exam_date'])->format('M j, Y') }}</td>
                                                        <td>{{ $assessment['marks_obtained'] }}/{{ $assessment['total_marks'] }}</td>
                                                        <td>
                                                            <span class="badge badge-{{ $assessment['percentage'] >= 80 ? 'success' : ($assessment['percentage'] >= 60 ? 'warning' : 'danger') }}">
                                                                {{ $assessment['percentage'] }}%
                                                            </span>
                                                        </td>
                                                        <td>{{ $assessment['teacher'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    @if(count($subject['assessments']) > 5)
                                        <div class="text-center">
                                            <button class="btn btn-sm btn-outline-primary" onclick="showAllAssessments('{{ $subject['subject_name'] }}')">
                                                Show All {{ count($subject['assessments']) }} Assessments
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <hr>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Academic Records Found</h5>
                                <p class="text-muted">Your academic records will appear here once grades are entered.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Subject Performance Analysis -->
                @if(count($academicRecords['subject_performance']) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Subject Performance Analysis
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Assessments</th>
                                            <th>Average</th>
                                            <th>Grade</th>
                                            <th>Performance</th>
                                            <th>Trend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($academicRecords['subject_performance'] as $performance)
                                            <tr>
                                                <td>
                                                    <strong>{{ $performance['subject_name'] }}</strong>
                                                    @if($performance['strengths'])
                                                        <i class="fas fa-star text-warning ml-1" title="Strength"></i>
                                                    @endif
                                                    @if($performance['needs_improvement'])
                                                        <i class="fas fa-exclamation-triangle text-danger ml-1" title="Needs Improvement"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $performance['total_assessments'] }}</td>
                                                <td>{{ $performance['average_percentage'] }}%</td>
                                                <td>
                                                    <span class="badge badge-{{ $performance['average_percentage'] >= 80 ? 'success' : ($performance['average_percentage'] >= 60 ? 'warning' : 'danger') }}">
                                                        {{ $performance['grade_letter'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $performance['performance_level'] === 'Excellent' ? 'success' : ($performance['performance_level'] === 'Good' ? 'primary' : 'warning') }}">
                                                        {{ $performance['performance_level'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($performance['trend'] === 'improving')
                                                        <i class="fas fa-arrow-up text-success" title="Improving"></i>
                                                    @elseif($performance['trend'] === 'declining')
                                                        <i class="fas fa-arrow-down text-danger" title="Declining"></i>
                                                    @else
                                                        <i class="fas fa-minus text-muted" title="Stable"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Academic Achievements -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-trophy mr-2"></i>
                            Academic Achievements
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(count($academicRecords['achievements']) > 0)
                            @foreach(array_slice($academicRecords['achievements'], 0, 10) as $achievement)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mr-3">
                                        <i class="{{ $achievement['icon'] }} text-{{ $achievement['color'] }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $achievement['title'] }}</h6>
                                        <p class="mb-0 text-muted small">{{ $achievement['description'] }}</p>
                                        <small class="text-muted">{{ Carbon\Carbon::parse($achievement['date'])->format('M j, Y') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-trophy fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No achievements yet. Keep working hard!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Attendance Summary -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Attendance History
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(count($academicRecords['attendance_history']) > 0)
                            @foreach(array_slice($academicRecords['attendance_history'], 0, 6, true) as $month => $stats)
                                <div class="progress-group">
                                    <span class="progress-text">{{ Carbon\Carbon::parse($month . '-01')->format('M Y') }}</span>
                                    <span class="float-right">
                                        <b>{{ $stats['present'] }}/{{ $stats['total'] }}</b>
                                    </span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-{{ $stats['percentage'] >= 90 ? 'success' : ($stats['percentage'] >= 75 ? 'warning' : 'danger') }}" 
                                             style="width: {{ $stats['percentage'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No attendance records available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-2"></i>
                            Quick Statistics
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-success">
                                        <i class="fas fa-caret-up"></i> 
                                        {{ count($academicRecords['transcript']) }}
                                    </span>
                                    <h5 class="description-header">Subjects</h5>
                                    <span class="description-text">ENROLLED</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="description-block">
                                    <span class="description-percentage text-info">
                                        <i class="fas fa-caret-up"></i> 
                                        {{ collect($academicRecords['transcript'])->sum('total_assessments') }}
                                    </span>
                                    <h5 class="description-header">Assessments</h5>
                                    <span class="description-text">COMPLETED</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-warning">
                                        {{ count($academicRecords['achievements']) }}
                                    </span>
                                    <h5 class="description-header">Achievements</h5>
                                    <span class="description-text">EARNED</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="description-block">
                                    <span class="description-percentage text-primary">
                                        @php
                                            $overallAvg = collect($academicRecords['transcript'])->avg('average_percentage');
                                        @endphp
                                        {{ number_format($overallAvg, 1) }}%
                                    </span>
                                    <h5 class="description-header">Overall</h5>
                                    <span class="description-text">AVERAGE</span>
                                </div>
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
function printTranscript() {
    window.print();
}

function exportTranscript() {
    // In a real implementation, this would generate and download a PDF
    toastr.info('PDF export functionality will be implemented');
}

function showAllAssessments(subjectName) {
    // In a real implementation, this would show a modal with all assessments
    toastr.info('Detailed assessment view for ' + subjectName + ' will be implemented');
}

$(document).ready(function() {
    // Initialize tooltips
    $('[title]').tooltip();
});
</script>
@endpush

@push('styles')
<style>
@media print {
    .content-header,
    .btn,
    .card-header .card-tools,
    .sidebar,
    .main-header,
    .main-footer {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .subject-record {
        page-break-inside: avoid;
    }
}

.subject-record {
    border-left: 4px solid #007bff;
    padding-left: 15px;
}

.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.progress-group {
    margin-bottom: 1rem;
}

.description-block {
    text-align: center;
}

.description-percentage {
    font-size: 1.1rem;
    font-weight: bold;
}

.description-header {
    font-size: 1rem;
    margin: 0.5rem 0;
}

.description-text {
    font-size: 0.8rem;
    color: #6c757d;
}
</style>
@endpush