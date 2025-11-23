@extends('layouts.admin')

@section('title', 'General Settings')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>General Settings</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">General Settings</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="settings-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab">
                                <i class="fas fa-cog"></i> General
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="system-tab" data-toggle="pill" href="#system" role="tab">
                                <i class="fas fa-server"></i> System
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="email-tab" data-toggle="pill" href="#email" role="tab">
                                <i class="fas fa-envelope"></i> Email
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sms-tab" data-toggle="pill" href="#sms" role="tab">
                                <i class="fas fa-sms"></i> SMS
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="settings-tabContent">
                        <!-- General Settings Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <form id="general-settings-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="school_name">School Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="school_name" name="settings[school_name]" 
                                                   value="{{ $settings['general']->firstWhere('key', 'school_name')->value ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="school_email">School Email</label>
                                            <input type="email" class="form-control" id="school_email" name="settings[school_email]" 
                                                   value="{{ $settings['general']->firstWhere('key', 'school_email')->value ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="school_phone">School Phone</label>
                                            <input type="text" class="form-control" id="school_phone" name="settings[school_phone]" 
                                                   value="{{ $settings['general']->firstWhere('key', 'school_phone')->value ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="school_address">School Address</label>
                                            <input type="text" class="form-control" id="school_address" name="settings[school_address]" 
                                                   value="{{ $settings['general']->firstWhere('key', 'school_address')->value ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="timezone">Timezone</label>
                                            <select class="form-control" id="timezone" name="settings[timezone]">
                                                <option value="Asia/Kolkata">Asia/Kolkata (IST)</option>
                                                <option value="America/New_York">America/New_York (EST)</option>
                                                <option value="Europe/London">Europe/London (GMT)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_format">Date Format</label>
                                            <select class="form-control" id="date_format" name="settings[date_format]">
                                                <option value="Y-m-d">YYYY-MM-DD</option>
                                                <option value="d/m/Y">DD/MM/YYYY</option>
                                                <option value="m/d/Y">MM/DD/YYYY</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Save Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- System Settings Tab -->
                        <div class="tab-pane fade" id="system" role="tabpanel">
                            <form id="system-settings-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="maintenance_mode">Maintenance Mode</label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="maintenance_mode" 
                                                       name="settings[maintenance_mode]" value="1">
                                                <label class="custom-control-label" for="maintenance_mode">Enable Maintenance Mode</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="auto_backup">Auto Backup</label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="auto_backup" 
                                                       name="settings[auto_backup]" value="1">
                                                <label class="custom-control-label" for="auto_backup">Enable Auto Backup</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Save Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Email Settings Tab -->
                        <div class="tab-pane fade" id="email" role="tabpanel">
                            <p class="text-muted">Email settings are managed in the Email Settings module.</p>
                            <a href="{{ route('admin.email-settings.index') }}" class="btn btn-primary">
                                <i class="fas fa-cog"></i> Configure Email Settings
                            </a>
                        </div>

                        <!-- SMS Settings Tab -->
                        <div class="tab-pane fade" id="sms" role="tabpanel">
                            <p class="text-muted">SMS settings are managed in the SMS Settings module.</p>
                            <a href="{{ route('admin.sms-settings.index') }}" class="btn btn-primary">
                                <i class="fas fa-cog"></i> Configure SMS Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle General Settings Form Submit
    $('#general-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("admin.settings.general.update") }}',
            type: 'POST',
            data: $(this).serialize(),
            beforeSend: function() {
                $('#general-settings-form button[type="submit"]').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                let message = 'An error occurred while saving settings.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            },
            complete: function() {
                $('#general-settings-form button[type="submit"]').prop('disabled', false)
                    .html('<i class="fas fa-save"></i> Save Settings');
            }
        });
    });

    // Handle System Settings Form Submit
    $('#system-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("admin.settings.general.update") }}',
            type: 'POST',
            data: $(this).serialize(),
            beforeSend: function() {
                $('#system-settings-form button[type="submit"]').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                let message = 'An error occurred while saving settings.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            },
            complete: function() {
                $('#system-settings-form button[type="submit"]').prop('disabled', false)
                    .html('<i class="fas fa-save"></i> Save Settings');
            }
        });
    });
});
</script>
@endpush
