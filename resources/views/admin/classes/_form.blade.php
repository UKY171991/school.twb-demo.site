<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">Class Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $class->name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="school_id">School</label>
            <select class="form-control select2" id="school_id" name="school_id" required>
                <option value="">Select School</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ (isset($class) && $class->school_id == $school->id) ? 'selected' : '' }}>
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
            <label for="teacher_id">Teacher</label>
            <select class="form-control select2" id="teacher_id" name="teacher_id" required>
                <option value="">Select Teacher</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ (isset($class) && $class->teacher_id == $teacher->id) ? 'selected' : '' }}>
                        {{ $teacher->user->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="room_number">Room Number</label>
            <input type="text" class="form-control" id="room_number" name="room_number" value="{{ old('room_number', $class->room_number ?? '') }}" required>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="capacity">Capacity</label>
    <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', $class->capacity ?? '') }}" required min="1">
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $class->description ?? '') }}</textarea>
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', isset($class) && $class->is_active) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Active Status</label>
    </div>
</div>
