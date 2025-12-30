@extends('adminlte::page')

@section('title', 'Marking Settings')

@section('content_header')
    <h1>Marking System Settings</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-indigo text-white">
                <h3 class="card-title"><i class="fas fa-calculator mr-2"></i> Configure Marking System</h3>
            </div>
            <form action="{{ route('settings.marking.update') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="current_marking_scheme" class="font-weight-bold">Current Marking Scheme</label>
                                <select name="current_marking_scheme" id="current_marking_scheme" class="form-control custom-select">
                                    <option value="percentage" {{ $currentScheme == 'percentage' ? 'selected' : '' }}>Percentage Based</option>
                                    <option value="points" {{ $currentScheme == 'points' ? 'selected' : '' }}>Points Based</option>
                                    <option value="letter" {{ $currentScheme == 'letter' ? 'selected' : '' }}>Letter Grades</option>
                                </select>
                            </div>
                        </div>
                        
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

                    <hr class="my-4">

                    <h5 class="mb-4 text-indigo font-weight-bold">Additional Marking Options</h5>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch mb-3">
                            <input type="checkbox" class="custom-control-input" id="show_grade_points" name="show_grade_points" 
                                   {{ \App\Models\SystemSetting::get('show_grade_points', false) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-normal" for="show_grade_points">
                                <strong>Show Grade Points on Marksheets</strong>
                                <p class="text-muted small mb-0">Display grade points (e.g., 4.0, 3.7) alongside grades on student marksheets.</p>
                            </label>
                        </div>

                        <div class="custom-control custom-switch mb-3">
                            <input type="checkbox" class="custom-control-input" id="show_class_rank" name="show_class_rank" 
                                   {{ \App\Models\SystemSetting::get('show_class_rank', false) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-normal" for="show_class_rank">
                                <strong>Show Class Rank on Marksheets</strong>
                                <p class="text-muted small mb-0">Calculate and display the student's rank within their class section.</p>
                            </label>
                        </div>

                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="allow_negative_marks" name="allow_negative_marks" 
                                   {{ \App\Models\SystemSetting::get('allow_negative_marks', false) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-normal" for="allow_negative_marks">
                                <strong>Allow Negative Marks</strong>
                                <p class="text-muted small mb-0">Enable if tests can have negative marking (e.g., for incorrect answers).</p>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top">
                    <button type="submit" class="btn btn-primary px-4 mr-2">
                        <i class="fas fa-save mr-1"></i> Save Settings
                    </button>
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h3 class="card-title font-weight-bold text-muted">Marking Preview</h3>
            </div>
            <div class="card-body">
                <h6 class="font-weight-bold mb-3">Sample Calculations:</h6>
                <table class="table table-sm table-borderless">
                    <tr class="border-bottom">
                        <td class="text-muted">85.678%</td>
                        <td class="text-right font-weight-bold text-success" id="sample1">85.68%</td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="text-muted">92.345%</td>
                        <td class="text-right font-weight-bold text-success" id="sample2">92.35%</td>
                    </tr>
                    <tr>
                        <td class="text-muted">78.901%</td>
                        <td class="text-right font-weight-bold text-success" id="sample3">78.90%</td>
                    </tr>
                </table>
                <div class="alert alert-light border mt-3 mb-0">
                    <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> The preview updates automatically based on your customized Decimal Places and Rounding Method settings.</small>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h3 class="card-title font-weight-bold text-muted">Available Schemes</h3>
            </div>
            <div class="card-body p-0">
                @if($markingSchemes->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($markingSchemes as $scheme)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $scheme->name }}</span>
                                <span class="badge badge-{{ $scheme->is_active ? 'success' : 'secondary' }} badge-pill px-3">
                                    {{ $scheme->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="p-3 text-center text-muted">No custom marking schemes configured.</div>
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