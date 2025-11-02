@extends('layouts.school-admin')

@section('title', 'Edit Student - ' . $student->full_name)

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Student</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.students.show', $student) }}">{{ $student->full_name }}</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <form id="studentEditForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Personal Information -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-user mr-2"></i>
                                    Personal Information
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" 
                                                   value="{{ $student->first_name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="middle_name">Middle Name</label>
                                            <input type="text" class="form-control" id="middle_name" name="middle_name" 
                                                   value="{{ $student->middle_name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" 
                                                   value="{{ $student->last_name }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                                   value="{{ $student->date_of_birth->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="gender">Gender <span class="text-danger">*</span></label>
                                            <select class="form-control" id="gender" name="gender" required>
                                                <option value="">Select Gender</option>
                                                @foreach($genders as $gender)
                                                    <option value="{{ $gender }}" {{ $student->gender === $gender ? 'selected' : '' }}>
                                                        {{ ucfirst($gender) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="blood_group">Blood Group</label>
                                            <select class="form-control" id="blood_group" name="blood_group">
                                                <option value="">Select Blood Group</option>
                                                @foreach($bloodGroups as $group)
                                                    <option value="{{ $group }}" {{ $student->blood_group === $group ? 'selected' : '' }}>
                                                        {{ $group }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="{{ $student->email }}">
                                            <small class="form-text text-muted">Optional - for student login access</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" 
                                                   value="{{ $student->phone }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3">{{ $student->address }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Photo Upload -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-camera mr-2"></i>
                                    Student Photo
                                </h3>
                            </div>
                            <div class="card-body text-center">
                                <div class="photo-preview mb-3">
                                    <img id="photoPreview" src="{{ $student->photo_url }}" 
                                         alt="Student Photo" class="img-fluid rounded-circle" 
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <div class="form-group">
                                    <input type="file" class="form-control-file" id="photo" name="photo" accept="image/*">
                                    <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG</small>
                                </div>
                                @if($student->photo)
                                    <button type="button" class="btn btn-sm btn-danger" id="removePhoto">
                                        <i class="fas fa-trash mr-1"></i>
                                        Remove Photo
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-graduation-cap mr-2"></i>
                                    Academic Information
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="class_id">Class <span class="text-danger">*</span></label>
                                    <select class="form-control" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ $student->class_id == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="admission_date">Admission Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="admission_date" name="admission_date" 
                                           value="{{ $student->admission_date->format('Y-m-d') }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="status" name="status" required>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ $student->status === $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="subjects">Subjects</label>
                                    <select class="form-control select2" id="subjects" name="subjects[]" multiple>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                {{ $student->subjects->contains($subject->id) ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Select subjects for this student</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-users mr-2"></i>
                                    Parent & Emergency Contact
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="parent_id">Parent/Guardian</label>
                                    <select class="form-control select2" id="parent_id" name="parent_id">
                                        <option value="">Select Parent</option>
                                        @foreach($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ $student->parent_id == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->user->name ?? 'Unknown' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        <a href="{{ route('admin.parents.create') }}" target="_blank">Add new parent</a>
                                    </small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="emergency_contact">Emergency Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" 
                                           value="{{ $student->emergency_contact }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="emergency_phone">Emergency Contact Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="emergency_phone" name="emergency_phone" 
                                           value="{{ $student->emergency_phone }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save mr-1"></i>
                                        Update Student
                                    </button>
                                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times mr-1"></i>
                                        Cancel
                                    </a>
                                    <button type="button" class="btn btn-info ml-2" id="previewBtn">
                                        <i class="fas fa-eye mr-1"></i>
                                        Preview Changes
                                    </button>
                                    <button type="button" class="btn btn-warning ml-2" id="resetForm">
                                        <i class="fas fa-undo mr-1"></i>
                                        Reset Form
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>

        </div>
    </section>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Preview Changes</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="confirmUpdate">
                    <i class="fas fa-check mr-1"></i>
                    Confirm & Update
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
.photo-preview {
    border: 2px dashed #ddd;
    border-radius: 10px;
    padding: 10px;
    transition: border-color 0.3s ease;
}

.photo-preview:hover {
    border-color: #007bff;
}

.form-group label {
    font-weight: 600;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-header .card-title {
    color: white;
}

.preview-section {
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
}

.preview-section h5 {
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    padding-bottom: 5px;
    margin-bottom: 15px;
}

.change-highlight {
    background-color: #fff3cd;
    padding: 2px 4px;
    border-radius: 3px;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('vendor/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Store original form data for comparison
    const originalData = new FormData($('#studentEditForm')[0]);
    
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Select options...'
    });
    
    // Photo preview
    $('#photo').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#photoPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Remove photo
    $('#removePhoto').click(function() {
        if (confirm('Are you sure you want to remove the current photo?')) {
            $('#photoPreview').attr('src', '{{ asset("vendor/adminlte/dist/img/user2-160x160.jpg") }}');
            $('#photo').val('');
            $(this).hide();
        }
    });
    
    // Form validation and submission
    $('#studentEditForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        submitForm();
    });
    
    // Preview button
    $('#previewBtn').click(function() {
        if (!validateForm()) {
            return;
        }
        
        generatePreview();
        $('#previewModal').modal('show');
    });
    
    // Confirm update from preview
    $('#confirmUpdate').click(function() {
        $('#previewModal').modal('hide');
        submitForm();
    });
    
    // Reset form
    $('#resetForm').click(function() {
        if (confirm('Are you sure you want to reset all changes?')) {
            location.reload();
        }
    });
    
    function validateForm() {
        let isValid = true;
        
        // Clear previous error states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Required field validation
        const requiredFields = [
            'first_name', 'last_name', 'date_of_birth', 'gender', 
            'class_id', 'admission_date', 'status', 'emergency_contact', 'emergency_phone'
        ];
        
        requiredFields.forEach(field => {
            const value = $(`#${field}`).val();
            if (!value || value.trim() === '') {
                showFieldError(field, 'This field is required');
                isValid = false;
            }
        });
        
        // Email validation
        const email = $('#email').val();
        if (email && !isValidEmail(email)) {
            showFieldError('email', 'Please enter a valid email address');
            isValid = false;
        }
        
        // Date validation
        const dob = new Date($('#date_of_birth').val());
        const today = new Date();
        if (dob >= today) {
            showFieldError('date_of_birth', 'Date of birth must be in the past');
            isValid = false;
        }
        
        const admissionDate = new Date($('#admission_date').val());
        if (admissionDate > today) {
            showFieldError('admission_date', 'Admission date cannot be in the future');
            isValid = false;
        }
        
        return isValid;
    }
    
    function showFieldError(fieldName, message) {
        const field = $(`#${fieldName}`);
        field.addClass('is-invalid');
        field.after(`<div class="invalid-feedback">${message}</div>`);
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function generatePreview() {
        const formData = new FormData($('#studentEditForm')[0]);
        let previewHtml = '';
        
        // Personal Information
        previewHtml += `
            <div class="preview-section">
                <h5><i class="fas fa-user mr-2"></i>Personal Information</h5>
                <div class="row">
                    <div class="col-md-8">
                        <p><strong>Name:</strong> ${formData.get('first_name')} ${formData.get('middle_name') || ''} ${formData.get('last_name')}</p>
                        <p><strong>Date of Birth:</strong> ${formData.get('date_of_birth')}</p>
                        <p><strong>Gender:</strong> ${formData.get('gender')}</p>
                        <p><strong>Blood Group:</strong> ${formData.get('blood_group') || 'Not specified'}</p>
                        <p><strong>Email:</strong> ${formData.get('email') || 'Not provided'}</p>
                        <p><strong>Phone:</strong> ${formData.get('phone') || 'Not provided'}</p>
                        <p><strong>Address:</strong> ${formData.get('address') || 'Not provided'}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="${$('#photoPreview').attr('src')}" alt="Student Photo" 
                             class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                </div>
            </div>
        `;
        
        // Academic Information
        const className = $('#class_id option:selected').text();
        const status = $('#status option:selected').text();
        const selectedSubjects = $('#subjects option:selected').map(function() { return $(this).text(); }).get().join(', ');
        
        previewHtml += `
            <div class="preview-section">
                <h5><i class="fas fa-graduation-cap mr-2"></i>Academic Information</h5>
                <p><strong>Class:</strong> ${className}</p>
                <p><strong>Status:</strong> ${status}</p>
                <p><strong>Admission Date:</strong> ${formData.get('admission_date')}</p>
                <p><strong>Subjects:</strong> ${selectedSubjects || 'None selected'}</p>
            </div>
        `;
        
        // Parent & Emergency Contact
        const parentName = $('#parent_id option:selected').text();
        
        previewHtml += `
            <div class="preview-section">
                <h5><i class="fas fa-users mr-2"></i>Parent & Emergency Contact</h5>
                <p><strong>Parent/Guardian:</strong> ${parentName !== 'Select Parent' ? parentName : 'Not assigned'}</p>
                <p><strong>Emergency Contact:</strong> ${formData.get('emergency_contact')}</p>
                <p><strong>Emergency Phone:</strong> ${formData.get('emergency_phone')}</p>
            </div>
        `;
        
        $('#previewContent').html(previewHtml);
    }
    
    function submitForm() {
        const formData = new FormData($('#studentEditForm')[0]);
        
        $.ajax({
            url: '{{ route("admin.students.update", $student) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Updating...');
            },
            success: function(response) {
                if (response.success) {
                    showSuccess(response.message);
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.students.show", $student) }}';
                    }, 1500);
                } else {
                    showError(response.message);
                    $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Update Student');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Error updating student';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(field => {
                        showFieldError(field, errors[field][0]);
                    });
                    errorMessage = 'Please correct the errors and try again';
                }
                
                showError(errorMessage);
                $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Update Student');
            }
        });
    }
    
    function showSuccess(message) {
        toastr.success(message);
    }
    
    function showError(message) {
        toastr.error(message);
    }
});
</script>
@endpush