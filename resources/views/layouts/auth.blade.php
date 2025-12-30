<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>School Management System - {{ $title ?? 'Auth' }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
    
    @yield('css')
    <style>
        :root {
            --auth-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
        }

        body.auth-page {
            font-family: 'Outfit', sans-serif;
            background: var(--auth-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .auth-container {
            width: 100%;
            max-width: 450px;
            animation: fadeIn 0.6s ease-out;
        }

        .auth-card {
            background: var(--glass-bg);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .auth-card:hover {
            transform: translateY(-5px);
        }

        .auth-header {
            padding: 40px 40px 10px;
            text-align: center;
        }

        .auth-header .logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: #764ba2;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
        }

        .auth-header .logo span {
            color: #667eea;
        }

        .auth-body {
            padding: 30px 40px 40px;
        }

        .form-label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            border-color: #667eea;
        }

        .input-group-text {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px 0 0 12px;
            color: #64748b;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
        }

        .btn-auth {
            background: var(--auth-bg);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-auth:hover {
            opacity: 0.9;
            transform: scale(1.02);
            color: white;
            box-shadow: 0 4px 12px rgba(118, 75, 162, 0.3);
        }

        .auth-footer {
            text-align: center;
            margin-top: 25px;
            color: rgba(255, 255, 255, 0.9);
        }

        .auth-footer a {
            color: white;
            font-weight: 600;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-container">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js')
</body>
</html>
