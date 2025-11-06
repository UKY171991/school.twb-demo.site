@extends('layouts.parent')

@section('title', 'Parent Dashboard')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Parent Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $statistics['total_children'] }}</h3>
                        <p>My Children</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-child"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $statistics['children_present_today'] }}/{{ $statistics['total_children'] }}</h3>
                        <p>Present Today</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $statistics['unread_messages'] }}</h3>
                        <p>Unread Messages</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $statistics['urgent_alerts'] }}</h3>
                        <p>Urgent Alerts</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Family Performance Overview -->
        <div class="row">
            <div class="col-md-8">
                <!-- Children Overview -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-2"></i>
                            My Children Overview
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(count($dashboardData['children_overview']) > 0)
                            @foreach($dashboardData['children_overview'] as $child)
                                <div class="child-overview-card mb-4 p-3 border rounded {{ $child['needs_attention'] ? 'border-warning' : 'border-light' }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <img src="{{ $child['photo_url'] }}" 
                                                 alt="{{ $child['name'] }}" 
                                                 class="img-fluid img-circle"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="mb-1">{{ $child['name'] }}</h5>
                                            <p class="mb-1 text-muted">{{ $child['class'] }}</p>
                                            <p class="mb-0 small text-muted">ID: {{ $child['student_id'] }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div class="progress mb-1">
                                                    <div class="progress-bar bg-{{ $child['attendance_stats']['attendance_percentage'] >= 90 ? 'success' : ($child['attendance_stats']['attendance_percentage'] >= 75 ? 'warning' : 'danger') }}" 
                                                         style="width: {{ $child['attendance_stats']['attendance_percentage'] }}%"></div>
                                                </div>
                                                <small>Attendance: {{ $child['attendance_stats']['attendance_percentage'] }}%</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-{{ $child['grade_stats']['average_grade'] >= 80 ? 'success' : ($child['grade_stats']['average_grade'] >= 60 ? 'warning' : 'danger') }}">
                                                    {{ number_format($child['grade_stats']['average_grade'], 1) }}%
                                                </h4>
                                                <small>Average Grade</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <h6>Today's Status</h6>
                                            <span class="badge badge-{{ $child['today_attendance'] === 'present' ? 'success' : ($child['today_attendance'] === 'absent' ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($child['today_attendance']) }}
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Recent Grades</h6>
                                            @if(count($child['recent_grades']) > 0)
                                                @foreach(array_slice($child['recent_grades'], 0, 3) as $grade)
                                                    <small class="d-block">{{ $grade['subject'] }}: {{ $grade['percentage'] }}%</small>
                                                @endforeach
                                            @else
                                                <small class="text-muted">No recent grades</small>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Upcoming Assignments</h6>
                                            @if(count($child['upcoming_assignments']) > 0)
                                                @foreach(array_slice($child['upcoming_assignments'], 0, 2) as $assignment)
                                                    <small class="d-block">{{ $assignment['subject'] }} - {{ $assignment['due_date'] }}</small>
                                                @endforeach
                                            @else
                                                <small class="text-muted">No upcoming assignments</small>
                                            @endif
                                        </div>
                                    </div>

                                    @if($child['needs_attention'])
                                        <div class="alert alert-warning mt-3 mb-0">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            <strong>Attention Required:</strong> This child may need additional support.
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-child fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Children Found</h5>
                                <p class="text-muted">No children are associated with your account.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Family Performance Summary -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-2"></i>
                            Family Performance
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h4 class="text-{{ $dashboardData['family_performance']['overall_status'] === 'Excellent' ? 'success' : ($dashboardData['family_performance']['overall_status'] === 'Good' ? 'primary' : 'warning') }}">
                                {{ $dashboardData['family_performance']['overall_status'] }}
                            </h4>
                            <p class="text-muted">Overall Family Status</p>
                        </div>
                        
                        <div class="progress-group">
                            <span class="progress-text">Average Attendance</span>
                            <span class="float-right">{{ $dashboardData['family_performance']['average_attendance'] }}%</span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: {{ $dashboardData['family_performance']['average_attendance'] }}%"></div>
                            </div>
                        </div>
                        
                        <div class="progress-group">
                            <span class="progress-text">Average Grades</span>
                            <span class="float-right">{{ $dashboardData['family_performance']['average_grades'] }}%</span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" style="width: {{ $dashboardData['family_performance']['average_grades'] }}%"></div>
                            </div>
                        </div>

                        @if($dashboardData['family_performance']['children_needing_attention'] > 0)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ $dashboardData['family_performance']['children_needing_attention'] }} child(ren) need attention
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Alerts & Notifications -->
                @if(count($dashboardData['alerts_and_notifications']) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bell mr-2"></i>
                                Alerts & Notifications
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach(array_slice($dashboardData['alerts_and_notifications'], 0, 5) as $alert)
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-start">
                                            <div class="mr-3">
                                                <i class="fas fa-{{ $alert['type'] === 'attendance' ? 'calendar-times' : ($alert['type'] === 'grades' ? 'chart-line' : 'tasks') }} text-{{ $alert['severity'] === 'high' ? 'danger' : 'warning' }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $alert['child_name'] }}</h6>
                                                <p class="mb-0 text-muted small">{{ $alert['message'] }}</p>
                                                @if($alert['action_needed'])
                                                    <small class="text-danger">Action Required</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Upcoming Events -->
                @if(count($dashboardData['upcoming_events']) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar mr-2"></i>
                                Upcoming Events
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach(array_slice($dashboardData['upcoming_events'], 0, 5) as $event)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $event['title'] }}</h6>
                                                <p class="mb-1 text-muted small">{{ $event['child_name'] }}</p>
                                                <small class="text-muted">{{ Carbon\Carbon::parse($event['date'])->format('M j, Y') }}</small>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge badge-{{ $event['type'] === 'exam' ? 'danger' : ($event['type'] === 'assignment' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($event['type']) }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $event['days_until'] }} days</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activities -->
        @if(count($dashboardData['recent_activities']) > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-2"></i>
                                Recent Activities
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @foreach(array_slice($dashboardData['recent_activities'], 0, 10) as $activity)
                                    <div class="time-label">
                                        <span class="bg-{{ $activity['status'] === 'positive' ? 'success' : 'danger' }}">
                                            {{ Carbon\Carbon::parse($activity['date'])->format('M j') }}
                                        </span>
                                    </div>
                                    <div>
                                        <i class="fas fa-{{ $activity['type'] === 'grade' ? 'star' : 'calendar-times' }} bg-{{ $activity['status'] === 'positive' ? 'blue' : 'red' }}"></i>
                                        <div class="timeline-item">
                                            <span class="time">
                                                <i class="fas fa-clock"></i> {{ Carbon\Carbon::parse($activity['date'])->diffForHumans() }}
                                            </span>
                                            <h3 class="timeline-header">{{ $activity['child_name'] }}</h3>
                                            <div class="timeline-body">
                                                {{ $activity['description'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div>
                                    <i class="fas fa-clock bg-gray"></i>
                                </div>
                            </div>
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
    // Auto-refresh dashboard every 5 minutes
    setTimeout(function() {
        location.reload();
    }, 300000);
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush

@push('styles')
<style>
.child-overview-card {
    transition: all 0.3s ease;
}

.child-overview-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
</style>
@endpush
