@extends('layouts.app')

@section('title', 'Dashboard')

@section('content_header')
    <div class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p class="text-muted">Welcome to your school management overview</p>
        </div>
        @if(isset($currentSchool))
            <div class="school-badge">
                <i class="fas fa-school"></i> {{ $currentSchool->name }}
            </div>
        @endif
    </div>
@stop

@section('content')
    <!-- Welcome Banner -->
    <div class="welcome-banner mb-4">
        <div class="d-flex align-items-center justify-content-between p-4 bg-white rounded shadow-sm custom-banner">
            <div>
                <h4 class="mb-1">Welcome back, {{ Auth::user()->name }}!</h4>
                <p class="mb-0 text-muted">Here's what's happening in your school today.</p>
            </div>
            <div class="date-badge">
                <span class="day">{{ now()->format('d') }}</span>
                <span class="month">{{ now()->format('M') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row">
        <!-- Students -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-info">
                <div class="inner">
                    <h3>{{ $stats['students'] }}</h3>
                    <p>Students</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="{{ route('students.index') }}" class="small-box-footer">
                    View All <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- Teachers -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3>{{ $stats['teachers'] }}</h3>
                    <p>Teachers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('teachers.index') }}" class="small-box-footer">
                    View All <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- Grades -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning">
                <div class="inner">
                    <h3>{{ $stats['grades'] }}</h3>
                    <p>Classes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <a href="{{ route('grades.index') }}" class="small-box-footer">
                    View All <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- Subjects -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-danger">
                <div class="inner">
                    <h3>{{ $stats['subjects'] }}</h3>
                    <p>Subjects</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('subjects.index') }}" class="small-box-footer">
                    View All <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Attendance -->
        <div class="col-lg-6 col-md-6">
            <div class="small-box bg-gradient-purple">
                <div class="inner">
                    <h3>{{ $stats['attendances_today'] }}</h3>
                    <p>Today's Attendance</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <a href="{{ route('attendances.index') }}" class="small-box-footer">
                    Manage Attendance <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- Marksheets -->
        <div class="col-lg-6 col-md-6">
            <div class="small-box bg-gradient-teal">
                <div class="inner">
                    <h3>{{ $stats['marksheets'] }}</h3>
                    <p>Marksheets Generated</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <a href="{{ route('marksheets.index') }}" class="small-box-footer">
                    View Marksheets <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white border-0 py-4">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-bolt text-warning mr-2"></i> Quick Actions
            </h3>
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <a href="{{ route('students.create') }}" class="btn btn-app btn-block bg-light hover-shadow">
                        <i class="fas fa-user-plus text-primary"></i> Add Student
                    </a>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <a href="{{ route('teachers.create') }}" class="btn btn-app btn-block bg-light hover-shadow">
                        <i class="fas fa-chalkboard-teacher text-success"></i> Add Teacher
                    </a>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <a href="{{ route('grades.create') }}" class="btn btn-app btn-block bg-light hover-shadow">
                        <i class="fas fa-layer-group text-warning"></i> Add Class
                    </a>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <a href="{{ route('marksheets.create') }}" class="btn btn-app btn-block bg-light hover-shadow">
                        <i class="fas fa-file-medical text-info"></i> Add Marksheet
                    </a>
                </div>
                 <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <a href="{{ route('schools.index') }}" class="btn btn-app btn-block bg-light hover-shadow">
                        <i class="fas fa-school text-secondary"></i> Schools
                    </a>
                </div>
                 <!-- Placeholder for future action -->
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                     <a href="{{ route('settings.index') }}" class="btn btn-app btn-block bg-light hover-shadow">
                        <i class="fas fa-cogs text-dark"></i> Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .dashboard-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #343a40;
            margin: 0;
        }
        .school-badge {
            background: #fff;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            font-weight: 600;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .bg-gradient-info { background: linear-gradient(45deg, #17a2b8, #138496); }
        .bg-gradient-success { background: linear-gradient(45deg, #28a745, #218838); }
        .bg-gradient-warning { background: linear-gradient(45deg, #ffc107, #e0a800); color: #fff !important; }
        .bg-gradient-danger { background: linear-gradient(45deg, #dc3545, #c82333); }
        .bg-gradient-purple { background: linear-gradient(45deg, #6f42c1, #5a32a3); color: #fff; }
        .bg-gradient-teal { background: linear-gradient(45deg, #20c997, #17a589); color: #fff; }
        
        .small-box { 
            border-radius: 10px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
            transition: transform 0.2s;
            overflow: hidden;
        }
        .small-box:hover { transform: translateY(-3px); }
        
        .custom-banner {
            border-left: 5px solid #007bff;
        }
        .date-badge {
            text-align: center;
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        .date-badge .day { display: block; font-size: 1.5rem; font-weight: bold; line-height: 1; }
        .date-badge .month { display: block; font-size: 0.8rem; text-transform: uppercase; color: #6c757d; }
        
        .btn-app {
            height: auto;
            min-height: 100px;
            padding: 1.5rem 0.5rem;
            margin: 0;
            border: 1px solid #eee;
            background-color: #fff !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #555;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-app:hover {
            border-color: #007bff;
            background-color: #f8f9ff !important;
            color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-app i {
            font-size: 2rem !important;
            display: block;
            margin-bottom: 5px;
        }
        .hover-shadow { transition: all 0.3s; }
    </style>
@stop
