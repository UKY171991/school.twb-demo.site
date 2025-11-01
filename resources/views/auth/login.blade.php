<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School Management') }} · Sign in</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet">

    <!-- AdminLTE3 & dependencies -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        body {
            background: radial-gradient(circle at top, #eff3ff 0%, #f4f6fb 45%, #f7f9fc 100%);
            min-height: 100vh;
            font-family: 'Nunito', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1120px;
        }

        .login-card {
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 28px 60px rgba(20, 34, 70, 0.12);
            background: #fff;
        }

        .brand-panel {
            background: linear-gradient(135deg, #2152ff 0%, #21d4fd 100%);
            position: relative;
            padding: 3.5rem 3.5rem 4.5rem;
        }

        .brand-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            background: url('https://adminlte.io/themes/v3/dist/img/boxed-bg.jpg') center/cover;
            opacity: .25;
            mix-blend-mode: screen;
        }

        .brand-panel .brand-content {
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(12px);
            margin-bottom: 2rem;
        }

        .brand-title {
            font-size: 2.125rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1rem;
            margin-bottom: 2.25rem;
            max-width: 280px;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
        }

        .feature-list li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.15rem;
            font-size: 1rem;
        }

        .feature-list li i {
            margin-right: 0.75rem;
            margin-top: 0.2rem;
            color: #ffffff;
            font-size: 1rem;
        }

        .form-panel {
            padding: 3.25rem 3.75rem;
        }

        .form-heading {
            margin-bottom: 2rem;
        }

        .form-heading h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #1f2d3d;
            font-size: 2rem;
        }

        .form-heading p {
            color: #6a7a8c;
            margin-bottom: 0;
        }

        .input-group-text {
            border-right: none;
            background: transparent;
            color: #2152ff;
        }

        .form-control {
            border-left: none;
            padding: 0.85rem 1rem;
            border-radius: 0 0.75rem 0.75rem 0;
        }

        .input-group {
            border: 1px solid #d8e2ef;
            border-radius: 0.9rem;
            transition: border-color 0.2s ease;
            background: #fff;
            margin-bottom: 1.5rem;
        }

        .input-group:focus-within {
            border-color: #2152ff;
            box-shadow: 0 0 0 3px rgba(33, 82, 255, 0.12);
        }

        .btn-login {
            background: linear-gradient(135deg, #2152ff 0%, #21d4fd 100%);
            border: none;
            border-radius: 0.9rem;
            padding: 1rem 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-login:hover,
        .btn-login:focus {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(33, 82, 255, 0.25);
        }

        .action-row {
            margin-bottom: 2rem;
        }

        .link-muted {
            color: #6a7a8c;
            text-decoration: none;
        }

        .link-muted:hover {
            color: #2152ff;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 2.25rem 0 1.75rem;
            color: #a1acbd;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e3e9f2;
        }

        .divider span {
            margin: 0 0.75rem;
            font-size: 0.75rem;
            letter-spacing: 0.12em;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            margin: 0 0.45rem;
            color: #fff;
            font-size: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .social-links a:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(18, 38, 63, 0.15);
        }

        .social-facebook { background: #3b5998; }
        .social-google { background: #db4437; }
        .social-twitter { background: #1da1f2; }

        @media (max-width: 1199.98px) {
            body {
                padding: 2.5rem 1rem;
            }

            .login-card {
                border-radius: 1.5rem;
            }

            .brand-panel {
                padding: 3rem 2.75rem 3.5rem;
            }

            .form-panel {
                padding: 3rem 3rem;
            }
        }

        @media (max-width: 991.98px) {
            body {
                padding: 2rem 0.75rem;
            }

            .brand-panel {
                padding: 2.75rem 2.5rem 3.25rem;
            }

            .form-panel {
                padding: 2.75rem 2.5rem;
            }
        }

        @media (max-width: 575.98px) {
            body {
                padding: 1.75rem 0.75rem;
            }

            .form-panel {
                padding: 2.25rem 1.75rem;
            }

            .brand-panel {
                padding: 2.5rem 1.75rem 3rem;
            }
        }
    </style>
</head>
<body class="hold-transition">
<div class="login-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="card login-card">
                    <div class="row no-gutters">
                        <div class="col-lg-5 d-none d-lg-flex brand-panel">
                            <div class="brand-content animate__animated animate__fadeInLeft">
                                <div class="brand-logo">
                                    <i class="fas fa-school fa-lg text-white"></i>
                                </div>
                                <div class="brand-title">{{ config('app.name', 'School Management') }}</div>
                                <p class="brand-subtitle">Unified platform to manage schools, staff, parents, and students effortlessly.</p>
                                <ul class="feature-list">
                                    <li><i class="fas fa-check"></i> Centralized multi-school management</li>
                                    <li><i class="fas fa-check"></i> Real-time attendance & academic insights</li>
                                    <li><i class="fas fa-check"></i> Secure communication for all stakeholders</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-7 col-12">
                            <div class="form-panel">
                                <div class="form-heading">
                                    <h2 class="mb-1">Welcome back</h2>
                                    <p>Sign in to continue to your dashboard.</p>
                                </div>

                                <form id="loginForm" action="{{ route('login') }}" method="POST" autocomplete="off">
                                    @csrf

                                    <div class="form-group mb-3">
                                        <label class="text-uppercase small text-muted font-weight-semibold" for="email">Email address</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-envelope"></i>
                                                </span>
                                            </div>
                                            <input type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   id="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   required
                                                   autofocus
                                                   placeholder="name@example.com">
                                        </div>
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="text-uppercase small text-muted font-weight-semibold" for="password">Password</label>
                                            @if (Route::has('password.request'))
                                                <a class="link-muted small" href="{{ route('password.request') }}">Forgot password?</a>
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                            </div>
                                            <input type="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   id="password"
                                                   name="password"
                                                   required
                                                   placeholder="Enter your password">
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label for="remember">Remember me</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-login btn-block" id="loginSubmit">
                                        <span class="btn-label"><i class="fas fa-sign-in-alt mr-2"></i>Sign in</span>
                                    </button>
                                </form>

                                <div class="divider">
                                    <span>OR CONTINUE WITH</span>
                                </div>

                                <div class="text-center social-links">
                                    <a href="#" class="social-facebook" aria-label="Continue with Facebook"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#" class="social-google" aria-label="Continue with Google"><i class="fab fa-google"></i></a>
                                    <a href="#" class="social-twitter" aria-label="Continue with Twitter"><i class="fab fa-twitter"></i></a>
                                </div>

                                <p class="mt-4 text-center text-muted">
                                    Don’t have an account?
                                    <a href="{{ route('register') }}" class="font-weight-semibold">Create one now</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000,
        extendedTimeOut: 1000,
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    @if (session('status'))
        toastr.info('{{ session('status') }}');
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif

    $('#loginForm').on('submit', function (event) {
        event.preventDefault();

        const $form = $(this);
        const $button = $('#loginSubmit');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            headers: {
                'Accept': 'application/json'
            },
            beforeSend: function () {
                $button.prop('disabled', true);
                $button.find('.btn-label').html('<i class="fas fa-spinner fa-spin mr-2"></i>Signing in');
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message || 'Login successful');
                    setTimeout(function () {
                        window.location.href = response.redirect || '{{ route('dashboard') }}';
                    }, 900);
                } else {
                    toastr.error(response.message || 'Login failed. Please try again.');
                    resetButton();
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    Object.values(xhr.responseJSON.errors).forEach(function (messages) {
                        toastr.error(messages[0]);
                    });
                } else if (xhr.status === 401) {
                    toastr.error(xhr.responseJSON?.message || 'Invalid credentials provided.');
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
                resetButton();
            }
        });

        function resetButton() {
            $button.prop('disabled', false);
            $button.find('.btn-label').html('<i class="fas fa-sign-in-alt mr-2"></i>Sign in');
        }
    });
</script>
</body>
</html>
