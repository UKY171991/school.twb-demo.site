@extends('adminlte::page')

@section('title', 'System Settings')

@section('content_header')
    <h1>System Settings</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-graduation-cap"></i> Grading System
                </h3>
            </div>
            <div class="card-body">
                <p>Configure grade boundaries, pass marks, and grading scales.</p>
                <a href="{{ route('settings.grading') }}" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Configure Grading
                </a>
                <a href="{{ route('grading-systems.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Manage Grades
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calculator"></i> Marking System
                </h3>
            </div>
            <div class="card-body">
                <p>Configure marking schemes, calculation methods, and rounding rules.</p>
                <a href="{{ route('settings.marking') }}" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Configure Marking
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-school"></i> School Information
                </h3>
            </div>
            <div class="card-body">
                <p>Update school details, academic year, and general settings.</p>
                <button class="btn btn-primary" data-toggle="modal" data-target="#schoolSettingsModal">
                    <i class="fas fa-cog"></i> Configure School
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Current Settings Overview</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Grading System</h5>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Active Grades:</strong></td>
                                <td>{{ $gradingSystems->count() }} grades configured</td>
                            </tr>
                            <tr>
                                <td><strong>Pass Percentage:</strong></td>
                                <td>{{ \App\Models\SystemSetting::get('pass_percentage', 33) }}%</td>
                            </tr>
                            <tr>
                                <td><strong>Calculation Method:</strong></td>
                                <td>{{ ucfirst(\App\Models\SystemSetting::get('grade_calculation_method', 'percentage')) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Marking System</h5>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Decimal Places:</strong></td>
                                <td>{{ \App\Models\SystemSetting::get('decimal_places', 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Rounding Method:</strong></td>
                                <td>{{ ucfirst(\App\Models\SystemSetting::get('rounding_method', 'round')) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Marking Schemes:</strong></td>
                                <td>{{ $markingSchemes->count() }} schemes available</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- School Settings Modal -->
<div class="modal fade" id="schoolSettingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">School Settings</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>School Name</label>
                                <input type="text" name="settings[school_name]" class="form-control" 
                                       value="{{ \App\Models\SystemSetting::get('school_name', 'ABC School') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Academic Year</label>
                                <input type="text" name="settings[academic_year]" class="form-control" 
                                       value="{{ \App\Models\SystemSetting::get('academic_year', '2024-2025') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>School Address</label>
                                <textarea name="settings[school_address]" class="form-control" rows="3">{{ \App\Models\SystemSetting::get('school_address', '123 Education Street, Learning City') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="settings[school_phone]" class="form-control" 
                                       value="{{ \App\Models\SystemSetting::get('school_phone', '+1234567890') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="settings[school_email]" class="form-control" 
                                       value="{{ \App\Models\SystemSetting::get('school_email', 'info@abcschool.edu') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop