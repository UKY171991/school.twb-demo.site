<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $student->user->name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $student->user->email ?? '') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" {{ isset($student) ? '' : 'required' }}>
            @if(isset($student))
                <small class="form-text text-muted">Leave blank to keep the current password.</small>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" class="form-control" id="student_id" name="student_id" value="{{ old('student_id', $student->student_id ?? '') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="school_id">School</label>
            <select class="form-control select2" id="school_id" name="school_id" required>
                <option value="">Select School</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ (isset($student) && $student->school_id == $school->id) ? 'selected' : '' }}>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="class_id">Class</label>
            <select class="form-control select2" id="class_id" name="class_id" required>
                <option value="">Select Class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ (isset($student) && $student->class_id == $class->id) ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $student->phone ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="male" {{ (isset($student) && $student->gender == 'male') ? 'selected' : '' }}>Male</option>
                <option value="female" {{ (isset($student) && $student->gender == 'female') ? 'selected' : '' }}>Female</option>
            </select>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="address">Address</label>
    <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $student->address ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', isset($student) ? $student->date_of_birth->format('Y-m-d') : '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="admission_date">Admission Date</label>
            <input type="date" class="form-control" id="admission_date" name="admission_date" value="{{ old('admission_date', isset($student) ? $student->admission_date->format('Y-m-d') : '') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="guardian_name">Guardian Name</label>
            <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="guardian_phone">Guardian Phone</label>
            <input type="text" class'="form-control" id="guardian_phone" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone ?? '') }}" required>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', isset($student) && $student->is_active) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Active Status</label>
    </div>
</div>
