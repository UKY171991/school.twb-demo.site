@extends('layouts.app')

@section('title', 'TC Dashboard')
@section('page-title', 'TC Dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item active">TC Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- My Classes Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="my-classes-count">{{ $stats['classes'] ?? 0 }}</h3>
                <p>My Classes</p>
            </div>
            <div class="icon">
                <i class="fas fa-door-open"></i>
            </div>
            <a href="{{ route('teacher.classes') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- My Students Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="my-students-count">{{ $stats['students'] ?? 0 }}</h3>
                <p>My Students</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('teacher.students') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- My Subjects Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="my-subjects-count">{{ $stats['subjects'] ?? 0 }}</h3>
                <p>My Subjects</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{ route('teacher.subjects') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Today's Attendance Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="attendance-percentage">{{ $stats['attendance_percentage'] ?? 0 }}%</h3>
                <p>Today's Attendance</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <a href="{{ route('teacher.attendance') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- My Classes -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-door-open mr-2"></i>
                    My Classes
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Section</th>
                                <th>Students</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="my-classes">
                            @forelse($myClasses ?? [] as $class)
                                <tr>
                                    <td>{{ $class->name }}</td>
                                    <td>{{ $class->section }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $class->students_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('classes.show', $class->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('attendance.create', ['class_id' => $class->id]) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-calendar-check"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No classes assigned</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('teacher.classes') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-list"></i> View All Classes
                </a>
            </div>
        </div>
    </div>

    <!-- My Subjects -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book mr-2"></i>
                    My Subjects
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="my-subjects">
                            @forelse($mySubjects ?? [] as $subject)
                                <tr>
                                    <td>{{ $subject->name }}</td>
                                    <td>{{ $subject->code }}</td>
                                    <td>
                                        <span class="badge badge-{{ $subject->type === 'core' ? 'primary' : ($subject->type === 'elective' ? 'success' : 'warning') }}">
                                            {{ ucfirst($subject->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('subjects.show', $subject->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('grades.create', ['subject_id' => $subject->id]) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-star"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No subjects assigned</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('teacher.subjects') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-list"></i> View All Subjects
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Today's Schedule -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-day mr-2"></i>
                    Today's Schedule
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="today-schedule">
                    @forelse($todaySchedule ?? [] as $schedule)
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>{{ $schedule->time ?? '09:00 AM' }}</strong>
                                </div>
                                <div class="col-md-4">
                                    {{ $schedule->subject->name ?? 'Subject Name' }}
                                </div>
                                <div class="col-md-3">
                                    {{ $schedule->class->name ?? 'Class Name' }}
                                </div>
                                <div class="col-md-2">
                                    <span class="badge badge-success">Active</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle mr-2"></i>
                            No classes scheduled for today.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-2"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('attendance.create') }}" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Take Attendance
                    </a>
                    <a href="{{ route('grades.create') }}" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-star mr-2"></i>
                        Add Grades
                    </a>
                    <a href="{{ route('teacher.profile') }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-user mr-2"></i>
                        My Profile
                    </a>
                    <a href="{{ route('teacher.schedule') }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-calendar mr-2"></i>
                        View Schedule
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2"></i>
                    Recent Activities
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="recent-activities">
                    @forelse($recentActivities ?? [] as $activity)
                        <div class="timeline">
                            <div class="time-label">
                                <span class="bg-{{ $activity->type === 'attendance' ? 'success' : ($activity->type === 'grade' ? 'warning' : 'info') }}">
                                    {{ $activity->created_at->format('M d') }}
                                </span>
                            </div>
                            <div>
                                <i class="fas fa-{{ $activity->type === 'attendance' ? 'calendar-check' : ($activity->type === 'grade' ? 'star' : 'book') }} bg-{{ $activity->type === 'attendance' ? 'success' : ($activity->type === 'grade' ? 'warning' : 'info') }}"></i>
                                <div class="timeline-item">
                                    <span class="time">
                                        <i class="fas fa-clock"></i> {{ $activity->created_at->format('H:i') }}
                                    </span>
                                    <h3 class="timeline-header">
                                        {{ $activity->title }}
                                    </h3>
                                    <div class="timeline-body">
                                        {{ $activity->description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            No recent activities found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Load teacher dashboard statistics
        loadTeacherDashboardStats();
        
        // Refresh stats every 30 seconds
        setInterval(loadTeacherDashboardStats, 30000);
    });

    function loadTeacherDashboardStats() {
        $.ajax({
            url: '{{ route("teacher.dashboard.stats") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    updateTeacherStatsDisplay(response.data);
                }
            },
            error: function(xhr) {
                console.log('Error loading teacher dashboard stats');
            }
        });
    }

    function updateTeacherStatsDisplay(data) {
        $('#my-classes-count').text(data.classes || 0);
        $('#my-students-count').text(data.students || 0);
        $('#my-subjects-count').text(data.subjects || 0);
        $('#attendance-percentage').text((data.attendance_percentage || 0) + '%');
    }

    // Quick action buttons with loading state
    $('.btn-block').on('click', function(e) {
        const action = $(this).text().trim();
        
        // Add loading state
        const originalText = $(this).html();
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Loading...');
        
        // Re-enable button after a short delay (in case of navigation)
        setTimeout(() => {
            $(this).prop('disabled', false).html(originalText);
        }, 2000);
    });

    // Mark attendance as completed
    function markAttendanceComplete(classId) {
        $.ajax({
            url: '{{ route("attendance.mark.complete") }}',
            type: 'POST',
            data: {
                class_id: classId,
                date: new Date().toISOString().split('T')[0]
            },
            success: function(response) {
                if (response.success) {
                    showSuccess('Attendance marked as completed successfully!');
                    loadTeacherDashboardStats();
                } else {
                    showError(response.message || 'Failed to mark attendance as completed.');
                }
            },
            error: function(xhr) {
                showError('An error occurred while marking attendance as completed.');
            }
        });
    }

    // View class details
    function viewClassDetails(classId) {
        window.location.href = '{{ route("classes.show", ":id") }}'.replace(':id', classId);
    }
</script>
@endsection
