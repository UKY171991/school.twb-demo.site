@extends('layouts.auth', ['title' => 'Confirm Password'])

@section('content')
<div class="auth-card shadow">
    <div class="auth-header">
        <a href="{{ url('/') }}" class="logo">School<span>MS</span></a>
        <h5 class="text-muted fw-normal">{{ __('Confirm Password') }}</h5>
    </div>
    <div class="auth-body">
        <p class="text-muted mb-4 text-center">
            {{ __('Please confirm your password before continuing.') }}
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-4">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                </div>
                @error('password')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-auth">
                    {{ __('Confirm Password') }}
                </button>
            </div>

            @if (Route::has('password.request'))
                <div class="text-center mt-3">
                    <a class="text-decoration-none small fw-semibold" style="color: #667eea;" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
