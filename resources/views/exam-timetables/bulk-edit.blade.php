@extends('adminlte::page')

@section('title', 'Bulk Edit Exam Timetables')

@section('content_header')
    <h1>Bulk Edit Exam Timetables</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Multiple Timetable Entries</h3>
        <div class="card-tools">
            <a href="{{ route('exam-timetables.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    
    <form action="{{ route('exam-timetables.bulk-update') }}" method="POST" id="bulkEditForm">
        @csrf
        @method('PUT')
        
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        You are editing {{ count($timetables) }} timetable entries. Make your changes and click "Update All" to save.
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th width="25%">Exam Type</th>
                            <th width="25%">Class</th>
                            <th width="25%">Section</th>
                            <th width="25%">Academic Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetables as $index => $timetable)
                            <tr>
                                <input type="hidden" name="timetables[{{ $index }}][original_exam_type_id]" value="{{ $timetable->exam_type_id }}">
                                <input type="hidden" name="timetables[{{ $index }}][original_class]" value="{{ $timetable->class }}">
                                <input type="hidden" name="timetables[{{ $index }}][original_section]" value="{{ $timetable->section }}">
                                <input type="hidden" name="timetables[{{ $index }}][original_academic_year]" value="{{ $timetable->academic_year }}">
                                
                                <td>
                                    <select name="timetables[{{ $index }}][exam_type_id]" class="form-control form-control-sm" required>
                                        @foreach($examTypes as $examType)
                                            <option value="{{ $examType->id }}" {{ $timetable->exam_type_id == $examType->id ? 'selected' : '' }}>
                                                {{ $examType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                
                                <td>
                                    <select name="timetables[{{ $index }}][class]" class="form-control form-control-sm" required>
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade->name }}" {{ $timetable->class == $grade->name ? 'selected' : '' }}>
                                                {{ $grade->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                
                                <td>
                                    <input type="text" name="timetables[{{ $index }}][section]" class="form-control form-control-sm" 
                                           value="{{ $timetable->section }}" placeholder="Section">
                                </td>
                                
                                <td>
                                    <input type="text" name="timetables[{{ $index }}][academic_year]" class="form-control form-control-sm" 
                                           value="{{ $timetable->academic_year }}" required>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
                <div class="col-md-6 text-right">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to update all selected timetable entries?')">
                        <i class="fas fa-save"></i> Update All ({{ count($timetables) }} entries)
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')
<style>
.table td {
    vertical-align: middle;
}
.form-control-sm {
    font-size: 0.875rem;
}
.form-label {
    font-weight: 600;
    margin-bottom: 0.25rem;
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Add validation for end time being after start time
    $('input[name*="[start_time]"], input[name*="[end_time]"]').on('change', function() {
        const row = $(this).closest('tr');
        const startTime = row.find('input[name*="[start_time]"]').val();
        const endTime = row.find('input[name*="[end_time]"]').val();
        
        if (startTime && endTime && startTime >= endTime) {
            alert('End time must be after start time');
            $(this).focus();
        }
    });
});
</script>
@stop