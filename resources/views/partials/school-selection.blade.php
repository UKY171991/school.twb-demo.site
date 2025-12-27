@php
    $schools = \App\Models\School::active()->get();
    $currentSchool = $currentSchool ?? null;
    $selectedSchoolId = $selectedSchoolId ?? ($currentSchool ? $currentSchool->id : null);
    $fieldName = $fieldName ?? 'school_id';
    $required = $required ?? true;
    $showLabel = $showLabel ?? true;
@endphp

@if($schools->count() > 1)
    @if($showLabel)
    <div class="form-group">
        <label for="{{ $fieldName }}">School <span class="text-danger">{{ $required ? '*' : '' }}</span></label>
        <select name="{{ $fieldName }}" id="{{ $fieldName }}" class="form-control @error($fieldName) is-invalid @enderror" {{ $required ? 'required' : '' }}>
            <option value="">Select School</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ $selectedSchoolId == $school->id ? 'selected' : '' }}>
                    {{ $school->name }}
                    @if($school->address)
                        - {{ $school->address }}
                    @endif
                </option>
            @endforeach
        </select>
        @error($fieldName)
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    @else
    <select name="{{ $fieldName }}" id="{{ $fieldName }}" class="form-control @error($fieldName) is-invalid @enderror" {{ $required ? 'required' : '' }}>
        <option value="">Select School</option>
        @foreach($schools as $school)
            <option value="{{ $school->id }}" {{ $selectedSchoolId == $school->id ? 'selected' : '' }}>
                {{ $school->name }}
                @if($school->address)
                    - {{ $school->address }}
                @endif
            </option>
        @endforeach
    </select>
    @error($fieldName)
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
    @endif
@else
    <!-- Auto-select single school -->
    <input type="hidden" name="{{ $fieldName }}" value="{{ $schools->first()->id ?? '' }}">
    @if($showLabel && $schools->count() == 1)
    <div class="form-group">
        <label>School</label>
        <input type="text" class="form-control" value="{{ $schools->first()->name }}" readonly>
        <small class="form-text text-muted">Only one school available</small>
    </div>
    @endif
@endif