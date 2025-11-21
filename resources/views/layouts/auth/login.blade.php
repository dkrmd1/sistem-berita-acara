<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Berita Acara') - bjb Sekuritas</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-blue: #225F85;
            --secondary-blue: #46B3E6;
            --accent-yellow: #E7C118;
            --light-blue: #e8f4f8;
            --dark-blue: #164266;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar-brand img {
            height: 40px;
        }
        
        /* Custom Primary Colors */
        .bg-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%) !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--primary-blue) 100%);
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 95, 133, 0.3);
            color: white;
        }
        
        /* Sidebar Styles */
        .sidebar {
            min-height: calc(100vh - 56px);
            background: linear-gradient(180deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 56px;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
            white-space: nowrap;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(231, 193, 24, 0.15);
            color: #fff;
        }
        
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--accent-yellow) 0%, #d4ad15 100%);
            color: var(--dark-blue);
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(231, 193, 24, 0.3);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        .main-content {
            padding: 30px 15px;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--light-blue) 0%, #fff 100%);
            border-bottom: 2px solid var(--secondary-blue);
            padding: 20px;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--primary-blue);
        }
        
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 6px;
        }
        
        .badge-primary {
            background: var(--secondary-blue);
        }
        
        .badge-warning {
            background: var(--accent-yellow);
            color: var(--dark-blue);
        }
        
        .stat-card {
            border-left: 4px solid var(--secondary-blue);
            transition: transform 0.2s;
            background: linear-gradient(135deg, #fff 0%, var(--light-blue) 100%);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(34, 95, 133, 0.15);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fff9e6 0%, #fff 100%);
            color: #856404;
            border-left: 4px solid var(--accent-yellow);
        }
        
        .table {
            background-color: #fff;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            font-weight: 600;
            border-bottom: 2px solid var(--accent-yellow);
        }
        
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(70, 179, 230, 0.05);
        }
        
        /* Responsive Table */
        .table-responsive {
            border-radius: 8px;
            overflow-x: auto;
        }
        
        /* Mobile Sidebar */
        .offcanvas-sidebar {
            background: linear-gradient(180deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        }
        
        .offcanvas-sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .offcanvas-sidebar .nav-link:hover {
            background-color: rgba(231, 193, 24, 0.15);
            color: #fff;
        }
        
        .offcanvas-sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--accent-yellow) 0%, #d4ad15 100%);
            color: var(--dark-blue);
            font-weight: 600;
        }
        
        .offcanvas-sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .sidebar {
                display: none;
            }
            
            .main-content {
                padding: 20px 10px;
            }
            
            .card-header {
                padding: 15px;
                font-size: 1rem;
            }
            
            .btn {
                padding: 8px 16px;
                font-size: 0.9rem;
            }
            
            .table {
                font-size: 0.85rem;
            }
            
            .navbar-brand {
                font-size: 0.95rem;
            }
            
            .stat-card {
                margin-bottom: 15px;
            }
        }
        
        @media (min-width: 768px) {
            .btn-mobile-menu {
                display: none;
            }
            
            .main-content {
                padding: 30px;
            }
        }
        
        @media (max-width: 575.98px) {
            .alert {
                font-size: 0.9rem;
                padding: 12px;
            }
            
            .card {
                margin-bottom: 15px;
            }
        }
        
        /* Offcanvas Custom Styles */
        .offcanvas-header {
            background-color: rgba(231, 193, 24, 0.2);
            color: white;
        }
        
        .offcanvas-body {
            padding: 0;
        }
        
        .user-info-mobile {
            padding: 20px;
            text-align: center;
            color: white;
            border-bottom: 2px solid var(--accent-yellow);
            background-color: rgba(255,255,255,0.05);
        }
        
        /* Accent Elements */
        .text-primary {
            color: var(--primary-blue) !important;
        }
        
        .text-accent {
            color: var(--accent-yellow) !important;
        }
        
        .border-primary {
            border-color: var(--secondary-blue) !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <!-- Mobile Menu Button -->
            <button class="btn btn-primary btn-mobile-menu me-2 d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                <i class="bi bi-list fs-4"></i>
            </button>
            
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="bi bi-file-earmark-text text-accent"></i> 
                <span class="d-none d-sm-inline">Sistem Berita Acara</span>
                <span class="d-inline d-sm-none">SBA</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle" style="color: var(--accent-yellow);"></i> 
                            <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person text-primary"></i> Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Sidebar (Offcanvas) -->
    <div class="offcanvas offcanvas-start offcanvas-sidebar" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">
                <i class="bi bi-file-earmark-text text-accent"></i> Menu
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="user-info-mobile">
                <i class="bi bi-person-badge fs-1" style="color: var(--accent-yellow);"></i>
                <p class="mb-0 mt-2 fw-semibold">{{ Auth::user()->name }}</p>
                <p class="mb-0 small text-accent">{{ Auth::user()->getRoleLabel() }}</p>
            </div>
            
            <nav class="nav flex-column py-3">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" data-bs-dismiss="offcanvas">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                
                <a class="nav-link {{ request()->routeIs('nasabah.*') ? 'active' : '' }}" href="{{ route('nasabah.index') }}" data-bs-dismiss="offcanvas">
                    <i class="bi bi-people"></i> Data Nasabah
                </a>
                
                @if(Auth::user()->isCS())
                <a class="nav-link {{ request()->routeIs('nasabah.import.*') ? 'active' : '' }}" href="{{ route('nasabah.import.form') }}" data-bs-dismiss="offcanvas">
                    <i class="bi bi-file-earmark-arrow-up"></i> Import Nasabah
                </a>
                @endif
                
                <a class="nav-link {{ request()->routeIs('berita-acara.*') ? 'active' : '' }}" href="{{ route('berita-acara.index') }}" data-bs-dismiss="offcanvas">
                    <i class="bi bi-file-earmark-text"></i> Berita Acara
                </a>
                
                @if(Auth::user()->isCS())
                <a class="nav-link {{ request()->routeIs('berita-acara.create*') ? 'active' : '' }}" href="{{ route('berita-acara.create') }}" data-bs-dismiss="offcanvas">
                    <i class="bi bi-plus-circle"></i> Buat BA Baru
                </a>
                @endif
            </nav>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Desktop Sidebar -->
            <div class="col-md-2 px-0 sidebar d-none d-md-block">
                <div class="py-4">
                    <div class="text-center text-white mb-4">
                        <i class="bi bi-person-badge fs-1" style="color: var(--accent-yellow);"></i>
                        <p class="mb-0 mt-2 small text-accent fw-semibold">{{ Auth::user()->getRoleLabel() }}</p>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('nasabah.*') ? 'active' : '' }}" href="{{ route('nasabah.index') }}">
                            <i class="bi bi-people"></i> Data Nasabah
                        </a>
                        
                        @if(Auth::user()->isCS())
                        <a class="nav-link {{ request()->routeIs('nasabah.import.*') ? 'active' : '' }}" href="{{ route('nasabah.import.form') }}">
                            <i class="bi bi-file-earmark-arrow-up"></i> Import Nasabah
                        </a>
                        @endif
                        
                        <a class="nav-link {{ request()->routeIs('berita-acara.*') ? 'active' : '' }}" href="{{ route('berita-acara.index') }}">
                            <i class="bi bi-file-earmark-text"></i> Berita Acara
                        </a>
                        
                        @if(Auth::user()->isCS())
                        <a class="nav-link {{ request()->routeIs('berita-acara.create*') ? 'active' : '' }}" href="{{ route('berita-acara.create') }}">
                            <i class="bi bi-plus-circle"></i> Buat BA Baru
                        </a>
                        @endif
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <!-- Alert Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>