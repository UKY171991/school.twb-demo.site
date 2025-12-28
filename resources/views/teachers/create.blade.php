@extends('adminlte::page')

@section('title', 'Add Teacher')

@section('content_header')
    <h1><i class="fas fa-chalkboard-teacher"></i> Add New Teacher</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-plus"></i> Teacher Information</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data" id="teacherForm">
            @csrf
            <div class="card-body">
                <!-- School Selection -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="school_id" class="form-label">
                                <i class="fas fa-school"></i> School <span class="text-danger">*</span>
                            </label>
                            <select name="school_id" id="school_id" class="form-control @error('school_id') is-invalid @enderror" required>
                                <option value="">Select School</option>
                                @if(isset($schools))
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('school_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i> Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" placeholder="Enter teacher's full name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" placeholder="teacher@school.com" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone"></i> Phone Number
                            </label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" placeholder="+1234567890" value="{{ old('phone') }}">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="gender" class="form-label">
                                <i class="fas fa-venus-mars"></i> Gender <span class="text-danger">*</span>
                            </label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                    <i class="fas fa-mars"></i> Male
                                </option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                    <i class="fas fa-venus"></i> Female
                                </option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>
                                    <i class="fas fa-genderless"></i> Other
                                </option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_of_birth" class="form-label">
                                <i class="fas fa-birthday-cake"></i> Date of Birth
                            </label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_joining" class="form-label">
                                <i class="fas fa-calendar-alt"></i> Date of Joining
                            </label>
                            <input type="date" name="date_of_joining" class="form-control @error('date_of_joining') is-invalid @enderror" 
                                   id="date_of_joining" value="{{ old('date_of_joining') ?? date('Y-m-d') }}">
                            @error('date_of_joining')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </label>
                            <textarea name="address" id="address" rows="1" 
                                      class="form-control @error('address') is-invalid @enderror" 
                                      placeholder="Enter complete address">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="image" class="form-label">
                                <i class="fas fa-camera"></i> Teacher Photo
                            </label>
                            <div class="custom-file-upload">
                                <div class="upload-area" id="uploadArea">
                                    <div class="upload-content">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <h6>Click to upload or drag and drop</h6>
                                        <p class="text-muted small">PNG, JPG, GIF up to 2MB</p>
                                        <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror" 
                                               id="image" accept="image/*" style="display: none;">
                                    </div>
                                    <div class="preview-area" id="previewArea" style="display: none;">
                                        <img id="imagePreview" src="" alt="Preview" class="img-thumbnail">
                                        <div class="preview-info">
                                            <h6 id="fileName"></h6>
                                            <button type="button" class="btn btn-sm btn-danger" id="removeImage">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Save Teacher
                </button>
                <a href="{{ route('teachers.index') }}" class="btn btn-default">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="reset" class="btn btn-warning">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
            </div>
        </form>
    </div>
@stop

@section('css')
<style>
.custom-file-upload {
    margin-bottom: 1rem;
}

.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #007bff;
    background-color: #e3f2fd;
}

.upload-area.dragover {
    border-color: #007bff;
    background-color: #e3f2fd;
    transform: scale(1.02);
}

.upload-content {
    transition: opacity 0.3s ease;
}

.preview-area {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background-color: #fff;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.preview-area img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}

.preview-info h6 {
    margin: 0 0 0.5rem 0;
    font-weight: 600;
    color: #333;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-label i {
    margin-right: 0.5rem;
    color: #6c757d;
}

.card-header .card-title i {
    margin-right: 0.5rem;
}

.btn-tool {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.invalid-feedback {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Loading state */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.btn.loading {
    position: relative;
}

.btn.loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    margin: auto;
    border: 2px solid transparent;
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .preview-area {
        flex-direction: column;
        text-align: center;
    }
    
    .upload-area {
        padding: 1rem;
    }
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // File upload functionality
    const uploadArea = $('#uploadArea');
    const fileInput = $('#image');
    const uploadContent = $('.upload-content');
    const previewArea = $('#previewArea');
    const imagePreview = $('#imagePreview');
    const fileName = $('#fileName');
    const removeImageBtn = $('#removeImage');
    const submitBtn = $('#submitBtn');
    const teacherForm = $('#teacherForm');

    // Click to upload
    uploadArea.on('click', function(e) {
        if (e.target !== removeImageBtn[0] && !removeImageBtn.has(e.target).length) {
            fileInput.click();
        }
    });

    // Drag and drop functionality
    uploadArea.on('dragover', function(e) {
        e.preventDefault();
        uploadArea.addClass('dragover');
    });

    uploadArea.on('dragleave', function(e) {
        e.preventDefault();
        uploadArea.removeClass('dragover');
    });

    uploadArea.on('drop', function(e) {
        e.preventDefault();
        uploadArea.removeClass('dragover');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelect(files[0]);
        }
    });

    // File input change
    fileInput.on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelect(file);
        }
    });

    // Remove image
    removeImageBtn.on('click', function(e) {
        e.stopPropagation();
        resetImageUpload();
    });

    // Handle file selection
    function handleFileSelect(file) {
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file (JPG, PNG, GIF)');
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            return;
        }

        // Read and display preview
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.attr('src', e.target.result);
            fileName.text(file.name);
            uploadContent.hide();
            previewArea.show();
        };
        reader.readAsDataURL(file);
    }

    // Reset image upload
    function resetImageUpload() {
        fileInput.val('');
        uploadContent.show();
        previewArea.hide();
        imagePreview.attr('src', '');
        fileName.text('');
    }

    // Form validation
    teacherForm.on('submit', function(e) {
        e.preventDefault();
        
        // Remove previous error states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Basic validation
        let isValid = true;
        const requiredFields = ['school_id', 'name', 'email', 'gender'];
        
        requiredFields.forEach(fieldName => {
            const field = $(`#${fieldName}`);
            const value = field.val().trim();
            
            if (!value) {
                field.addClass('is-invalid');
                field.after('<span class="invalid-feedback">This field is required.</span>');
                isValid = false;
            }
        });

        // Email validation
        const emailField = $('#email');
        const email = emailField.val().trim();
        if (email && !isValidEmail(email)) {
            emailField.addClass('is-invalid');
            emailField.after('<span class="invalid-feedback">Please enter a valid email address.</span>');
            isValid = false;
        }

        if (isValid) {
            // Show loading state
            submitBtn.addClass('loading').prop('disabled', true);
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            
            // Submit form
            this.submit();
        }
    });

    // Email validation helper
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Real-time validation
    $('.form-control').on('blur', function() {
        const field = $(this);
        const value = field.val().trim();
        
        // Remove previous error state
        field.removeClass('is-invalid');
        field.next('.invalid-feedback').remove();
        
        // Check if required field is empty
        if (field.prop('required') && !value) {
            field.addClass('is-invalid');
            field.after('<span class="invalid-feedback">This field is required.</span>');
        }
        
        // Email validation
        if (field.attr('type') === 'email' && value && !isValidEmail(value)) {
            field.addClass('is-invalid');
            field.after('<span class="invalid-feedback">Please enter a valid email address.</span>');
        }
    });

    // Phone number formatting
    $('#phone').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 3) {
                value = value;
            } else if (value.length <= 6) {
                value = value.slice(0, 3) + '-' + value.slice(3);
            } else {
                value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
            }
        }
        $(this).val(value);
    });

    // Auto-resize address textarea
    $('#address').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>
@stop
