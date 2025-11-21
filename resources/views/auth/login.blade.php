<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - Sistem Berita Acara bjb Sekuritas</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --calm-water-blue: #406B85;
            --calm-water-blue-dark: #2E5166;
            --atmospheric-blue: #41A1E0;
            --sincere-yellow: #FFED00;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--calm-water-blue-dark) 0%, #1a3a4f 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 0;
            pointer-events: none;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            margin: auto;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            /* Shadow lebih lembut */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden; /* Border radius tetap rapi */
            animation: fadeInUp 0.8s ease-out;
            /* Tidak ada lagi border-top warna */
        }

        .login-header {
            background: white;
            padding: 40px 30px 20px;
            text-align: center;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--calm-water-blue), var(--calm-water-blue-dark));
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 10px 20px rgba(64, 107, 133, 0.3);
        }

        .logo-icon i {
            font-size: 2.5rem;
            color: var(--sincere-yellow);
        }

        .login-header h4 {
            color: var(--calm-water-blue-dark);
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 5px;
        }

        .login-body {
            padding: 20px 40px 40px;
        }

        /* Input Styles */
        .input-group-text {
            background: transparent;
            border-right: none;
            color: #adb5bd;
            border-radius: 12px 0 0 12px;
            border-color: #e2e8f0;
        }

        .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
            border-color: #e2e8f0;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--atmospheric-blue);
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--atmospheric-blue);
        }
        
        .input-group:focus-within .input-group-text i {
            color: var(--atmospheric-blue);
        }

        /* Button Styles */
        .btn-login {
            background: linear-gradient(135deg, var(--calm-water-blue) 0%, var(--calm-water-blue-dark) 100%);
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(46, 81, 102, 0.3);
            color: white;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--atmospheric-blue) 0%, var(--calm-water-blue) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(65, 161, 224, 0.4);
            color: white;
        }

        .login-footer {
            background: #f8fafc;
            padding: 15px;
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom SweetAlert Styles */
        .swal2-popup {
            border-radius: 20px !important;
            padding: 2rem !important;
            font-family: 'Inter', sans-serif !important;
        }
        .swal2-title {
            color: var(--calm-water-blue-dark) !important;
            font-size: 1.5rem !important;
        }
        .loading-spinner {
            color: var(--atmospheric-blue) !important;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            <!-- Hapus div brand-strip di sini -->
            
            <!-- Header -->
            <div class="login-header">
                <div class="logo-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h4>Sistem Berita Acara</h4>
                <p class="text-muted small mb-0">PT bjb Sekuritas Jawa Barat</p>
            </div>
            
            <!-- Body -->
            <div class="login-body">
                <!-- Alert Messages -->
                @if(session('success'))
                <div class="alert alert-success d-flex align-items-center border-0 shadow-sm rounded-3 mb-4 bg-success-subtle text-success-emphasis" style="font-size: 0.9rem;">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center border-0 shadow-sm rounded-3 mb-4 bg-danger-subtle text-danger-emphasis" style="font-size: 0.9rem;">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
                @endif
                
                <!-- Form -->
                <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase" style="font-size: 0.75rem;">Email Perusahaan</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="user@bjbsekuritas.co.id" value="{{ old('email') }}" required autofocus>
                        </div>
                        @error('email') <small class="text-danger ms-1">{{ $message }}</small> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase" style="font-size: 0.75rem;">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        @error('password') <small class="text-danger ms-1">{{ $message }}</small> @enderror
                    </div>
                    
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-login" id="btnSubmit">
                        Masuk Aplikasi <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="login-footer">
                &copy; {{ date('Y') }} <strong>PT bjb Sekuritas Jawa Barat</strong>. <br>All Rights Reserved.
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Script Loading -->
    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            Swal.fire({
                title: 'Sedang Memproses',
                html: 'Memverifikasi akun Anda...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                color: '#2E5166',
                background: '#fff',
                customClass: {
                    loader: 'loading-spinner'
                }
            });
        });
    </script>

</body>
</html>