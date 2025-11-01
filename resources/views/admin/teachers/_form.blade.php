<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $teacher->user->name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $teacher->user->email ?? '') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" {{ isset($teacher) ? '' : 'required' }}>
            @if(isset($teacher))
                <small class="form-text text-muted">Leave blank to keep the current password.</small>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="school_id">School</label>
            <select class="form-control select2" id="school_id" name="school_id" required>
                <option value="">Select School</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ (isset($teacher) && $teacher->school_id == $school->id) ? 'selected' : '' }}>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="employee_id">Employee ID</label>
            <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{ old('employee_id', $teacher->employee_id ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $teacher->phone ?? '') }}" required>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="address">Address</label>
    <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $teacher->address ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="qualification">Qualification</label>
            <input type="text" class="form-control" id="qualification" name="qualification" value="{{ old('qualification', $teacher->qualification ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="subject_specialization">Subject Specialization</label>
            <input type="text" class="form-control" id="subject_specialization" name="subject_specialization" value="{{ old('subject_specialization', $teacher->subject_specialization ?? '') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="experience_years">Experience (Years)</label>
            <input type="number" class="form-control" id="experience_years" name="experience_years" value="{{ old('experience_years', $teacher->experience_years ?? '0') }}" required min="0">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="salary">Salary</label>
            <input type="number" class="form-control" id="salary" name="salary" value="{{ old('salary', $teacher->salary ?? '0.00') }}" required min="0" step="0.01">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="joining_date">Joining Date</label>
            <input type="date" class="form-control" id="joining_date" name="joining_date" value="{{ old('joining_date', isset($teacher) ? $teacher->joining_date->format('Y-m-d') : '') }}" required>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', isset($teacher) && $teacher->is_active) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Active Status</label>
    </div>
</div>
