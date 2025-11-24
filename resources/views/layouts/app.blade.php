<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Berita Acara') - bjb Sekuritas</title>
    
    <!-- CDN Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* ===================================
           BJB SEKURITAS BRAND COLORS
        =================================== */
        :root {
            --calm-water-blue: #406B85;
            --calm-water-blue-dark: #2E5166;
            --atmospheric-blue: #41A1E0;
            --sincere-yellow: #FFED00;
            --sincere-yellow-dark: #E6D400;
        }
        
        /* ===================================
           GLOBAL STYLES
        =================================== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        html, body { height: 100%; width: 100%; }
        
        body {
            font-family: 'Myriad Pro', 'Segoe UI', Tahoma, sans-serif;
            background: #f5f7fa;
            overflow-x: hidden;
        }

        /* ===================================
           PAGE LOADER (LOGO CUSTOM)
        =================================== */
        #pageLoader {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.95);
            z-index: 9999;
            display: none;
            justify-content: center; align-items: center; flex-direction: column;
            backdrop-filter: blur(4px);
            transition: opacity 0.3s ease;
        }

        .loader-logo {
            width: 120px; height: auto;
            animation: pulse 1.5s infinite ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.15); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* ===================================
           CUSTOM SCROLLBAR
        =================================== */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--calm-water-blue); }
        
        /* ===================================
           NAVBAR
        =================================== */
        .navbar {
            background: linear-gradient(135deg, var(--calm-water-blue), var(--calm-water-blue-dark)) !important;
            box-shadow: 0 2px 10px rgba(64, 107, 133, 0.15);
            padding: 0 !important; 
            height: 70px;
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 1030;
            display: flex; flex-direction: column; 
        }

        .navbar .container-fluid { height: 100%; display: flex; align-items: center; padding: 0 1.5rem; }
        
        .navbar-brand {
            font-weight: 700; font-size: 1.2rem; color: #fff !important;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .navbar-brand i { color: var(--sincere-yellow); font-size: 1.4rem; }

        .brand-strip {
            height: 6px; width: 100%;
            background: linear-gradient(90deg, var(--calm-water-blue-dark) 0%, var(--calm-water-blue-dark) 50%, var(--atmospheric-blue) 50%, var(--atmospheric-blue) 85%, var(--sincere-yellow) 85%, var(--sincere-yellow) 100%);
            position: absolute; bottom: 0; left: 0;
        }

        /* === NOTIFICATION STYLES === */
        .notification-dropdown { width: 320px; overflow: hidden; }
        .notification-list { max-height: 300px; overflow-y: auto; }
        .avatar-icon { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
        .bg-blue-subtle { background-color: rgba(64, 107, 133, 0.05); }
        
        /* ===================================
           SIDEBAR
        =================================== */
        .sidebar {
            position: fixed; top: 70px; left: 0; bottom: 0; width: 270px;
            background: linear-gradient(180deg, var(--calm-water-blue), var(--calm-water-blue-dark));
            box-shadow: 3px 0 15px rgba(64, 107, 133, 0.1);
            overflow-y: auto; transition: transform 0.3s ease-in-out; z-index: 1020;
            padding-bottom: 2rem;
        }
        
        .sidebar-user-info {
            padding: 1.5rem; text-align: center; color: #fff;
            background: rgba(255, 255, 255, 0.05); border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-user-info .user-avatar {
            width: 60px; height: 60px; margin: 0 auto 0.8rem;
            background: linear-gradient(135deg, var(--sincere-yellow), var(--sincere-yellow-dark));
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(255, 237, 0, 0.25); font-size: 1.8rem; color: var(--calm-water-blue-dark);
        }
        
        .sidebar-nav { padding: 1rem 0; }
        .sidebar-nav .nav-section-title {
            color: rgba(255, 255, 255, 0.5); font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px; padding: 0.8rem 1.5rem 0.4rem;
        }
        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85); padding: 0.8rem 1.5rem;
            display: flex; align-items: center; font-size: 0.95rem; transition: all 0.2s; text-decoration: none;
        }
        .sidebar-nav .nav-link:hover { background: rgba(255, 255, 255, 0.1); color: #fff; padding-left: 1.8rem; }
        .sidebar-nav .nav-link i { width: 24px; font-size: 1.1rem; margin-right: 10px; text-align: center; }
        .sidebar-nav .nav-link.active {
            background: linear-gradient(90deg, rgba(255,255,255,0.15), transparent);
            color: #fff; border-left: 4px solid var(--sincere-yellow);
        }
        
        /* ===================================
           MAIN CONTENT
        =================================== */
        .main-wrapper {
            margin-left: 270px; min-height: 100vh; 
            padding-top: 70px; display: flex; flex-direction: column;
            transition: margin-left 0.3s ease;
        }
        .main-content { padding: 1.5rem; flex: 1; width: 100%; }
        
        /* ===================================
           FOOTER
        =================================== */
        .footer {
            background-color: #fff; border-top: 1px solid rgba(64, 107, 133, 0.1);
            width: 100%; flex-shrink: 0; position: relative;
        }
        .footer-content {
            padding: 1rem 1.5rem; display: flex; justify-content: space-between;
            align-items: center; font-size: 0.85rem; color: var(--calm-water-blue-dark);
        }
        .footer .brand-strip { position: absolute; bottom: 0; left: 0; width: 100%; }

        /* ===================================
           RESPONSIVE
        =================================== */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); width: 260px; }
            .sidebar.show { transform: translateX(0); box-shadow: 5px 0 15px rgba(0,0,0,0.2); }
            .main-wrapper { margin-left: 0 !important; width: 100% !important; }
            .sidebar-toggle {
                border: 1px solid rgba(255,255,255,0.3); padding: 0.25rem 0.5rem;
                border-radius: 4px; background: transparent; color: white; font-size: 1.2rem;
            }
            .main-content { padding: 1rem; }
            .footer-content { flex-direction: column; text-align: center; gap: 0.5rem; }
        }
        .sidebar-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.5); z-index: 1015; backdrop-filter: blur(2px);
        }
        .sidebar-overlay.show { display: block; }
    </style>
    
    @stack('styles')
