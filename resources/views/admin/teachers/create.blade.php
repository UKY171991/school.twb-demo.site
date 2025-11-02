@extends('layouts.school-admin')

@section('title', 'Add New Teacher')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add New Teacher</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">Teachers</a></li>
                        <li class="breadcrumb-item active">Add Teacher</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <form id="teacherForm" enctype="multipart/form-data">
                @csrf
                
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
                                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="middle_name">Middle Name</label>
                                            <input type="text" class="form-control" id="middle_name" name="middle_name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                            <small class="form-text text-muted">Will be used for teacher login access</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <input type="tel" class="form-control" id="phone" name="phone">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender">Gender <span class="text-danger">*</span></label>
                                            <select class="form-control" id="gender" name="gender" required>
                                                <option value="">Select Gender</option>
                                                @foreach($genders as $gender)
                                                    <option value="{{ $gender }}">{{ ucfirst($gender) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
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
                                    Teacher Photo
                                </h3>
                            </div>
                            <div class="card-body text-center">
                                <div class="photo-preview mb-3">
                                    <img id="photoPreview" src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" 
                                         alt="Teacher Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <div class="form-group">
                                    <input type="file" class="form-control-file" id="photo" name="photo" accept="image/*">
                                    <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-graduation-cap mr-2"></i>
                                    Professional Information
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="qualification">Qualification <span class="text-danger">*</span></label>
                                    <select class="form-control" id="qualification" name="qualification" required>
                                        <option value="">Select Qualification</option>
                                        @foreach($qualifications as $qualification)
                                            <option value="{{ $qualification }}">{{ $qualification }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="experience">Experience (Years) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="experience" name="experience" 
                                                   min="0" max="50" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="salary">Salary <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="salary" name="salary" 
                                                   min="0" step="0.01" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="joining_date">Joining Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="joining_date" name="joining_date" 
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chalkboard mr-2"></i>
                                    Teaching Assignments
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="subjects">Subjects</label>
                                    <select class="form-control select2" id="subjects" name="subjects[]" multiple>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Select subjects this teacher will teach</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="classes">Classes</label>
                                    <select class="form-control select2" id="classes" name="classes[]" multiple>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Select classes this teacher will handle</small>
                                </div>
                                
                                <div class="alert alert-info" id="workloadAlert" style="display: none;">
                                    <i class="fas fa-info-circle"></i>
                                    <span id="workloadMessage"></span>
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
                                        Create Teacher
                                    </button>
                                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times mr-1"></i>
                                        Cancel
                                    </a>
                                    <button type="button" class="btn btn-info ml-2" id="previewBtn">
                                        <i class="fas fa-eye mr-1"></i>
                                        Preview
                                    </button>
                                    <button type="button" class="btn btn-warning ml-2" id="checkConflictsBtn">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Check Schedule Conflicts
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
                <h4 class="modal-title">Teacher Information Preview</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="confirmCreate">
                    <i class="fas fa-check mr-1"></i>
                    Confirm & Create
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
</style>
@endpush

@push('scripts')
<script src="{{ asset('vendor/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(document).ready(function() {
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
    
    // Check workload when classes/subjects change
    $('#classes, #subjects').change(function() {
        checkWorkload();
    });
    
    // Form validation
    $('#teacherForm').on('submit', function(e) {
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
    
    // Confirm create from preview
    $('#confirmCreate').click(function() {
        $('#previewModal').modal('hide');
        submitForm();
    });
    
    // Check conflicts button
    $('#checkConflictsBtn').click(function() {
        checkScheduleConflicts();
    });
    
    function validateForm() {
        let isValid = true;
        
        // Clear previous error states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Required field validation
        const requiredFields = [
            'first_name', 'last_name', 'email', 'date_of_birth', 'gender',
            'qualification', 'experience', 'salary', 'joining_date'
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
        
        const joiningDate = new Date($('#joining_date').val());
        if (joiningDate > today) {
            showFieldError('joining_date', 'Joining date cannot be in the future');
            isValid = false;
        }
        
        // Experience validation
        const experience = parseInt($('#experience').val());
        if (experience < 0 || experience > 50) {
            showFieldError('experience', 'Experience must be between 0 and 50 years');
            isValid = false;
        }
        
        // Salary validation
        const salary = parseFloat($('#salary').val());
        if (salary < 0) {
            showFieldError('salary', 'Salary must be a positive number');
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
    
    function checkWorkload() {
        const classCount = $('#classes').val() ? $('#classes').val().length : 0;
        const subjectCount = $('#subjects').val() ? $('#subjects').val().length : 0;
        
        if (classCount > 0 || subjectCount > 0) {
            const workloadScore = (classCount * 10) + (subjectCount * 5);
            let message = `Estimated workload: ${classCount} classes, ${subjectCount} subjects`;
            let alertClass = 'alert-info';
            
            if (workloadScore > 80) {
                message += ' - <strong>High workload detected!</strong>';
                alertClass = 'alert-warning';
            } else if (workloadScore > 50) {
                message += ' - <strong>Moderate workload</strong>';
                alertClass = 'alert-info';
            } else {
                message += ' - <strong>Light workload</strong>';
                alertClass = 'alert-success';
            }
            
            $('#workloadAlert').removeClass('alert-info alert-warning alert-success').addClass(alertClass);
            $('#workloadMessage').html(message);
            $('#workloadAlert').show();
        } else {
            $('#workloadAlert').hide();
        }
    }
    
    function checkScheduleConflicts() {
        const classes = $('#classes').val();
        
        if (!classes || classes.length === 0) {
            showError('Please select classes to check for conflicts');
            return;
        }
        
        // For now, just show the workload check
        checkWorkload();
        showInfo('Schedule conflict checking is based on workload analysis');
    }
    
    function generatePreview() {
        const formData = new FormData($('#teacherForm')[0]);
        let previewHtml = '';
        
        // Personal Information
        previewHtml += `
            <div class="preview-section">
                <h5><i class="fas fa-user mr-2"></i>Personal Information</h5>
                <div class="row">
                    <div class="col-md-8">
                        <p><strong>Name:</strong> ${formData.get('first_name')} ${formData.get('middle_name') || ''} ${formData.get('last_name')}</p>
                        <p><strong>Email:</strong> ${formData.get('email')}</p>
                        <p><strong>Phone:</strong> ${formData.get('phone') || 'Not provided'}</p>
                        <p><strong>Date of Birth:</strong> ${formData.get('date_of_birth')}</p>
                        <p><strong>Gender:</strong> ${formData.get('gender')}</p>
                        <p><strong>Address:</strong> ${formData.get('address') || 'Not provided'}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="${$('#photoPreview').attr('src')}" alt="Teacher Photo" 
                             class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                </div>
            </div>
        `;
        
        // Professional Information
        previewHtml += `
            <div class="preview-section">
                <h5><i class="fas fa-graduation-cap mr-2"></i>Professional Information</h5>
                <p><strong>Qualification:</strong> ${formData.get('qualification')}</p>
                <p><strong>Experience:</strong> ${formData.get('experience')} years</p>
                <p><strong>Salary:</strong> $${formData.get('salary')}</p>
                <p><strong>Joining Date:</strong> ${formData.get('joining_date')}</p>
            </div>
        `;
        
        // Teaching Assignments
        const selectedSubjects = $('#subjects option:selected').map(function() { return $(this).text(); }).get().join(', ');
        const selectedClasses = $('#classes option:selected').map(function() { return $(this).text(); }).get().join(', ');
        
        previewHtml += `
            <div class="preview-section">
                <h5><i class="fas fa-chalkboard mr-2"></i>Teaching Assignments</h5>
                <p><strong>Subjects:</strong> ${selectedSubjects || 'None selected'}</p>
                <p><strong>Classes:</strong> ${selectedClasses || 'None selected'}</p>
            </div>
        `;
        
        $('#previewContent').html(previewHtml);
    }
    
    function submitForm() {
        const formData = new FormData($('#teacherForm')[0]);
        
        $.ajax({
            url: '{{ route("admin.teachers.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Creating...');
            },
            success: function(response) {
                if (response.success) {
                    showSuccess(response.message);
                    if (response.redirect) {
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 1500);
                    } else {
                        setTimeout(() => {
                            window.location.href = '{{ route("admin.teachers.index") }}';
                        }, 1500);
                    }
                } else {
                    showError(response.message);
                    $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Create Teacher');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Error creating teacher';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(field => {
                        showFieldError(field, errors[field][0]);
                    });
                    errorMessage = 'Please correct the errors and try again';
                }
                
                showError(errorMessage);
                $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Create Teacher');
            }
        });
    }
    
    function showSuccess(message) {
        toastr.success(message);
    }
    
    function showError(message) {
        toastr.error(message);
    }
    
    function showInfo(message) {
        toastr.info(message);
    }
});
</script>
@endpush