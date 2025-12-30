@extends('layouts.auth', ['title' => '404 - Page Not Found'])

@section('content')
<div class="auth-card shadow text-center">
    <div class="auth-header">
        <div class="display-1 fw-bold text-primary mb-2" style="color: #764ba2 !important;">404</div>
        <h2 class="fw-bold mb-3">Oops! Page Not Found</h2>
        <p class="text-muted mb-4">
            The page you are looking for might have been moved, removed, or had its name changed. 
            Or maybe it never existed in the first place!
        </p>
    </div>
    <div class="auth-body">
        <div class="d-grid gap-3">
            <a href="{{ url('/') }}" class="btn btn-auth">
                <i class="fas fa-home me-2"></i> Return to Home
            </a>
            <button onclick="window.history.back()" class="btn btn-outline-secondary border-2 rounded-pill py-2">
                <i class="fas fa-arrow-left me-2"></i> Go Back
            </button>
        </div>
    </div>
</div>

<div class="auth-footer">
    <p>&copy; {{ date('Y') }} School<span>MS</span>. All rights reserved.</p>
</div>
@endsection

@section('css')
<style>
    .display-1 {
        font-size: 8rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
    }
    .btn-outline-secondary {
        border-radius: 12px !important;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-outline-secondary:hover {
        background-color: #f8fafc;
        color: #4a5568;
        transform: translateY(-2px);
    }
</style>
@endsection
