@extends('layouts.parent')

@section('title', 'Family Profile')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Family Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('parent.family.index') }}">Family Management</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Family Information</h3>
                    </div>
                    <form id="family-profile-form">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="family_name">Family Name</label>
                                        <input type="text" name="family_name" id="family_name" class="form-control" 
                                               value="{{ $familyProfile->family_name ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="primary_contact_name">Primary Contact Name</label>
                                        <input type="text" name="primary_contact_name" id="primary_contact_name" class="form-control" 
                                               value="{{ $familyProfile->primary_contact_name ?? auth()->user()->name }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="primary_contact_phone">Primary Contact Phone</label>
                                        <input type="tel" name="primary_contact_phone" id="primary_contact_phone" class="form-control" 
                                               value="{{ $familyProfile->primary_contact_phone ?? auth()->user()->phone }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="primary_contact_email">Primary Contact Email</label>
                                        <input type="email" name="primary_contact_email" id="primary_contact_email" class="form-control" 
                                               value="{{ $familyProfile->primary_contact_email ?? auth()->user()->email }}" required>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5>Secondary Contact (Optional)</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="secondary_contact_name">Secondary Contact Name</label>
                                        <input type="text" name="secondary_contact_name" id="secondary_contact_name" class="form-control" 
                                               value="{{ $familyProfile->secondary_contact_name ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="secondary_contact_phone">Secondary Contact Phone</label>
                                        <input type="tel" name="secondary_contact_phone" id="secondary_contact_phone" class="form-control" 
                                               value="{{ $familyProfile->secondary_contact_phone ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="secondary_contact_email">Secondary Contact Email</label>
                                        <input type="email" name="secondary_contact_email" id="secondary_contact_email" class="form-control" 
                                               value="{{ $familyProfile->secondary_contact_email ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5>Addresses</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="home_address">Home Address</label>
                                        <textarea name="home_address" id="home_address" class="form-control" rows="3">{{ $familyProfile->home_address ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="work_address">Work Address</label>
                                        <textarea name="work_address" id="work_address" class="form-control" rows="3">{{ $familyProfile->work_address ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5>Additional Information</h5>

                            <div class="form-group">
                                <label for="medical_information">Medical Information</label>
                                <textarea name="medical_information" id="medical_information" class="form-control" rows="3" 
                                          placeholder="Any important medical information about family members...">{{ $familyProfile->medical_information ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="special_instructions">Special Instructions</label>
                                <textarea name="special_instructions" id="special_instructions" class="form-control" rows="3" 
                                          placeholder="Any special instructions for school staff...">{{ $familyProfile->special_instructions ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="save-btn">
                                <i class="fas fa-save mr-1"></i>
                                Save Profile
                            </button>
                            <a href="{{ route('parent.family.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back to Family Management
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Emergency Contacts Summary -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Emergency Contacts</h3>
                        <div class="card-tools">
                            <a href="{{ route('parent.family.emergency-contacts') }}" class="btn btn-sm btn-primary">
                                Manage
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($emergencyContacts->count() > 0)
                            @foreach($emergencyContacts->take(3) as $contact)
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $contact->name }}</h6>
                                        <small class="text-muted">
                                            {{ $contact->relationship }} - {{ $contact->phone_primary }}
                                        </small>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr class="my-2">
                                @endif
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-phone fa-2x text-muted mb-2"></i>
                                <p class="text-muted small">No emergency contacts added</p>
                                <a href="{{ route('parent.family.emergency-contacts') }}" class="btn btn-sm btn-primary">
                                    Add Contact
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Tips -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Profile Tips</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success mr-2"></i>
                                Keep contact information up to date
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success mr-2"></i>
                                Add emergency contacts for safety
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success mr-2"></i>
                                Include medical information if relevant
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success mr-2"></i>
                                Set communication preferences
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#family-profile-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const saveBtn = $('#save-btn');
        const originalText = saveBtn.html();
        
        saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Saving...');
        
        $.ajax({
            url: '{{ route("parent.family.update-profile") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Failed to save profile');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.values(errors).forEach(function(errorArray) {
                        errorArray.forEach(function(error) {
                            toastr.error(error);
                        });
                    });
                } else {
                    toastr.error('Failed to save profile. Please try again.');
                }
            },
            complete: function() {
                saveBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
@endpush