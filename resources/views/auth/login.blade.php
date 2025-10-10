<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'School Management') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- AdminLTE3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .login-box {
            margin-top: 10vh;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px 15px 0 0 !important;
            text-align: center;
            padding: 2rem;
        }
        .card-header h3 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        .login-logo {
            font-size: 2rem;
            font-weight: bold;
            color: white;
            margin-bottom: 1rem;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .input-group-text {
            background: transparent;
            border-right: none;
            color: #667eea;
        }
        .form-control {
            border-left: none;
            border-radius: 0 25px 25px 0;
            padding: 12px 15px;
        }
        .input-group {
            margin-bottom: 1.5rem;
        }
        .input-group:first-child .form-control {
            border-radius: 25px;
        }
        .input-group:first-child .input-group-text {
            border-radius: 25px 0 0 25px;
        }
        .social-auth-links {
            text-align: center;
            margin-top: 1rem;
        }
        .social-auth-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 50%;
            margin: 0 5px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .social-auth-links a:hover {
            transform: translateY(-3px);
        }
        .bg-facebook { background: #3b5998; }
        .bg-google { background: #dd4b39; }
        .bg-twitter { background: #00aced; }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card">
        <div class="card-header">
            <div class="login-logo">
                <i class="fas fa-school"></i> School Management
            </div>
            <h3>Welcome Back!</h3>
            <p class="text-white-50">Please sign in to your account</p>
        </div>
        <div class="card-body login-card-body">
            <form action="{{ route('login') }}" method="post" id="loginForm">
                @csrf
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           placeholder="Email Address" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="email" 
                           autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Password" 
                           name="password" 
                           required 
                           autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-login">
                            <i class="fas fa-sign-in-alt"></i> Sign In
                        </button>
                    </div>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            <i class="fas fa-key"></i> Forgot Your Password?
                        </a>
                    </div>
                @endif
            </form>

            <div class="social-auth-links text-center mt-4">
                <p class="mb-3">- OR -</p>
                <a href="#" class="bg-facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="bg-google">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#" class="bg-twitter">
                    <i class="fab fa-twitter"></i>
                </a>
            </div>

            <div class="text-center mt-4">
                <p class="mb-0">Don't have an account? 
                    <a href="{{ route('register') }}" class="text-decoration-none">
                        <strong>Sign up here</strong>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Toastr configuration
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // CSRF token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Show validation errors if any
    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif

    // Show success message if redirected from registration
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    // AJAX form submission
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = form.serialize();
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('.btn-login').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Signing In...');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Login successful! Redirecting...');
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Login failed. Please try again.');
                    $('.btn-login').prop('disabled', false).html('<i class="fas fa-sign-in-alt"></i> Sign In');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        toastr.error(errors[field][0]);
                    }
                } else if (xhr.status === 401) {
                    toastr.error('Invalid credentials. Please check your email and password.');
                } else {
                    toastr.error('An error occurred. Please try again.');
                }
                $('.btn-login').prop('disabled', false).html('<i class="fas fa-sign-in-alt"></i> Sign In');
            }
        });
    });

    // Add animation to input fields
    $('.form-control').on('focus', function() {
        $(this).parent().addClass('focused');
    });

    $('.form-control').on('blur', function() {
        if (!$(this).val()) {
            $(this).parent().removeClass('focused');
        }
    });
</script>
</body>
</html>
