<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $parent->user->name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $parent->user->email ?? '') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" {{ isset($parent) ? '' : 'required' }}>
            @if(isset($parent))
                <small class="form-text text-muted">Leave blank to keep the current password.</small>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $parent->phone ?? '') }}" required>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="address">Address</label>
    <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $parent->address ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="occupation">Occupation</label>
            <input type="text" class="form-control" id="occupation" name="occupation" value="{{ old('occupation', $parent->occupation ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="relationship">Relationship to Student(s)</label>
            <input type="text" class="form-control" id="relationship" name="relationship" value="{{ old('relationship', $parent->relationship ?? '') }}" required>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="students">Children/Students</label>
    <select class="form-control select2" id="students" name="students[]" multiple="multiple" required>
        @foreach($students as $student)
            <option value="{{ $student->id }}" {{ (isset($parent) && $parent->students->contains($student->id)) ? 'selected' : '' }}>
                {{ $student->user->name }} ({{ $student->student_id }})
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', isset($parent) && $parent->is_active) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Active Status</label>
    </div>
</div>
