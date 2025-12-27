@extends('adminlte::page')

@section('title', 'Grading Settings')

@section('content_header')
    <h1>Grading System Settings</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Configure Grading System</h3>
            </div>
            <form action="{{ route('settings.grading.update') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="form-group">
                        <label for="current_grading_scheme">Current Grading Scheme</label>
                        <select name="current_grading_scheme" id="current_grading_scheme" class="form-control">
                            <option value="default" {{ $currentScheme == 'default' ? 'selected' : '' }}>Default System</option>
                            <option value="percentage" {{ $currentScheme == 'percentage' ? 'selected' : '' }}>Percentage Based</option>
                            <option value="points" {{ $currentScheme == 'points' ? 'selected' : '' }}>Points Based</option>
                            <option value="letter" {{ $currentScheme == 'letter' ? 'selected' : '' }}>Letter Grades</option>
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
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Current Grade Scale</h3>
            </div>
            <div class="card-body">
                @if($gradingSystems->count() > 0)
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Grade</th>
                                <th>Range</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gradingSystems as $grade)
                                <tr class="{{ $grade->is_active ? '' : 'text-muted' }}">
                                    <td>
                                        <strong>{{ $grade->grade }}</strong>
                                        @if($grade->grade_points)
                                            <small>({{ $grade->grade_points }})</small>
                                        @endif
                                    </td>
                                    <td>{{ $grade->min_percentage }}% - {{ $grade->max_percentage }}%</td>
                                    <td>
                                        @if($grade->is_passing)
                                            <span class="badge badge-success">Pass</span>
                                        @else
                                            <span class="badge badge-danger">Fail</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No grading system configured. Using default grades.</p>
                @endif
                
                <a href="{{ route('grading-systems.index') }}" class="btn btn-sm btn-primary btn-block">
                    <i class="fas fa-cog"></i> Manage Grade Scale
                </a>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('grading-systems.create') }}" class="btn btn-success btn-block">
                    <i class="fas fa-plus"></i> Add New Grade
                </a>
                <button class="btn btn-warning btn-block" onclick="resetToDefault()">
                    <i class="fas fa-undo"></i> Reset to Default
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function resetToDefault() {
    if (confirm('Are you sure you want to reset to default grading system? This will deactivate all custom grades.')) {
        // Implementation for resetting to default
        window.location.href = "{{ route('settings.grading') }}?reset=default";
    }
}
</script>
@stop