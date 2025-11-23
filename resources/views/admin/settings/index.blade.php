@extends('layouts.admin')

@section('title', 'Settings - Admin Dashboard')

@section('page-title', 'System Settings')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Settings Menu</h3>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link active">
                            <i class="fas fa-cog mr-2"></i> General Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-shield mr-2"></i> Security
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-bell mr-2"></i> Notifications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-envelope mr-2"></i> Email Configuration
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-database mr-2"></i> Backup & Restore
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cog mr-2"></i>
                    General Settings
                </h3>
            </div>
            <div class="card-body">
                <form id="settingsForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="school_name">School Name</label>
                                <input type="text" class="form-control" id="school_name" name="school_name" value="{{ $currentSchool->name ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="school_code">School Code</label>
                                <input type="text" class="form-control" id="school_code" name="school_code" value="{{ $currentSchool->code ?? '' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $currentSchool->email ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $currentSchool->phone ?? '' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3">{{ $currentSchool->address ?? '' }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ $currentSchool->city ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="{{ $currentSchool->country ?? '' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="academic_year_start">Academic Year Start Date</label>
                        <input type="date" class="form-control" id="academic_year_start" name="academic_year_start" value="{{ $currentSchool->academic_year_start ?? '' }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="academic_year_end">Academic Year End Date</label>
                        <input type="date" class="form-control" id="academic_year_end" name="academic_year_end" value="{{ $currentSchool->academic_year_end ?? '' }}">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="saveSettingsBtn">
                            <i class="fas fa-save mr-1"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#settingsForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const saveBtn = $('#saveSettingsBtn');
        const originalText = saveBtn.html();
        
        $.ajax({
            url: '{{ route("admin.settings.update") }}',
            method: 'POST',
            data: formData,
            beforeSend: function() {
                saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Settings saved successfully');
                } else {
                    toastr.error(response.message || 'Failed to save settings');
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred while saving settings');
            },
            complete: function() {
                saveBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
@endpush