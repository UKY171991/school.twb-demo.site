<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheWebBrain - School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            min-height: 600px;
            display: flex;
            align-items: center;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-custom {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 50px;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .feature-card {
            border: none;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stats-section {
            padding: 80px 0;
            background: #f8f9fc;
        }

        .stat-card {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 1.1rem;
            color: var(--secondary-color);
            margin-top: 10px;
        }

        .footer {
            background: #2d3748;
            color: white;
            padding: 40px 0;
            margin-top: 80px;
        }

        .navbar-custom {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-graduation-cap"></i> TheWebBrain
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">School Management Made Simple</h1>
                    <p class="hero-subtitle">Streamline your school operations with our comprehensive management system. Manage students, teachers, grades, and more - all in one place.</p>
                    <div class="mt-4">
                        @auth
                            <a href="{{ route('home') }}" class="btn btn-light btn-custom">Go to Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-light btn-custom">Get Started</a>
                            <a href="#features" class="btn btn-outline-light btn-custom">Learn More</a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://via.placeholder.com/600x400/667eea/ffffff?text=School+Management" alt="School Management" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">Powerful Features</h2>
                <p class="lead text-muted">Everything you need to manage your school efficiently</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h4>Student Management</h4>
                        <p class="text-muted">Easily manage student records, enrollment, and academic progress all in one centralized system.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h4>Teacher Portal</h4>
                        <p class="text-muted">Comprehensive teacher management with assignment tracking, schedule management, and performance monitoring.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h4>Attendance Tracking</h4>
                        <p class="text-muted">Digital attendance system with real-time updates and automated reporting for better insights.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h4>Subject Management</h4>
                        <p class="text-muted">Organize subjects, assign teachers, and manage curriculum efficiently across all grades.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-poll"></i>
                        </div>
                        <h4>Exam & Marks</h4>
                        <p class="text-muted">Complete examination management with grade tracking, report cards, and performance analytics.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Reports & Analytics</h4>
                        <p class="text-muted">Generate comprehensive reports and gain insights with powerful analytics and visualization tools.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-label">Student Management</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">
                            <i class="fas fa-chalkboard"></i>
                        </div>
                        <div class="stat-label">Class Organization</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="stat-label">Attendance System</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="stat-label">Grade Tracking</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">About Our System</h2>
                    <p class="lead">Our School Management System is designed to simplify administrative tasks and improve communication between teachers, students, and parents.</p>
                    <p>With intuitive interfaces and powerful features, managing your school has never been easier. From enrollment to graduation, we've got you covered.</p>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Easy to use interface</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Secure data management</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Real-time updates</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Comprehensive reporting</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <img src="https://via.placeholder.com/600x400/764ba2/ffffff?text=Modern+Education" alt="About" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-graduation-cap"></i> TheWebBrain</h5>
                    <p class="mt-3">Empowering education through technology. Making school management simple, efficient, and effective.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled mt-3">
                        <li><a href="#" class="text-white-50">Home</a></li>
                        <li><a href="#features" class="text-white-50">Features</a></li>
                        <li><a href="#about" class="text-white-50">About</a></li>
                        <li><a href="{{ route('login') }}" class="text-white-50">Login</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled mt-3">
                        <li class="text-white-50"><i class="fas fa-envelope me-2"></i> info@schoolms.com</li>
                        <li class="text-white-50"><i class="fas fa-phone me-2"></i> +1 234 567 890</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-white-50 my-4">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} TheWebBrain. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
