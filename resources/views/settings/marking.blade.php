@extends('adminlte::page')

@section('title', 'Marking Settings')

@section('content_header')
    <h1>Marking System Settings</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Configure Marking System</h3>
            </div>
            <form action="{{ route('settings.marking.update') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="form-group">
                        <label for="current_marking_scheme">Current Marking Scheme</label>
                        <select name="current_marking_scheme" id="current_marking_scheme" class="form-control">
                            <option value="percentage" {{ $currentScheme == 'percentage' ? 'selected' : '' }}>Percentage Based</option>
                            <option value="points" {{ $currentScheme == 'points' ? 'selected' : '' }}>Points Based</option>
                            <option value="letter" {{ $currentScheme == 'letter' ? 'selected' : '' }}>Letter Grades</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="decimal_places">Decimal Places</label>
                        <select name="decimal_places" id="decimal_places" class="form-control">
                            <option value="0" {{ \App\Models\SystemSetting::get('decimal_places', 2) == 0 ? 'selected' : '' }}>0 (85%)</option>
                            <option value="1" {{ \App\Models\SystemSetting::get('decimal_places', 2) == 1 ? 'selected' : '' }}>1 (85.5%)</option>
                            <option value="2" {{ \App\Models\SystemSetting::get('decimal_places', 2) == 2 ? 'selected' : '' }}>2 (85.50%)</option>
                            <option value="3" {{ \App\Models\SystemSetting::get('decimal_places', 2) == 3 ? 'selected' : '' }}>3 (85.500%)</option>
                        </select>
                        <small class="form-text text-muted">Number of decimal places to show in percentages</small>
                    </div>

                    <div class="form-group">
                        <label for="rounding_method">Rounding Method</label>
                        <select name="rounding_method" id="rounding_method" class="form-control">
                            <option value="round" {{ \App\Models\SystemSetting::get('rounding_method', 'round') == 'round' ? 'selected' : '' }}>Round (85.5 → 86)</option>
                            <option value="floor" {{ \App\Models\SystemSetting::get('rounding_method', 'round') == 'floor' ? 'selected' : '' }}>Floor (85.9 → 85)</option>
                            <option value="ceil" {{ \App\Models\SystemSetting::get('rounding_method', 'round') == 'ceil' ? 'selected' : '' }}>Ceiling (85.1 → 86)</option>
                        </select>
                        <small class="form-text text-muted">How to handle decimal values in calculations</small>
                    </div>

                    <hr>

                    <h5>Additional Marking Options</h5>
                    
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="show_grade_points" name="show_grade_points" 
                               {{ \App\Models\SystemSetting::get('show_grade_points', false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="show_grade_points">
                            Show Grade Points on Marksheets
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="show_class_rank" name="show_class_rank" 
                               {{ \App\Models\SystemSetting::get('show_class_rank', false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="show_class_rank">
                            Show Class Rank on Marksheets
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="allow_negative_marks" name="allow_negative_marks" 
                               {{ \App\Models\SystemSetting::get('allow_negative_marks', false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="allow_negative_marks">
                            Allow Negative Marks
                        </label>
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
                <h3 class="card-title">Marking Preview</h3>
            </div>
            <div class="card-body">
                <h6>Sample Calculations:</h6>
                <table class="table table-sm">
                    <tr>
                        <td>85.678%</td>
                        <td id="sample1">85.68%</td>
                    </tr>
                    <tr>
                        <td>92.345%</td>
                        <td id="sample2">92.35%</td>
                    </tr>
                    <tr>
                        <td>78.901%</td>
                        <td id="sample3">78.90%</td>
                    </tr>
                </table>
                <small class="text-muted">Preview updates based on your settings</small>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Available Schemes</h3>
            </div>
            <div class="card-body">
                @if($markingSchemes->count() > 0)
                    @foreach($markingSchemes as $scheme)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $scheme->name }}</span>
                            <span class="badge badge-{{ $scheme->is_active ? 'success' : 'secondary' }}">
                                {{ $scheme->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No custom marking schemes configured.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    
    document.getElementById('decimal_places').addEventListener('change', updatePreview);
    document.getElementById('rounding_method').addEventListener('change', updatePreview);
});

function updatePreview() {
    const decimalPlaces = parseInt(document.getElementById('decimal_places').value);
    const roundingMethod = document.getElementById('rounding_method').value;
    
    const samples = [85.678, 92.345, 78.901];
    
    samples.forEach((value, index) => {
        let result;
        
        switch(roundingMethod) {
            case 'floor':
                result = Math.floor(value * Math.pow(10, decimalPlaces)) / Math.pow(10, decimalPlaces);
                break;
            case 'ceil':
                result = Math.ceil(value * Math.pow(10, decimalPlaces)) / Math.pow(10, decimalPlaces);
                break;
            default:
                result = Math.round(value * Math.pow(10, decimalPlaces)) / Math.pow(10, decimalPlaces);
        }
        
        document.getElementById('sample' + (index + 1)).textContent = result.toFixed(decimalPlaces) + '%';
    });
}
</script>
@stop