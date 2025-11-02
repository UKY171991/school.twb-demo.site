@extends('layouts.superadmin')

@section('title', 'Edit School')

@section('content-header')
    @include('layouts.partials.content-header', [
        'title' => 'Edit School',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'url' => route('superadmin.dashboard')],
            ['text' => 'Schools', 'url' => route('superadmin.schools.index')],
            ['text' => $school->name, 'url' => route('superadmin.schools.show', $school)],
            ['text' => 'Edit', 'active' => true]
        ]
    ])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit School Information</h3>
                <div class="card-tools">
                    <a href="{{ route('superadmin.schools.show', $school) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> View School
                    </a>
                    <a href="{{ route('superadmin.schools.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Schools
                    </a>
                </div>
            </div>
            <form id="schoolForm" action="{{ route('superadmin.schools.update', $school) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Basic Information</h5>
                            
                            @include('components.form-group', [
                                'label' => 'School Name',
                                'name' => 'name',
                                'type' => 'text',
                                'required' => true,
                                'placeholder' => 'Enter school name',
                                'value' => old('name', $school->name)
                            ])

                            @include('components.form-group', [
                                'label' => 'School Code',
                                'name' => 'code',
                                'type' => 'text',
                                'required' => true,
                                'placeholder' => 'Enter unique school code',
                                'value' => old('code', $school->code),
                                'help' => 'Unique identifier for the school'
                            ])

                            @include('components.form-group', [
                                'label' => 'Email',
                                'name' => 'email',
                                'type' => 'email',
                                'placeholder' => 'Enter school email',
                                'value' => old('email', $school->email)
                            ])

                            @include('components.form-group', [
                                'label' => 'Phone',
                                'name' => 'phone',
                                'type' => 'text',
                                'placeholder' => 'Enter school phone number',
                                'value' => old('phone', $school->phone)
                            ])

                            @include('components.form-group', [
                                'label' => 'Website',
                                'name' => 'website',
                                'type' => 'url',
                                'placeholder' => 'Enter school website URL',
                                'value' => old('website', $school->website)
                            ])

                            @include('components.form-group', [
                                'label' => 'Address',
                                'name' => 'address',
                                'type' => 'textarea',
                                'placeholder' => 'Enter school address',
                                'value' => old('address', $school->address),
                                'rows' => 3
                            ])
                        </div>

                        <!-- Principal Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Principal Information</h5>
                            
                            @include('components.form-group', [
                                'label' => 'Principal Name',
                                'name' => 'principal_name',
                                'type' => 'text',
                                'placeholder' => 'Enter principal name',
                                'value' => old('principal_name', $school->principal_name)
                            ])

                            @include('components.form-group', [
                                'label' => 'Principal Email',
                                'name' => 'principal_email',
                                'type' => 'email',
                                'placeholder' => 'Enter principal email',
                                'value' => old('principal_email', $school->principal_email)
                            ])

                            @include('components.form-group', [
                                'label' => 'Principal Phone',
                                'name' => 'principal_phone',
                                'type' => 'text',
                                'placeholder' => 'Enter principal phone number',
                                'value' => old('principal_phone', $school->principal_phone)
                            ])

                            @include('components.form-group', [
                                'label' => 'Established Date',
                                'name' => 'established_date',
                                'type' => 'date',
                                'value' => old('established_date', $school->established_date ? $school->established_date->format('Y-m-d') : '')
                            ])

                            <div class="form-group">
                                <label for="timezone">Timezone</label>
                                <select name="timezone" id="timezone" class="form-control">
                                    <option value="UTC" {{ old('timezone', $school->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="America/New_York" {{ old('timezone', $school->timezone) == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                    <option value="America/Chicago" {{ old('timezone', $school->timezone) == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                    <option value="America/Denver" {{ old('timezone', $school->timezone) == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                    <option value="America/Los_Angeles" {{ old('timezone', $school->timezone) == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                    <option value="Europe/London" {{ old('timezone', $school->timezone) == 'Europe/London' ? 'selected' : '' }}>London</option>
                                    <option value="Europe/Paris" {{ old('timezone', $school->timezone) == 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                                    <option value="Asia/Tokyo" {{ old('timezone', $school->timezone) == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                                    <option value="Asia/Shanghai" {{ old('timezone', $school->timezone) == 'Asia/Shanghai' ? 'selected' : '' }}>Shanghai</option>
                                    <option value="Australia/Sydney" {{ old('timezone', $school->timezone) == 'Australia/Sydney' ? 'selected' : '' }}>Sydney</option>
                                </select>
                            </div>

                            @include('components.form-group', [
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'textarea',
                                'placeholder' => 'Enter school description',
                                'value' => old('description', $school->description),
                                'rows' => 4
                            ])
                        </div>
                    </div>

                    <!-- School Status -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> School Status</h6>
                                <p class="mb-0">
                                    Current Status: 
                                    @if($school->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </p>
                                <small class="text-muted">
                                    Use the toggle button in the schools list to change the status.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update School
                    </button>
                    <a href="{{ route('superadmin.schools.show', $school) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> View School
                    </a>
                    <a href="{{ route('superadmin.schools.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form submission with AJAX
    $('#schoolForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        // Disable submit button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    if (response.data && response.data.redirect) {
                        setTimeout(() => {
                            window.location.href = response.data.redirect;
                        }, 1500);
                    }
                } else {
                    toastr.error(response.message);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                
                if (response && response.errors) {
                    // Display validation errors
                    $.each(response.errors, function(field, messages) {
                        const input = $(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                    });
                    toastr.error('Please correct the errors and try again');
                } else {
                    toastr.error(response?.message || 'An error occurred');
                }
                
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Form validation
    $('#schoolForm').validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            code: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            email: {
                email: true,
                maxlength: 255
            },
            website: {
                url: true,
                maxlength: 255
            },
            principal_email: {
                email: true,
                maxlength: 255
            },
            established_date: {
                date: true
            }
        },
        messages: {
            name: {
                required: "School name is required",
                minlength: "School name must be at least 2 characters",
                maxlength: "School name cannot exceed 255 characters"
            },
            code: {
                required: "School code is required",
                minlength: "School code must be at least 2 characters",
                maxlength: "School code cannot exceed 50 characters"
            }
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endpush