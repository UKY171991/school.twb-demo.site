@extends('layouts.student')

@section('title', 'Edit Profile')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.profile.show') }}">Profile</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Profile Photo -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-camera mr-2"></i>
                                Profile Photo
                            </h3>
                        </div>
                        <div class="card-body text-center">
                            <div class="profile-photo-container mb-3">
                                <img id="profilePhotoPreview" 
                                     src="{{ $student->photo_url }}" 
                                     alt="Profile Photo" 
                                     class="img-fluid img-circle"
                                     style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ddd;">
                            </div>
                            
                            <div class="form-group">
                                <label for="photo" class="btn btn-primary btn-sm">
                                    <i class="fas fa-upload mr-1"></i> Choose Photo
                                </label>
                                <input type="file" 
                                       id="photo" 
                                       name="photo" 
                                       class="d-none @error('photo') is-invalid @enderror"
                                       accept="image/*"
                                       onchange="previewPhoto(this)">
                                @error('photo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <small class="text-muted">
                                Allowed formats: JPEG, PNG, JPG, GIF<br>
                                Maximum size: 2MB
                            </small>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-cogs mr-2"></i>
                                Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-info btn-block" onclick="showPasswordModal()">
                                    <i class="fas fa-key mr-1"></i> Change Password
                                </button>
                                <button type="button" class="btn btn-warning btn-block" onclick="showPreferencesModal()">
                                    <i class="fas fa-sliders-h mr-1"></i> Preferences
                                </button>
                                <a href="{{ route('student.profile.show') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-arrow-left mr-1"></i> Back to Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="col-md-8">
                    <!-- Personal Information -->
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
                                        <input type="text" 
                                               class="form-control @error('first_name') is-invalid @enderror" 
                                               id="first_name" 
                                               name="first_name" 
                                               value="{{ old('first_name', $student->first_name) }}" 
                                               required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="middle_name">Middle Name</label>
                                        <input type="text" 
                                               class="form-control @error('middle_name') is-invalid @enderror" 
                                               id="middle_name" 
                                               name="middle_name" 
                                               value="{{ old('middle_name', $student->middle_name) }}">
                                        @error('middle_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('last_name') is-invalid @enderror" 
                                               id="last_name" 
                                               name="last_name" 
                                               value="{{ old('last_name', $student->last_name) }}" 
                                               required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_of_birth">Date of Birth</label>
                                        <input type="date" 
                                               class="form-control @error('date_of_birth') is-invalid @enderror" 
                                               id="date_of_birth" 
                                               name="date_of_birth" 
                                               value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control @error('gender') is-invalid @enderror" 
                                                id="gender" 
                                                name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $student->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="blood_group">Blood Group</label>
                                        <select class="form-control @error('blood_group') is-invalid @enderror" 
                                                id="blood_group" 
                                                name="blood_group">
                                            <option value="">Select Blood Group</option>
                                            <option value="A+" {{ old('blood_group', $student->blood_group) === 'A+' ? 'selected' : '' }}>A+</option>
                                            <option value="A-" {{ old('blood_group', $student->blood_group) === 'A-' ? 'selected' : '' }}>A-</option>
                                            <option value="B+" {{ old('blood_group', $student->blood_group) === 'B+' ? 'selected' : '' }}>B+</option>
                                            <option value="B-" {{ old('blood_group', $student->blood_group) === 'B-' ? 'selected' : '' }}>B-</option>
                                            <option value="AB+" {{ old('blood_group', $student->blood_group) === 'AB+' ? 'selected' : '' }}>AB+</option>
                                            <option value="AB-" {{ old('blood_group', $student->blood_group) === 'AB-' ? 'selected' : '' }}>AB-</option>
                                            <option value="O+" {{ old('blood_group', $student->blood_group) === 'O+' ? 'selected' : '' }}>O+</option>
                                            <option value="O-" {{ old('blood_group', $student->blood_group) === 'O-' ? 'selected' : '' }}>O-</option>
                                        </select>
                                        @error('blood_group')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-address-book mr-2"></i>
                                Contact Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $student->email ?? $student->user->email) }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="tel" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone', $student->phone) }}"
                                               placeholder="+1 (555) 123-4567">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3"
                                          placeholder="Enter your full address">{{ old('address', $student->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-phone mr-2"></i>
                                Emergency Contact
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emergency_contact">Emergency Contact Name</label>
                                        <input type="text" 
                                               class="form-control @error('emergency_contact') is-invalid @enderror" 
                                               id="emergency_contact" 
                                               name="emergency_contact" 
                                               value="{{ old('emergency_contact', $student->emergency_contact) }}"
                                               placeholder="Parent/Guardian Name">
                                        @error('emergency_contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emergency_phone">Emergency Contact Phone</label>
                                        <input type="tel" 
                                               class="form-control @error('emergency_phone') is-invalid @enderror" 
                                               id="emergency_phone" 
                                               name="emergency_phone" 
                                               value="{{ old('emergency_phone', $student->emergency_phone) }}"
                                               placeholder="+1 (555) 123-4567">
                                        @error('emergency_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save mr-2"></i>
                                        Update Profile
                                    </button>
                                    <a href="{{ route('student.profile.show') }}" class="btn btn-secondary btn-lg ml-2">
                                        <i class="fas fa-times mr-2"></i>
                                        Cancel
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Fields marked with <span class="text-danger">*</span> are required
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Change Password Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="passwordForm" action="{{ route('student.profile.update-password') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preferences Modal -->
<div class="modal fade" id="preferencesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notification Preferences</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="preferencesForm" action="{{ route('student.profile.update-preferences') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Notification Settings</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="email_notifications" name="email_notifications" value="1" checked>
                            <label class="custom-control-label" for="email_notifications">Email Notifications</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="assignment_reminders" name="assignment_reminders" value="1" checked>
                            <label class="custom-control-label" for="assignment_reminders">Assignment Reminders</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="grade_notifications" name="grade_notifications" value="1" checked>
                            <label class="custom-control-label" for="grade_notifications">Grade Notifications</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="attendance_alerts" name="attendance_alerts" value="1" checked>
                            <label class="custom-control-label" for="attendance_alerts">Attendance Alerts</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="dashboard_theme">Dashboard Theme</label>
                        <select class="form-control" id="dashboard_theme" name="dashboard_theme">
                            <option value="light">Light</option>
                            <option value="dark">Dark</option>
                            <option value="auto">Auto</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#profilePhotoPreview').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function showPasswordModal() {
    $('#passwordModal').modal('show');
}

function showPreferencesModal() {
    $('#preferencesModal').modal('show');
}

$(document).ready(function() {
    // Handle password form submission
    $('#passwordForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#passwordModal').modal('hide');
                    toastr.success(response.message);
                    $('#passwordForm')[0].reset();
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    Object.keys(errors).forEach(function(key) {
                        toastr.error(errors[key][0]);
                    });
                } else {
                    toastr.error('An error occurred while updating password');
                }
            }
        });
    });

    // Handle preferences form submission
    $('#preferencesForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#preferencesModal').modal('hide');
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred while updating preferences');
            }
        });
    });

    // Form validation
    $('#profileForm').on('submit', function(e) {
        const firstName = $('#first_name').val().trim();
        const lastName = $('#last_name').val().trim();
        const email = $('#email').val().trim();
        
        if (!firstName || !lastName || !email) {
            e.preventDefault();
            toastr.error('Please fill in all required fields');
            return false;
        }
    });
});
</script>
@endpush