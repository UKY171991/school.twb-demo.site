@extends('layouts.parent')

@section('title', 'My Children')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">My Children</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">My Children</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(count($childrenData) > 0)
            <div class="row">
                @foreach($childrenData as $data)
                    @php $child = $data['child']; @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-widget widget-user-2">
                            <div class="widget-user-header bg-{{ $data['academic_status']['needs_attention'] ? 'warning' : 'primary' }}">
                                <div class="widget-user-image">
                                    <img class="img-circle elevation-2" 
                                         src="{{ $child->photo_url }}" 
                                         alt="{{ $child->full_name }}"
                                         style="width: 65px; height: 65px; object-fit: cover;">
                                </div>
                                <h3 class="widget-user-username">{{ $child->full_name }}</h3>
                                <h5 class="widget-user-desc">{{ $child->classModel->full_name ?? 'Not Assigned' }}</h5>
                            </div>
                            
                            <div class="card-footer p-0">
                                <!-- Academic Performance Summary -->
                                <div class="row text-center p-3">
                                    <div class="col-4">
                                        <div class="description-block">
                                            <h5 class="description-header text-{{ $data['attendance_stats']['attendance_percentage'] >= 90 ? 'success' : ($data['attendance_stats']['attendance_percentage'] >= 75 ? 'warning' : 'danger') }}">
                                                {{ $data['attendance_stats']['attendance_percentage'] }}%
                                            </h5>
                                            <span class="description-text">ATTENDANCE</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="description-block">
                                            <h5 class="description-header text-{{ $data['grade_stats']['average_grade'] >= 80 ? 'success' : ($data['grade_stats']['average_grade'] >= 60 ? 'warning' : 'danger') }}">
                                                {{ number_format($data['grade_stats']['average_grade'], 1) }}%
                                            </h5>
                                            <span class="description-text">AVG GRADE</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="description-block">
                                            <h5 class="description-header text-info">
                                                {{ count($data['grade_stats']['grade_distribution']) }}
                                            </h5>
                                            <span class="description-text">SUBJECTS</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Status Indicators -->
                                <div class="px-3 pb-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <small class="text-muted">Academic Status:</small>
                                            <span class="badge badge-{{ $data['academic_status']['overall_performance'] === 'Excellent' ? 'success' : ($data['academic_status']['overall_performance'] === 'Good' ? 'primary' : 'warning') }} ml-1">
                                                {{ $data['academic_status']['overall_performance'] }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if(count($data['recent_alerts']) > 0)
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    {{ count($data['recent_alerts']) }} alert(s) require attention
                                                </small>
                                            </div>
                                        </div>
                                    @endif

                                    @if(count($data['upcoming_events']) > 0)
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <small class="text-info">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ count($data['upcoming_events']) }} upcoming event(s)
                                                </small>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="card-footer p-0">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('parent.children.show', $child) }}" class="nav-link">
                                                <i class="fas fa-eye mr-2"></i>
                                                View Detailed Report
                                                <span class="float-right badge bg-primary">
                                                    <i class="fas fa-arrow-right"></i>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('parent.children.attendance-analysis', $child) }}" class="nav-link">
                                                <i class="fas fa-calendar-check mr-2"></i>
                                                Attendance Analysis
                                                <span class="float-right badge bg-{{ $data['attendance_stats']['attendance_percentage'] >= 90 ? 'success' : 'warning' }}">
                                                    {{ $data['attendance_stats']['attendance_percentage'] }}%
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('parent.children.grade-tracking', $child) }}" class="nav-link">
                                                <i class="fas fa-chart-line mr-2"></i>
                                                Grade Tracking
                                                <span class="float-right badge bg-{{ $data['grade_stats']['average_grade'] >= 80 ? 'success' : 'warning' }}">
                                                    {{ $data['grade_stats']['total_grades'] }} grades
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('parent.children.performance-trends', $child) }}" class="nav-link">
                                                <i class="fas fa-trending-up mr-2"></i>
                                                Performance Trends
                                                <span class="float-right badge bg-info">
                                                    <i class="fas fa-chart-bar"></i>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Family Summary -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users mr-2"></i>
                                Family Academic Summary
                            </h3>
                        </div>
                        <div class="card-body">
                            @php
                                $familyPerformance = $parent->getFamilyAcademicPerformance();
                            @endphp
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-child"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Children</span>
                                            <span class="info-box-number">{{ $familyPerformance['total_children'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success">
                                            <i class="fas fa-calendar-check"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Family Attendance</span>
                                            <span class="info-box-number">{{ $familyPerformance['average_attendance'] }}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning">
                                            <i class="fas fa-star"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Family Grades</span>
                                            <span class="info-box-number">{{ $familyPerformance['average_grades'] }}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-{{ $familyPerformance['children_needing_attention'] > 0 ? 'danger' : 'primary' }}">
                                            <i class="fas fa-{{ $familyPerformance['children_needing_attention'] > 0 ? 'exclamation-triangle' : 'check-circle' }}"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Need Attention</span>
                                            <span class="info-box-number">{{ $familyPerformance['children_needing_attention'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <h4 class="text-{{ $familyPerformance['overall_status'] === 'Excellent' ? 'success' : ($familyPerformance['overall_status'] === 'Good' ? 'primary' : 'warning') }}">
                                        Overall Family Status: {{ $familyPerformance['overall_status'] }}
                                    </h4>
                                    @if($familyPerformance['children_needing_attention'] > 0)
                                        <div class="alert alert-warning mt-3">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            {{ $familyPerformance['children_needing_attention'] }} of your children may need additional academic support.
                                            Consider reviewing their individual reports and contacting their teachers.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-child fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted">No Children Found</h4>
                            <p class="text-muted">
                                No children are currently associated with your parent account.
                                Please contact the school administration if this is incorrect.
                            </p>
                            <a href="{{ route('parent.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Add hover effects to cards
    $('.card-widget').hover(
        function() {
            $(this).addClass('shadow-lg').css('transform', 'translateY(-2px)');
        },
        function() {
            $(this).removeClass('shadow-lg').css('transform', 'translateY(0)');
        }
    );
});
</script>
@endpush

@push('styles')
<style>
.card-widget {
    transition: all 0.3s ease;
}

.description-block {
    text-align: center;
}

.description-header {
    font-size: 1.2rem;
    font-weight: bold;
    margin: 0;
}

.description-text {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
}

.widget-user-header {
    position: relative;
    padding: 20px;
    text-align: center;
}

.widget-user-image {
    position: absolute;
    top: 65px;
    left: 50%;
    margin-left: -32.5px;
}

.widget-user-username {
    margin-top: 0;
    margin-bottom: 5px;
    font-size: 1.1rem;
    font-weight: 600;
}

.widget-user-desc {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.8;
}

.nav-link {
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.nav-link:last-child {
    border-bottom: none;
}

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

.info-box-icon {
    border-radius: .25rem;
    align-items: center;
    display: flex;
    font-size: 1.875rem;
    justify-content: center;
    text-align: center;
    width: 70px;
    color: rgba(255,255,255,.8);
    flex-shrink: 0;
}

.info-box-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.8;
    margin-left: .5rem;
    padding: 0;
}

.info-box-number {
    display: block;
    font-weight: 700;
    font-size: 1.125rem;
}

.info-box-text {
    display: block;
    font-size: .875rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
@endpush