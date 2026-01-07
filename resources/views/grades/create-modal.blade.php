<div class="row">
    <div class="col-12">
        <form action="{{ route('grades.store') }}" method="POST" id="gradeCreateForm">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-graduation-cap"></i> Class Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               id="name" placeholder="e.g., Class 1, Class 10, Nursery" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="section" class="form-label">
                            <i class="fas fa-th-large"></i> Section
                        </label>
                        <input type="text" name="section" class="form-control @error('section') is-invalid @enderror" 
                               id="section" placeholder="e.g., A, B, C" value="{{ old('section') }}" maxlength="1">
                        @error('section')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="capacity" class="form-label">
                            <i class="fas fa-users"></i> Maximum Capacity
                        </label>
                        <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" 
                               id="capacity" placeholder="e.g., 40" value="{{ old('capacity', 40) }}" min="1" max="100">
                        @error('capacity')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="teacher_id" class="form-label">
                            <i class="fas fa-chalkboard-teacher"></i> Class Teacher
                        </label>
                        <select name="teacher_id" id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i> Description
                        </label>
                        <textarea name="description" id="description" rows="2" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Enter class description...">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status" class="form-label">
                            <i class="fas fa-toggle-on"></i> Status
                        </label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-palette"></i> Class Color Theme
                        </label>
                        <div class="grade-color-selector">
                            @for($i = 1; $i <= 12; $i++)
                                <div class="color-option grade-{{ $i }} {{ old('grade_theme', 1) == $i ? 'selected' : '' }}" data-grade="{{ $i }}" title="Class {{ $i }}">
                                    {{ $i }}
                                </div>
                            @endfor
                        </div>
                        <input type="hidden" name="grade_theme" id="gradeTheme" value="{{ old('grade_theme', 1) }}">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Class
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.grade-color-selector {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    margin-top: 5px;
}

.color-option {
    width: 30px;
    height: 30px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    font-weight: bold;
    color: white;
    border: 2px solid transparent;
    transition: all 0.2s;
}

.color-option:hover {
    transform: scale(1.1);
}

.color-option.selected {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

/* Grade color styles */
.grade-1 { background-color: #007bff; }
.grade-2 { background-color: #28a745; }
.grade-3 { background-color: #dc3545; }
.grade-4 { background-color: #ffc107; }
.grade-5 { background-color: #17a2b8; }
.grade-6 { background-color: #6f42c1; }
.grade-7 { background-color: #e83e8c; }
.grade-8 { background-color: #fd7e14; }
.grade-9 { background-color: #20c997; }
.grade-10 { background-color: #6c757d; }
.grade-11 { background-color: #343a40; }
.grade-12 { background-color: #f8f9fa; color: #343a40; }
</style>

<script>
$(document).ready(function() {
    // Color selector functionality
    $('.color-option').on('click', function() {
        $('.color-option').removeClass('selected');
        $(this).addClass('selected');
        $('#gradeTheme').val($(this).data('grade'));
    });
});
</script>
