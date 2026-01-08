@extends('layouts.app')

@section('title', 'System Settings')

@section('content_header')
    <h1>System Settings</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-4 d-flex align-items-stretch">
        <div class="card h-100 w-100 shadow-sm">
            <div class="card-header bg-gradient-purple text-white">
                <h3 class="card-title">
                    <i class="fas fa-graduation-cap"></i> Grading System
                </h3>
            </div>
            <div class="card-body d-flex flex-column">
                <p>Configure grade boundaries, pass marks, and grading scales.</p>
                <div class="mt-auto">
                    <button class="btn btn-primary btn-block mb-2" data-toggle="modal" data-target="#gradingSettingsModal">
                        <i class="fas fa-cog"></i> Configure Grading
                    </button>
                    <a href="{{ route('grading-systems.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-list"></i> Manage Grades
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Grading Settings Modal -->
    <div class="modal fade" id="gradingSettingsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="gradingSettingsForm" action="{{ route('settings.grading.update') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Grading System Settings</h4>
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="current_grading_scheme">Current Grading Scheme</label>
                            <select name="current_grading_scheme" id="current_grading_scheme" class="form-control">
                                <option value="default" {{ \App\Models\SystemSetting::get('current_grading_scheme', 'default') == 'default' ? 'selected' : '' }}>Default System</option>
                                <option value="percentage" {{ \App\Models\SystemSetting::get('current_grading_scheme', 'default') == 'percentage' ? 'selected' : '' }}>Percentage Based</option>
                                <option value="points" {{ \App\Models\SystemSetting::get('current_grading_scheme', 'default') == 'points' ? 'selected' : '' }}>Points Based</option>
                                <option value="letter" {{ \App\Models\SystemSetting::get('current_grading_scheme', 'default') == 'letter' ? 'selected' : '' }}>Letter Grades</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="pass_percentage">Pass Percentage</label>
                            <input type="number" name="pass_percentage" id="pass_percentage" class="form-control"
                                   value="{{ \App\Models\SystemSetting::get('pass_percentage', 33) }}"
                                   min="0" max="100" step="0.01">
                            <small class="form-text text-muted">Minimum percentage required to pass</small>
                        </div>

                        <div class="form-group">
                            <label for="grade_calculation_method">Grade Calculation Method</label>
                            <select name="grade_calculation_method" id="grade_calculation_method" class="form-control">
                                <option value="percentage" {{ \App\Models\SystemSetting::get('grade_calculation_method', 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                <option value="points" {{ \App\Models\SystemSetting::get('grade_calculation_method', 'percentage') == 'points' ? 'selected' : '' }}>Points</option>
                                <option value="weighted" {{ \App\Models\SystemSetting::get('grade_calculation_method', 'percentage') == 'weighted' ? 'selected' : '' }}>Weighted Average</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 d-flex align-items-stretch">
        <div class="card h-100 w-100 shadow-sm">
            <div class="card-header bg-gradient-indigo text-white">
                <h3 class="card-title">
                    <i class="fas fa-calculator"></i> Marking System
                </h3>
            </div>
            <div class="card-body d-flex flex-column">
                <p>Configure marking schemes, calculation methods, and rounding rules.</p>
                <div class="mt-auto">
                    <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#markingSettingsModal">
                        <i class="fas fa-cog"></i> Configure Marking
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Marking Settings Modal -->
    <div class="modal fade" id="markingSettingsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="markingSettingsForm" action="{{ route('settings.marking.update') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Marking System Settings</h4>
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="current_marking_scheme" class="font-weight-bold">Current Marking Scheme</label>
                            <select name="current_marking_scheme" id="current_marking_scheme" class="form-control custom-select">
                                <option value="percentage" {{ \App\Models\SystemSetting::get('current_marking_scheme', 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage Based</option>
                                <option value="points" {{ \App\Models\SystemSetting::get('current_marking_scheme', 'percentage') == 'points' ? 'selected' : '' }}>Points Based</option>
                                <option value="letter" {{ \App\Models\SystemSetting::get('current_marking_scheme', 'percentage') == 'letter' ? 'selected' : '' }}>Letter Grades</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="decimal_places" class="font-weight-bold">Decimal Places</label>
                                    <select name="decimal_places" id="decimal_places" class="form-control custom-select">
                                        <option value="0" {{ \App\Models\SystemSetting::get('decimal_places', 2) == 0 ? 'selected' : '' }}>0 (85%)</option>
                                        <option value="1" {{ \App\Models\SystemSetting::get('decimal_places', 2) == 1 ? 'selected' : '' }}>1 (85.5%)</option>
                                        <option value="2" {{ \App\Models\SystemSetting::get('decimal_places', 2) == 2 ? 'selected' : '' }}>2 (85.50%)</option>
                                        <option value="3" {{ \App\Models\SystemSetting::get('decimal_places', 2) == 3 ? 'selected' : '' }}>3 (85.500%)</option>
                                    </select>
                                    <small class="form-text text-muted">Number of decimal places to show in percentages</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rounding_method" class="font-weight-bold">Rounding Method</label>
                                    <select name="rounding_method" id="rounding_method" class="form-control custom-select">
                                        <option value="round" {{ \App\Models\SystemSetting::get('rounding_method', 'round') == 'round' ? 'selected' : '' }}>Round (85.5 → 86)</option>
                                        <option value="floor" {{ \App\Models\SystemSetting::get('rounding_method', 'round') == 'floor' ? 'selected' : '' }}>Floor (85.9 → 85)</option>
                                        <option value="ceil" {{ \App\Models\SystemSetting::get('rounding_method', 'round') == 'ceil' ? 'selected' : '' }}>Ceiling (85.1 → 86)</option>
                                    </select>
                                    <small class="form-text text-muted">How to handle decimal values in calculations</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 d-flex align-items-stretch">
        <div class="card h-100 w-100 shadow-sm">
            <div class="card-header bg-gradient-navy text-white">
                <h3 class="card-title">
                    <i class="fas fa-school"></i> School Information
                </h3>
            </div>
            <div class="card-body d-flex flex-column">
                <p>Update school details, academic year, and general settings.</p>
                <div class="mt-auto">
                    <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#schoolSettingsModal">
                        <i class="fas fa-cog"></i> Configure School
                    </button>
                </div>
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
            <form id="schoolSettingsForm" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">School Settings</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>School Logo</label>
                                <input type="file" name="settings[school_logo]" class="form-control-file" accept="image/*">
                                <small class="form-text text-muted">Upload school logo (JPG, PNG, SVG - Max: 2MB)</small>
                                @if(\App\Models\SystemSetting::get('school_logo'))
                                    <div class="mt-2">
                                        <img src="{{ asset(\App\Models\SystemSetting::get('school_logo')) }}" 
                                             alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                                        <p class="small text-muted">Current Logo</p>
                                        <form action="{{ route('settings.reset') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="key" value="school_logo">
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to remove logo?')">
                                                <i class="fas fa-trash"></i> Remove Logo
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Favicon</label>
                                <input type="file" name="settings[school_favicon]" class="form-control-file" accept="image/*">
                                <small class="form-text text-muted">Upload favicon (ICO, PNG - Max: 1MB)</small>
                                @if(\App\Models\SystemSetting::get('school_favicon'))
                                    <div class="mt-2">
                                        <img src="{{ asset(\App\Models\SystemSetting::get('school_favicon')) }}" 
                                             alt="Current Favicon" class="img-thumbnail" style="max-height: 50px;">
                                        <p class="small text-muted">Current Favicon</p>
                                        <form action="{{ route('settings.reset') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="key" value="school_favicon">
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to remove favicon?')">
                                                <i class="fas fa-trash"></i> Remove Favicon
                                            </button>
                                        </form>
                                    </div>
                                @endif
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
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Website URL</label>
                                <input type="url" name="settings[school_website]" class="form-control" 
                                       value="{{ \App\Models\SystemSetting::get('school_website', 'https://www.abcschool.edu') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Principal Name</label>
                                <input type="text" name="settings[school_principal]" class="form-control" 
                                       value="{{ \App\Models\SystemSetting::get('school_principal', 'Dr. John Smith') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
    <script src="{{ asset('js/settings.js') }}"></script>
@stop
