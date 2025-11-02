@extends('layouts.tc')

@section('title', 'View Grade')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">View Grade</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.grades') }}">Grades</a></li>
                        <li class="breadcrumb-item active">View</li>
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
                            <i class="fas fa-star mr-2"></i>
                            Grade Details
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('teacher.grades.edit', $grade) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit mr-1"></i>
                                Edit Grade
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Student Information</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $grade->student->full_name ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Student ID:</strong></td>
                                        <td>{{ $grade->student->student_id ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Class:</strong></td>
                                        <td>{{ $grade->class->full_name ?? 'Unknown' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Grade Information</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Subject:</strong></td>
                                        <td>{{ $grade->subject->name ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Exam Type:</strong></td>
                                        <td>{!! $grade->exam_type_badge !!}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Exam Date:</strong></td>
                                        <td>{{ $grade->exam_date?->format('F j, Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Marks</span>
                                        <span class="info-box-number">{{ $grade->marks_obtained }}/{{ $grade->total_marks }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Percentage</span>
                                        <span class="info-box-number">{{ $grade->calculated_percentage }}%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box bg-warning">
                                    <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Grade</span>
                                        <span class="info-box-number">{{ $grade->grade_letter }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box {{ $grade->isPassing() ? 'bg-success' : 'bg-danger' }}">
                                    <span class="info-box-icon">
                                        <i class="fas {{ $grade->isPassing() ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Status</span>
                                        <span class="info-box-number">{{ $grade->isPassing() ? 'Pass' : 'Fail' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($grade->remarks)
                            <div class="row">
                                <div class="col-12">
                                    <h5>Remarks</h5>
                                    <div class="alert alert-info">
                                        <i class="fas fa-comment mr-2"></i>
                                        {{ $grade->remarks }}
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-12">
                                <h5>Performance Analysis</h5>
                                <div class="progress mb-3">
                                    <div class="progress-bar" 
                                         style="width: {{ $grade->calculated_percentage }}%; background-color: {{ $grade->grade_color }}"
                                         role="progressbar">
                                        {{ $grade->calculated_percentage }}%
                                    </div>
                                </div>
                                <p class="text-muted">
                                    <strong>Performance Level:</strong> {{ $grade->performance_level }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('teacher.grades') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Back to List
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('teacher.grades.edit', $grade) }}" class="btn btn-warning">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Grade
                                </a>
                                <button class="btn btn-danger" onclick="deleteGrade({{ $grade->id }})">
                                    <i class="fas fa-trash mr-2"></i>
                                    Delete Grade
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .info-box-number {
            font-size: 1.5rem !important;
            font-weight: bold;
        }
        
        .progress {
            height: 30px;
        }
        
        .progress-bar {
            font-size: 1.1rem;
            font-weight: bold;
            line-height: 30px;
        }
    </style>
@stop

@section('js')
<script>
function deleteGrade(gradeId) {
    if (confirm('Are you sure you want to delete this grade? This action cannot be undone.')) {
        $.ajax({
            url: `/teacher/grades/${gradeId}`,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Grade deleted successfully');
                    
                    // Redirect to grades list after a short delay
                    setTimeout(function() {
                        window.location.href = '{{ route("teacher.grades") }}';
                    }, 1500);
                } else {
                    toastr.error('Failed to delete grade');
                }
            },
            error: function() {
                toastr.error('Failed to delete grade');
            }
        });
    }
}
</script>
@stop