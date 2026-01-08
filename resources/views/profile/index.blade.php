@extends('adminlte::page')

@section('title', 'Profile Settings')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
@stop

@section('content_header')
    <h1><i class="fas fa-user-circle"></i> Profile Settings</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information Card -->
        <div class="col-lg-6 col-md-12" id="profile">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user"></i> Profile Information
                    </h3>
                </div>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Account Created</label>
                            <input type="text" class="form-control" 
                                   value="{{ $user->created_at->format('d M Y, H:i') }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Last Updated</label>
                            <input type="text" class="form-control" 
                                   value="{{ $user->updated_at->format('d M Y, H:i') }}" readonly>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="col-lg-6 col-md-12" id="password">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lock"></i> Change Password
                    </h3>
                </div>
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" name="password" id="password" 
                                   class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted d-block mt-1">
                                Password must be at least 8 characters long.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control @error('password_confirmation') is-invalid @enderror" required>
                            @error('password_confirmation')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Account Information Card -->
    <div class="row mt-3">
        <div class="col-lg-6 col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Account Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <strong>User ID:</strong>
                        </div>
                        <div class="col-sm-7">
                            <span class="badge badge-secondary">{{ $user->id }}</span>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <strong>Account Status:</strong>
                        </div>
                        <div class="col-sm-7">
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-5">
                            <strong>Email Verified:</strong>
                        </div>
                        <div class="col-sm-7">
                            @if($user->email_verified_at)
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> Verified
                                </span>
                            @else
                                <span class="badge badge-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Not Verified
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .card {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        margin-bottom: 1.5rem;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
        padding: 0.75rem 1.25rem;
    }
    
    .card-header .card-title {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .card-primary .card-header {
        background-color: #007bff;
        color: white;
    }
    
    .card-primary .card-header .card-title {
        color: white;
    }
    
    .card-warning .card-header {
        background-color: #ffc107;
        color: #333;
    }
    
    .card-warning .card-header .card-title {
        color: #333;
    }
    
    .card-info .card-header {
        background-color: #17a2b8;
        color: white;
    }
    
    .card-info .card-header .card-title {
        color: white;
    }
    
    .form-group label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid rgba(0,0,0,.125);
        padding: 0.75rem 1.25rem;
    }
    
    .btn {
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .col-lg-6 {
            margin-bottom: 1rem;
        }
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Handle hash scrolling for menu navigation
    if (window.location.hash) {
        var target = window.location.hash;
        if ($(target).length) {
            $('html, body').animate({
                scrollTop: $(target).offset().top - 100
            }, 800);
        }
    }
    
    // Add form validation feedback
    $('form').on('submit', function() {
        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
});
</script>
@stop