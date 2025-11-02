@extends('layouts.school-admin')

@section('title', 'Student Profile - ' . $student->full_name)

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Student Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
                        <li class="breadcrumb-item active">{{ $student->full_name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Student Header -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <img class="profile-user-img img-fluid img-circle" 
                                         src="{{ $student->photo_url }}" 
                                         alt="Student Photo"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                    <h3 class="profile-username text-center mt-3">{{ $student->full_name }}</h3>
                                    <p class="text-muted text-center">{{ $student->student_id }}</p>
                                    
                                    <div class="text-center">
                                        {!! $student->status_badge !!}
                                    </div>
                                </div>
                                
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <th width="40%">Class:</th>
                                                    <td>{{ $student->classModel->name ?? 'Not Assigned' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Gender:</th>
                                                    <td>{{ ucfirst($student->gender) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Date of Birth:</th>
                                                    <td>{{ $student->date_of_birth->format('d M, Y') }} ({{ $student->age }} years)</td>
                                                </tr>
                                                <tr>
                                                    <th>Blood Group:</th>
                                                    <td>{{ $student->blood_group ?? 'Not specified' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Admission Date:</th>
                                                    <td>{{ $student->admission_date->format('d M, Y') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <th width="40%">Email:</th>
                                                    <td>{{ $student->email ?? 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone:</th>
                                                    <td>{{ $student->phone ?? 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Parent/Guardian:</th>
                                                    <td>{{ $student->parent->user->name ?? 'Not assigned' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Emergency Contact:</th>
                                                    <td>{{ $student->emergency_contact }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Emergency Phone:</th>
                                                    <td>{{ $student->emergency_phone }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Edit Profile
                                                </a>
                                                <button type="button" class="btn btn-info" id="printProfile">
                                                    <i class="fas fa-print mr-1"></i>
                                                    Print Profile
                                                </button>
                                                <button type="button" class="btn btn-secondary" id="exportProfile">
                                                    <i class="fas fa-download mr-1"></i>
                                                    Export Data
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Status Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $attendanceStats['attendance_percentage'] }}%</h3>
                            <p>Attendance Rate</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $gradeStats['average_grade'] }}</h3>
                            <p>Average Grade</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $attendanceStats['present_days'] }}</h3>
                            <p>Days Present</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $attendanceStats['absent_days'] }}</h3>
                            <p>Days Absent</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Performance -->
            <div class="row">
                <div class="col-md-8">
                    <!-- Recent Grades -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>
                                Recent Academic Performance
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($recentGrades) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Exam Type</th>
                                                <th>Marks</th>
                                                <th>Percentage</th>
                                                <th>Grade</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentGrades as $grade)
                                                <tr>
                                                    <td>{{ $grade['subject'] }}</td>
                                                    <td>{{ ucfirst($grade['exam_type']) }}</td>
                                                    <td>{{ $grade['marks_obtained'] }}/{{ $grade['total_marks'] }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $grade['percentage'] >= 80 ? 'success' : ($grade['percentage'] >= 60 ? 'warning' : 'danger') }}">
                                                            {{ $grade['percentage'] }}%
                                                        </span>
                                                    </td>
                                                    <td>{{ $grade['grade'] }}</td>
                                                    <td>{{ $grade['exam_date'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No recent grades available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Attendance -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Recent Attendance (Last 30 Days)
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($recentAttendance) > 0)
                                <div class="attendance-calendar">
                                    @foreach(array_chunk($recentAttendance, 7) as $week)
                                        <div class="row mb-2">
                                            @foreach($week as $day)
                                                <div class="col">
                                                    <div class="attendance-day text-center p-2 rounded 
                                                         {{ $day['status'] === 'present' ? 'bg-success' : ($day['status'] === 'absent' ? 'bg-danger' : 'bg-warning') }}">
                                                        <small class="text-white">{{ date('M d', strtotime($day['date'])) }}</small>
                                                        <br>
                                                        <i class="fas fa-{{ $day['status'] === 'present' ? 'check' : ($day['status'] === 'absent' ? 'times' : 'clock') }} text-white"></i>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No attendance records available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Academic Status -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-trophy mr-2"></i>
                                Academic Status
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="progress-group">
                                <span class="progress-text">Overall Performance</span>
                                <span class="float-right"><b>{{ $academicStatus['overall_performance'] }}</b></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-{{ $academicStatus['overall_performance'] === 'Excellent' ? 'success' : ($academicStatus['overall_performance'] === 'Good' ? 'info' : 'warning') }}" 
                                         style="width: {{ $academicStatus['overall_performance'] === 'Excellent' ? '100' : ($academicStatus['overall_performance'] === 'Good' ? '80' : '60') }}%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-group">
                                <span class="progress-text">Attendance Status</span>
                                <span class="float-right"><b>{{ $academicStatus['attendance_status'] }}</b></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-{{ $academicStatus['attendance_status'] === 'Excellent' ? 'success' : ($academicStatus['attendance_status'] === 'Good' ? 'info' : 'warning') }}" 
                                         style="width: {{ $attendanceStats['attendance_percentage'] }}%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-group">
                                <span class="progress-text">Grade Status</span>
                                <span class="float-right"><b>{{ $academicStatus['grade_status'] }}</b></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-{{ $academicStatus['grade_status'] === 'Excellent' ? 'success' : ($academicStatus['grade_status'] === 'Good' ? 'info' : 'warning') }}" 
                                         style="width: {{ $gradeStats['average_grade'] }}%"></div>
                                </div>
                            </div>
                            
                            @if($academicStatus['needs_attention'])
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Attention Required!</strong><br>
                                    This student may need additional support.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Subjects -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-book mr-2"></i>
                                Enrolled Subjects
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($student->subjects->count() > 0)
                                <ul class="list-unstyled">
                                    @foreach($student->subjects as $subject)
                                        <li class="mb-2">
                                            <span class="badge badge-primary mr-2">{{ $subject->code }}</span>
                                            {{ $subject->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No subjects assigned</p>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-address-book mr-2"></i>
                                Contact Information
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($student->address)
                                <p><strong>Address:</strong><br>{{ $student->address }}</p>
                            @endif
                            
                            <p><strong>Emergency Contact:</strong><br>
                               {{ $emergencyContact['contact_name'] }}<br>
                               <i class="fas fa-phone mr-1"></i> {{ $emergencyContact['contact_phone'] }}
                            </p>
                            
                            @if($emergencyContact['parent_name'])
                                <p><strong>Parent/Guardian:</strong><br>
                                   {{ $emergencyContact['parent_name'] }}<br>
                                   @if($emergencyContact['parent_phone'])
                                       <i class="fas fa-phone mr-1"></i> {{ $emergencyContact['parent_phone'] }}<br>
                                   @endif
                                   @if($emergencyContact['parent_email'])
                                       <i class="fas fa-envelope mr-1"></i> {{ $emergencyContact['parent_email'] }}
                                   @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.profile-user-img {
    border: 3px solid #adb5bd;
    margin: 0 auto;
    padding: 3px;
}

.attendance-day {
    min-height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.progress-group {
    margin-bottom: 15px;
}

.progress-text {
    font-weight: 600;
}

.card-primary.card-outline {
    border-top: 3px solid #007bff;
}

.box-profile {
    padding: 20px;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.8rem;
}

@media print {
    .btn, .card-tools, .breadcrumb {
        display: none !important;
    }
    
    .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Print profile
    $('#printProfile').click(function() {
        window.print();
    });
    
    // Export profile data
    $('#exportProfile').click(function() {
        const studentData = {
            student_id: '{{ $student->student_id }}',
            name: '{{ $student->full_name }}',
            class: '{{ $student->classModel->name ?? "Not Assigned" }}',
            attendance_rate: '{{ $attendanceStats["attendance_percentage"] }}%',
            average_grade: '{{ $gradeStats["average_grade"] }}',
            status: '{{ $student->status }}'
        };
        
        // Convert to CSV
        const csvContent = "data:text/csv;charset=utf-8," 
            + "Field,Value\n"
            + Object.entries(studentData).map(([key, value]) => `${key},${value}`).join('\n');
        
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `student_${studentData.student_id}_profile.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showSuccess('Student profile exported successfully');
    });
    
    function showSuccess(message) {
        toastr.success(message);
    }
});
</script>
@endpush