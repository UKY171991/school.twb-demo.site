@extends('layouts.auth', ['title' => 'Reset Password'])

@section('content')
<div class="auth-card shadow">
    <div class="auth-header">
        <a href="{{ url('/') }}" class="logo">School<span>MS</span></a>
        <h5 class="text-muted fw-normal">Reset your password to regain access.</h5>
    </div>
    <div class="auth-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                </div>
                @error('email')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-auth">
                    {{ __('Send Reset Link') }}
                </button>
            </div>
        </form>
    </div>
</div>

<div class="auth-footer">
    <p>Remembered your password? <a href="{{ route('login') }}">Back to Login</a></p>
</div>
@endsection
