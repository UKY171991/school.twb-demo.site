@extends('layouts.superadmin')

@section('title', 'Create School')

@section('content-header')
    @include('layouts.partials.content-header', [
        'title' => 'Create School',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'url' => route('superadmin.dashboard')],
            ['text' => 'Schools', 'url' => route('superadmin.schools.index')],
            ['text' => 'Create', 'active' => true]
        ]
    ])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">School Information</h3>
                <div class="card-tools">
                    <a href="{{ route('superadmin.schools.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Schools
                    </a>
                </div>
            </div>
            <form id="schoolForm" action="{{ route('superadmin.schools.store') }}" method="POST">
                @csrf
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
                                'value' => old('name')
                            ])

                            @include('components.form-group', [
                                'label' => 'School Code',
                                'name' => 'code',
                                'type' => 'text',
                                'required' => true,
                                'placeholder' => 'Enter unique school code',
                                'value' => old('code'),
                                'help' => 'Unique identifier for the school'
                            ])

                            @include('components.form-group', [
                                'label' => 'Email',
                                'name' => 'email',
                                'type' => 'email',
                                'placeholder' => 'Enter school email',
                                'value' => old('email')
                            ])

                            @include('components.form-group', [
                                'label' => 'Phone',
                                'name' => 'phone',
                                'type' => 'text',
                                'placeholder' => 'Enter school phone number',
                                'value' => old('phone')
                            ])

                            @include('components.form-group', [
                                'label' => 'Website',
                                'name' => 'website',
                                'type' => 'url',
                                'placeholder' => 'Enter school website URL',
                                'value' => old('website')
                            ])

                            @include('components.form-group', [
                                'label' => 'Address',
                                'name' => 'address',
                                'type' => 'textarea',
                                'placeholder' => 'Enter school address',
                                'value' => old('address'),
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
                                'value' => old('principal_name')
                            ])

                            @include('components.form-group', [
                                'label' => 'Principal Email',
                                'name' => 'principal_email',
                                'type' => 'email',
                                'placeholder' => 'Enter principal email',
                                'value' => old('principal_email')
                            ])

                            @include('components.form-group', [
                                'label' => 'Principal Phone',
                                'name' => 'principal_phone',
                                'type' => 'text',
                                'placeholder' => 'Enter principal phone number',
                                'value' => old('principal_phone')
                            ])

                            @include('components.form-group', [
                                'label' => 'Established Date',
                                'name' => 'established_date',
                                'type' => 'date',
                                'value' => old('established_date')
                            ])

                            <div class="form-group">
                                <label for="timezone">Timezone</label>
                                <select name="timezone" id="timezone" class="form-control">
                                    <option value="UTC" {{ old('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="America/New_York" {{ old('timezone') == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                    <option value="America/Chicago" {{ old('timezone') == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                    <option value="America/Denver" {{ old('timezone') == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                    <option value="America/Los_Angeles" {{ old('timezone') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                    <option value="Europe/London" {{ old('timezone') == 'Europe/London' ? 'selected' : '' }}>London</option>
                                    <option value="Europe/Paris" {{ old('timezone') == 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                                    <option value="Asia/Tokyo" {{ old('timezone') == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                                    <option value="Asia/Shanghai" {{ old('timezone') == 'Asia/Shanghai' ? 'selected' : '' }}>Shanghai</option>
                                    <option value="Australia/Sydney" {{ old('timezone') == 'Australia/Sydney' ? 'selected' : '' }}>Sydney</option>
                                </select>
                            </div>

                            @include('components.form-group', [
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'textarea',
                                'placeholder' => 'Enter school description',
                                'value' => old('description'),
                                'rows' => 4
                            ])
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create School
                    </button>
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
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
        
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

    // Auto-generate school code from name
    $('#name').on('input', function() {
        const name = $(this).val();
        const code = name.toUpperCase()
                        .replace(/[^A-Z0-9\s]/g, '')
                        .replace(/\s+/g, '_')
                        .substring(0, 10);
        $('#code').val(code);
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