</head>
<body>
    
    <!-- LOADING SCREEN (LOGO BJB) -->
    <div id="pageLoader">
        <img src="{{ asset('images/bjbsekuritas.png') }}" alt="Loading..." class="loader-logo" onerror="this.style.display='none'">
        <div class="mt-3 text-muted small fw-bold">Memuat Halaman...</div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <button class="sidebar-toggle d-lg-none me-2" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            
            <a class="navbar-brand me-auto" href="{{ route('dashboard') }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span class="d-none d-sm-inline">Sistem Berita Acara</span>
                <span class="d-sm-none">e-BA</span>
            </a>
            
            <div class="d-flex align-items-center gap-3">
                <!-- NOTIFIKASI -->
                @if(Auth::user()->isApprover())
                <div class="dropdown">
                    <a class="nav-link text-white position-relative" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell-fill fs-5"></i>
                        @php 
                            $unreadCount = Auth::user()->unreadNotifications()->count(); 
                        @endphp
                        @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem;">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 notification-dropdown">
                        <li class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
                            <span class="fw-bold small text-muted text-uppercase">Notifikasi</span>
                            @if($unreadCount > 0)
                            <a href="{{ route('notifications.markAllRead') }}" class="text-decoration-none small" style="color: var(--calm-water-blue)">Tandai dibaca</a>
                            @endif
                        </li>
                        <div class="notification-list">
                            @forelse(Auth::user()->notifications()->take(10)->get() as $notification)
                                <li>
                                    <a class="dropdown-item d-flex gap-3 py-3 border-bottom {{ $notification->read_at ? '' : 'bg-blue-subtle' }}" 
                                       href="{{ route('notifications.read', $notification->id) }}">
                                        <div class="flex-shrink-0">
                                            @if(isset($notification->data['type']) && $notification->data['type'] == 'approved')
                                                <span class="avatar-icon bg-success-subtle text-success"><i class="bi bi-check-circle-fill"></i></span>
                                            @elseif(isset($notification->data['type']) && $notification->data['type'] == 'rejected')
                                                <span class="avatar-icon bg-danger-subtle text-danger"><i class="bi bi-x-circle-fill"></i></span>
                                            @else
                                                <span class="avatar-icon bg-primary-subtle text-primary"><i class="bi bi-info-circle-fill"></i></span>
                                            @endif
                                        </div>
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-0 small fw-bold text-wrap {{ $notification->read_at ? 'text-muted' : 'text-dark' }}">
                                                    {{ $notification->data['title'] ?? 'Notifikasi Baru' }}
                                                </h6>
                                                @if(!$notification->read_at)
                                                <span class="badge bg-danger rounded-circle p-1" style="width: 8px; height: 8px;"></span>
                                                @endif
                                            </div>
                                            <p class="mb-0 small text-muted text-wrap mt-1" style="line-height: 1.3;">
                                                {{ Str::limit($notification->data['message'] ?? '', 60) }}
                                            </p>
                                            <small class="text-secondary" style="font-size: 0.7rem;">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="py-5 text-center">
                                    <i class="bi bi-bell-slash text-muted fs-1 opacity-25"></i>
                                    <p class="small text-muted mt-2 mb-0">Belum ada notifikasi</p>
                                </li>
                            @endforelse
                        </div>
                        @if(Auth::user()->notifications()->count() > 10)
                        <li class="text-center py-2 border-top bg-light">
                            <a href="#" class="small text-decoration-none fw-bold text-company">Lihat Semua</a>
                        </li>
                        @endif
                    </ul>
                </div>
                @endif

                <!-- USER MENU -->
                <div class="dropdown border-start ps-3 border-white border-opacity-25">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="d-none d-md-block text-end" style="line-height: 1.2;">
                            <small class="d-block fw-bold">{{ Auth::user()->name }}</small>
                            <span style="font-size: 0.7rem; opacity: 0.8;">{{ Auth::user()->jabatan ?? 'User' }}</span>
                        </div>
                        <i class="bi bi-person-circle fs-4"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="bi bi-person me-2 text-muted"></i> Profil Saya
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="brand-strip"></div>
    </nav>
    
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-user-info">
            <div class="user-avatar">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="user-name">{{ Auth::user()->name }}</div>
            <div class="user-role badge bg-warning text-dark">{{ Auth::user()->getRoleLabel() }}</div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section-title">Menu Utama</div>
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('nasabah.*') && !request()->routeIs('nasabah.import.*') ? 'active' : '' }}" href="{{ route('nasabah.index') }}">
                <i class="bi bi-people-fill"></i> Data Nasabah
            </a>
            @if(Auth::user()->isCS())
            <a class="nav-link {{ request()->routeIs('nasabah.import.*') ? 'active' : '' }}" href="{{ route('nasabah.import.form') }}">
                <i class="bi bi-cloud-upload-fill"></i> Import Nasabah
            </a>
            @endif
            
            <div class="nav-section-title">Berita Acara</div>
            <a class="nav-link {{ request()->routeIs('berita-acara.index') ? 'active' : '' }}" href="{{ route('berita-acara.index') }}">
                <i class="bi bi-file-earmark-text-fill"></i> Daftar BA
            </a>
            @if(Auth::user()->isCS())
            <a class="nav-link {{ request()->routeIs('berita-acara.create*') ? 'active' : '' }}" href="{{ route('berita-acara.create') }}">
                <i class="bi bi-plus-circle-fill"></i> Buat BA Baru
            </a>
            @endif
            
            @if(Auth::user()->isAdmin())
            <div class="nav-section-title">Pengaturan</div>
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="bi bi-person-fill-gear"></i> Kelola User
            </a>
            <a class="nav-link {{ request()->routeIs('backup.*') ? 'active' : '' }}" href="{{ route('backup.index') }}">
                <i class="bi bi-database-fill-gear"></i> Backup & Restore
            </a>
            @endif
        </nav>
    </aside>
    
    <div class="main-wrapper">
        <div class="main-content">
            <!-- Alert Success/Error dari Session -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
        
        <footer class="footer">
            <div class="footer-content">
                <span class="fw-bold" style="color: var(--calm-water-blue);">Sistem Berita Acara APUPPT & PPSPM</span>
                <span>&copy; {{ date('Y') }} <strong>PT bjb Sekuritas Jawa Barat</strong></span>
            </div>
            <div class="brand-strip"></div>
        </footer>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarToggle');
            const pageLoader = document.getElementById('pageLoader');

            // Loading Animation - mengabaikan link download dan dropdown
            const links = document.querySelectorAll('a[href]:not([href^="#"]):not([target="_blank"]):not(.dropdown-toggle):not([href*="download"]):not([id="downloadBtn"])');
            
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (e.ctrlKey || e.metaKey) return;
                    if (pageLoader) pageLoader.style.display = 'flex';
                });
            });

            window.addEventListener('pageshow', function(event) {
                if (pageLoader) pageLoader.style.display = 'none';
            });

            // Sidebar Toggle Logic
            if(toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });
            }
            if(overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
            
            document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 991) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>