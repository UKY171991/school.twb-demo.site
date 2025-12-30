@extends('layouts.auth', ['title' => 'Verify Email'])

@section('content')
<div class="auth-card shadow">
    <div class="auth-header">
        <a href="{{ url('/') }}" class="logo">School<span>MS</span></a>
        <h5 class="text-muted fw-normal">{{ __('Verify Your Email Address') }}</h5>
    </div>
    <div class="auth-body text-center">
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif

        <p class="text-muted mb-4">
            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email, you can request another one below.') }}
        </p>

        <form class="d-grid" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-auth">{{ __('Request Another Link') }}</button>
        </form>
    </div>
</div>

<div class="auth-footer">
    <p><a href="{{ route('login') }}">Back to Login</a></p>
</div>
@endsection
