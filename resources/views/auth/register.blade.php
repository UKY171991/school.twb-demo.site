<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School Management') }} · Create account</title>

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

        .register-wrapper {
            width: 100%;
            max-width: 1120px;
        }

        .register-card {
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 28px 60px rgba(20, 34, 70, 0.12);
            background: #fff;
        }

        .brand-panel {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            position: relative;
            padding: 3.5rem 3.5rem 4.5rem;
            color: #fff;
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
            margin-bottom: 1rem;
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1rem;
            margin-bottom: 2.25rem;
            max-width: 290px;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.1rem;
            font-size: 1rem;
        }

        .feature-list li i {
            margin-right: 0.75rem;
            margin-top: 0.25rem;
            font-size: 1rem;
            color: #fff;
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

        .input-group {
            border: 1px solid #d8e2ef;
            border-radius: 0.9rem;
            transition: border-color 0.2s ease;
            background: #fff;
            margin-bottom: 1.5rem;
        }

        .input-group-text {
            border-right: none;
            background: transparent;
            color: #6a11cb;
        }

        .input-group:focus-within {
            border-color: #6a11cb;
            box-shadow: 0 0 0 3px rgba(106, 17, 203, 0.12);
        }

        .form-control {
            border-left: none;
            padding: 0.85rem 1rem;
            border-radius: 0 0.75rem 0.75rem 0;
        }

        .btn-register {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border: none;
            border-radius: 0.9rem;
            padding: 1rem 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-register:hover,
        .btn-register:focus {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(106, 17, 203, 0.25);
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

        .text-muted a {
            color: #6a11cb;
        }

        @media (max-width: 1199.98px) {
            body {
                padding: 2.5rem 1rem;
            }

            .register-card {
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
<div class="register-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="card register-card">
                    <div class="row no-gutters">
                        <div class="col-lg-5 d-none d-lg-flex brand-panel">
                            <div class="brand-content animate__animated animate__fadeInLeft">
                                <div class="brand-logo">
                                    <i class="fas fa-user-plus fa-lg text-white"></i>
                                </div>
                                <div class="brand-title">Join {{ config('app.name', 'School Management') }}</div>
                                <p class="brand-subtitle">Create an account to access centralized control across schools, staff, and student operations.</p>
                                <ul class="feature-list">
                                    <li><i class="fas fa-check"></i> Manage multiple campuses in one place</li>
                                    <li><i class="fas fa-check"></i> Coordinate teachers, parents & students seamlessly</li>
                                    <li><i class="fas fa-check"></i> Real-time analytics & performance dashboards</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-7 col-12">
                            <div class="form-panel">
                                <div class="form-heading">
                                    <h2 class="mb-1">Create your account</h2>
                                    <p>Fill in your details to get started.</p>
                                </div>

                                <form id="registerForm" action="{{ route('register') }}" method="POST" autocomplete="off">
                                    @csrf

                                    <div class="form-group">
                                        <label class="text-uppercase small text-muted font-weight-semibold" for="name">Full name</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Jane Doe">
                                        </div>
                                        @error('name')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="text-uppercase small text-muted font-weight-semibold" for="email">Email address</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-envelope"></i></span>
                                            </div>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                                        </div>
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="text-uppercase small text-muted font-weight-semibold" for="password">Password</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            </div>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Choose a secure password">
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                        <small class="form-text text-muted">Use at least 8 characters with uppercase, lowercase, number & symbol.</small>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-uppercase small text-muted font-weight-semibold" for="password_confirmation">Confirm password</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                            </div>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Re-enter your password">
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox mb-4">
                                        <input type="checkbox" class="custom-control-input" id="terms" required>
                                        <label class="custom-control-label" for="terms">I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>.</label>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-register btn-block" id="registerSubmit">
                                        <span class="btn-label"><i class="fas fa-user-plus mr-2"></i>Create account</span>
                                    </button>
                                </form>

                                <div class="divider">
                                    <span>ALREADY HAVE AN ACCOUNT?</span>
                                </div>

                                <p class="text-center text-muted mb-0">Sign in instead
                                    <a href="{{ route('login') }}" class="font-weight-semibold">Go to login</a>
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

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif

    $('#registerForm').on('submit', function (event) {
        event.preventDefault();

        const $form = $(this);
        const $button = $('#registerSubmit');

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
                $button.find('.btn-label').html('<i class="fas fa-spinner fa-spin mr-2"></i>Creating...');
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message || 'Registration successful');
                    setTimeout(function () {
                        window.location.href = response.redirect || '{{ route('dashboard') }}';
                    }, 900);
                } else {
                    toastr.error(response.message || 'Registration failed. Please try again.');
                    resetButton();
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    Object.values(xhr.responseJSON.errors).forEach(function (messages) {
                        toastr.error(messages[0]);
                    });
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Something went wrong. Please try again.');
                }
                resetButton();
            }
        });

        function resetButton() {
            $button.prop('disabled', false);
            $button.find('.btn-label').html('<i class="fas fa-user-plus mr-2"></i>Create account');
        }
    });
</script>
</body>
</html>
