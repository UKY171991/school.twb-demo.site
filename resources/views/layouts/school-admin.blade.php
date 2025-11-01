@extends('layouts.master')

@section('title', 'School Admin - ' . (isset($pageTitle) ? $pageTitle : 'Dashboard'))

@section('body-class', 'admin-layout')

@push('styles')
<style>
    /* School Admin specific styles */
    .admin-layout .main-sidebar {
        background: linear-gradient(180deg, #17a2b8 0%, #138496 100%);
    }
    
    .school-info-card {
        background: linear-gradient(135deg, #17a2b8, #20c997);
        color: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .quick-action-btn {
        transition: all 0.3s ease;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('control-sidebar')
    <!-- School Admin Control Panel -->
    <div class="p-3">
        <h5>School Admin Panel</h5>
        <hr class="mb-2">
        
        @if(isset($currentSchool))
            <div class="mb-3">
                <label class="form-label">Current School</label>
                <div class="bg-light p-2 rounded">
                    <strong>{{ $currentSchool->name }}</strong>
                    <br>
                    <small class="text-muted">{{ $currentSchool->address }}</small>
                </div>
            </div>
        @endif
        
        <div class="mb-3">
            <label class="form-label">Quick Actions</label>
            <div class="d-grid gap-2">
                <button class="btn btn-primary btn-sm" onclick="addNewStudent()">
                    <i class="fas fa-user-plus"></i> Add Student
                </button>
                <button class="btn btn-success btn-sm" onclick="markAttendance()">
                    <i class="fas fa-calendar-check"></i> Mark Attendance
                </button>
                <button class="btn btn-info btn-sm" onclick="generateReport()">
                    <i class="fas fa-chart-bar"></i> Generate Report
                </button>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Today's Summary</label>
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">Present</small>
                    <div class="font-weight-bold text-success">{{ $statistics['present_today'] ?? 0 }}</div>
                </div>
                <div class="col-6">
                    <small class="text-muted">Absent</small>
                    <div class="font-weight-bold text-danger">{{ $statistics['absent_today'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function addNewStudent() {
    window.location.href = '{{ route("admin.students.create") }}';
}

function markAttendance() {
    window.location.href = '{{ route("admin.attendance.create") }}';
}

function generateReport() {
    window.location.href = '{{ route("admin.reports.index") }}';
}
</script>
@endpush