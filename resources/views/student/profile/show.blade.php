@extends('layouts.student')

@section('title', 'My Profile')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">My Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Profile Information -->
            <div class="col-md-4">
                <!-- Profile Card -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ $student->photo_url }}"
                                 alt="Student profile picture"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        </div>

                        <h3 class="profile-username text-center">{{ $student->full_name }}</h3>

                        <p class="text-muted text-center">
                            Student ID: {{ $student->student_id }}
                        </p>

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
                            <li class="list-group-item">
                                <b>Admission Date</b> 
                                <a class="float-right">{{ $student->admission_date ? $student->admission_date->format('M j, Y') : 'N/A' }}</a>
                            </li>
                        </ul>

                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('student.profile.edit') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-edit mr-1"></i> Edit Profile
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('student.profile.academic-records') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-file-alt mr-1"></i> Records
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Status Card -->
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
                                <b>{{ $stats['academic_status']['overall_performance'] }}</b>
                            </span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-{{ $stats['academic_status']['overall_performance'] === 'Excellent' ? 'success' : ($stats['academic_status']['overall_performance'] === 'Good' ? 'primary' : 'warning') }}" 
                                     style="width: {{ $stats['grade_stats']['average_grade'] }}%"></div>
                            </div>
                        </div>

                        <div class="progress-group">
                            <span class="progress-text">Attendance Rate</span>
                            <span class="float-right">
                                <b>{{ $stats['attendance_stats']['attendance_percentage'] }}%</b>
                            </span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-{{ $stats['attendance_stats']['attendance_percentage'] >= 90 ? 'success' : ($stats['attendance_stats']['attendance_percentage'] >= 75 ? 'warning' : 'danger') }}" 
                                     style="width: {{ $stats['attendance_stats']['attendance_percentage'] }}%"></div>
                            </div>
                        </div>

                        @if($stats['academic_status']['needs_attention'])
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Attention Required:</strong> Your academic performance needs improvement.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-phone mr-2"></i>
                            Emergency Contact
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($stats['emergency_contact']['contact_name'])
                            <p><strong>Name:</strong> {{ $stats['emergency_contact']['contact_name'] }}</p>
                            <p><strong>Phone:</strong> {{ $stats['emergency_contact']['contact_phone'] }}</p>
                        @else
                            <p class="text-muted">No emergency contact information available.</p>
                            <a href="{{ route('student.profile.edit') }}" class="btn btn-sm btn-primary">
                                Add Emergency Contact
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-8">
                <!-- Personal Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user mr-2"></i>
                            Personal Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>First Name:</strong></td>
                                        <td>{{ $student->first_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Name:</strong></td>
                                        <td>{{ $student->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Middle Name:</strong></td>
                                        <td>{{ $student->middle_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date of Birth:</strong></td>
                                        <td>{{ $student->date_of_birth ? $student->date_of_birth->format('M j, Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Age:</strong></td>
                                        <td>{{ $student->age }} years</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Gender:</strong></td>
                                        <td>{{ $student->gender ? ucfirst($student->gender) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Blood Group:</strong></td>
                                        <td>{{ $student->blood_group ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>{{ $student->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $student->email ?? $student->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Address:</strong></td>
                                        <td>{{ $student->address ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Academic Statistics
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-star"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Grades</span>
                                        <span class="info-box-number">{{ $stats['grade_stats']['total_grades'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success">
                                        <i class="fas fa-percentage"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Average Grade</span>
                                        <span class="info-box-number">{{ number_format($stats['grade_stats']['average_grade'], 1) }}%</span>
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
                                        <span class="info-box-number">{{ $stats['attendance_stats']['attendance_percentage'] }}%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger">
                                        <i class="fas fa-book"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Subjects</span>
                                        <span class="info-box-number">{{ $stats['academic_info']['total_subjects'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-star mr-2"></i>
                                    Recent Grades
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                @if(count($recentActivity['recent_grades']) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Grade</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentActivity['recent_grades'] as $grade)
                                                    <tr>
                                                        <td>{{ $grade['subject'] }}</td>
                                                        <td>
                                                            <span class="badge badge-{{ $grade['percentage'] >= 80 ? 'success' : ($grade['percentage'] >= 60 ? 'warning' : 'danger') }}">
                                                                {{ $grade['percentage'] }}%
                                                            </span>
                                                        </td>
                                                        <td>{{ $grade['exam_date'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-star fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No recent grades available</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-trophy mr-2"></i>
                                    Recent Achievements
                                </h3>
                            </div>
                            <div class="card-body">
                                @if(count($achievements) > 0)
                                    @foreach(array_slice($achievements, 0, 5) as $achievement)
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="mr-3">
                                                <i class="{{ $achievement['icon'] }} text-{{ $achievement['color'] }}"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $achievement['title'] }}</h6>
                                                <p class="mb-0 text-muted small">{{ $achievement['description'] }}</p>
                                                <small class="text-muted">{{ Carbon\Carbon::parse($achievement['date'])->diffForHumans() }}</small>
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
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush