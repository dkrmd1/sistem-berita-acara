@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4">
    
    <!-- 1. HEADER & CLOCK WIDGET (PERBAIKAN KONTRAS JAM) -->
    <div class="row mb-4 g-3">
        <!-- Welcome Message -->
        <div class="col-12 col-md-7 col-lg-8">
            <div class="welcome-banner p-4 rounded-4 position-relative overflow-hidden bg-white shadow-sm border h-100 d-flex align-items-center">
                <!-- Dekorasi Background -->
                <div class="decoration-circle-1 position-absolute rounded-circle opacity-10"></div>
                <div class="decoration-circle-2 position-absolute rounded-circle opacity-10"></div>

                <div class="position-relative z-1 w-100">
                    <div class="d-flex align-items-center mb-2 text-muted small fw-bold text-uppercase spacing-1">
                        <i class="bi bi-grid-fill me-2 text-company"></i>
                        <span>Dashboard Overview</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-1 display-6" style="font-size: 1.75rem;">
                        <span id="greetingText" class="text-company">Halo</span>, 
                        <span class="text-gradient-company">{{ Str::limit(Auth::user()->name, 20) }}</span>
                        <span class="wave-emoji d-inline-block">👋</span>
                    </h2>
                    <p class="text-muted mb-0 small opacity-75">Siap untuk memantau dan mengelola data hari ini?</p>
                </div>
            </div>
        </div>

        <!-- Live Clock Widget (FIX: Tulisan Gelap agar Terbaca) -->
        <div class="col-12 col-md-5 col-lg-4">
            <div class="welcome-banner p-4 rounded-4 position-relative overflow-hidden bg-white shadow-sm border h-100 d-flex align-items-center justify-content-center">
                <!-- Dekorasi Background (Tetap ada tapi samar) -->
                <div class="decoration-clock-1 position-absolute rounded-circle opacity-10"></div>
                <div class="decoration-clock-2 position-absolute rounded-circle opacity-10"></div>

                <div class="position-relative z-1 text-center w-100">
                    <!-- Tanggal (Warna Abu Gelap) -->
                    <div class="d-flex align-items-center justify-content-center mb-1 text-secondary">
                        <i class="bi bi-calendar2-week-fill me-2 text-company"></i>
                        <span id="dateDisplay" class="fw-bold text-uppercase spacing-1" style="font-size: 0.85rem; color: #444;">...</span>
                    </div>
                    
                    <!-- Jam Besar (Warna Biru Tua Solid agar Jelas) -->
                    <div id="clockDisplay" class="clock-time fw-bold display-4 mb-0" style="line-height: 1; color: #1e5a7d;">
                        00<span class="blink mx-1" style="color: #1e5a7d;">:</span>00<span class="blink mx-1" style="color: #1e5a7d;">:</span>00
                    </div>
                    
                    <!-- Label Zona Waktu -->
                    <small class="text-muted opacity-50" style="font-size: 0.65rem; letter-spacing: 3px;">WAKTU SERVER</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STATISTIK CARDS -->
    <div class="row g-3 mb-4">
        <!-- Total Nasabah -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-calm-water card-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-2 small fw-semibold text-uppercase">Total Nasabah</p>
                            <h2 class="mb-0 fw-bold text-white counter">{{ $totalNasabah }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-people-fill fs-1 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nasabah Belum BA -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-sincere-yellow card-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-2 small fw-semibold text-uppercase">Belum Punya BA</p>
                            <h2 class="mb-0 fw-bold text-white counter">{{ $nasabahBelumBA }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-clock-history fs-1 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nasabah Sudah BA -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-success card-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-2 small fw-semibold text-uppercase">Sudah Punya BA</p>
                            <h2 class="mb-0 fw-bold text-white counter">{{ $nasabahSudahBA }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-check-circle-fill fs-1 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total BA -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-atmospheric card-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-2 small fw-semibold text-uppercase">Total Berita Acara</p>
                            <h2 class="mb-0 fw-bold text-white counter">{{ $totalBA }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-file-earmark-text-fill fs-1 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. STATUS BA CARDS (REJECTED DIHAPUS, KOLOM DISESUAIKAN JADI col-lg-6) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-gradient-company text-white py-3 border-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-bar-chart-fill me-2"></i> Status Berita Acara
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center g-3">
                        <!-- Pending (Diperlebar jadi col-lg-6) -->
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="status-box p-4 rounded-4 h-100 border border-warning border-opacity-25 bg-warning-subtle">
                                <div class="mb-2"><i class="bi bi-clock-fill text-warning fs-1"></i></div>
                                <h3 class="fw-bold text-dark counter">{{ $baPending }}</h3>
                                <small class="text-muted fw-bold text-uppercase">Pending</small>
                            </div>
                        </div>
                        <!-- Approved (Diperlebar jadi col-lg-6) -->
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="status-box p-4 rounded-4 h-100 border border-success border-opacity-25 bg-success-subtle">
                                <div class="mb-2"><i class="bi bi-check-circle-fill text-success fs-1"></i></div>
                                <h3 class="fw-bold text-dark counter">{{ $baApproved }}</h3>
                                <small class="text-muted fw-bold text-uppercase">Approved</small>
                            </div>
                        </div>
                        <!-- Bagian Rejected telah dihapus -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- 4. TABEL BA TERBARU -->
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100 bg-white">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-company">
                        <i class="bi bi-file-earmark-text-fill me-2"></i> BA Terbaru
                    </h6>
                    <a href="{{ route('berita-acara.index') }}" class="btn btn-sm btn-light text-primary fw-bold rounded-pill px-3 border">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentBA->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 text-secondary small text-uppercase">Nomor BA</th>
                                    <th class="text-secondary small text-uppercase">Nasabah</th>
                                    <th class="d-none d-lg-table-cell text-secondary small text-uppercase">Tanggal</th>
                                    <th class="text-secondary small text-uppercase text-center">Status</th>
                                    <th class="text-center text-secondary small text-uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBA as $ba)
                                <tr>
                                    <td class="ps-4 fw-semibold text-company">{{ $ba->nomor_ba }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $ba->nasabah->nama }}</div>
                                        <small class="text-muted d-lg-none">{{ $ba->getTanggalBaFormatted() }}</small>
                                    </td>
                                    <td class="d-none d-lg-table-cell text-muted small">
                                        {{ $ba->getTanggalBaFormatted() }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-{{ $ba->getStatusBadgeColor() }} bg-opacity-10 text-{{ $ba->getStatusBadgeColor() }} border border-{{ $ba->getStatusBadgeColor() }} px-3">
                                            {{ $ba->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('berita-acara.show', $ba->id) }}" class="btn btn-sm btn-light text-primary rounded-circle border" data-bs-toggle="tooltip" title="Detail">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="mb-3 opacity-25"><i class="bi bi-inbox-fill display-1 text-secondary"></i></div>
                        <p class="text-muted">Belum ada data Berita Acara</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 5. QUICK ACTIONS & INFO -->
        <div class="col-lg-4 col-md-12 mb-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4 rounded-4 bg-gradient-company text-white position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 translate-middle rounded-circle bg-white opacity-10" style="width: 120px; height: 120px; margin-top: -30px; margin-right: -30px;"></div>
                
                <div class="card-body p-4 position-relative z-1">
                    <h6 class="fw-bold mb-4 border-bottom border-white border-opacity-25 pb-2 d-flex align-items-center">
                        <i class="bi bi-lightning-charge-fill me-2"></i> Akses Cepat
                    </h6>
                    
                    <div class="d-flex flex-column gap-3">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('users.index') }}" class="btn btn-light text-company fw-bold shadow-sm py-2 text-start px-3 rounded-3 d-flex align-items-center">
                                <div class="bg-company-subtle p-2 rounded-circle me-3"><i class="bi bi-people-fill"></i></div>
                                <div>Kelola User<br><small class="fw-normal opacity-75">Tambah/Edit Pengguna</small></div>
                            </a>
                        @elseif(Auth::user()->isCS())
                            <a href="{{ route('berita-acara.create') }}" class="btn btn-light text-company fw-bold shadow-sm py-2 text-start px-3 rounded-3 d-flex align-items-center">
                                <div class="bg-company-subtle p-2 rounded-circle me-3"><i class="bi bi-plus-lg"></i></div>
                                <div>Buat BA Baru<br><small class="fw-normal opacity-75">Input Berita Acara</small></div>
                            </a>
                            <a href="{{ route('nasabah.import.form') }}" class="btn btn-outline-light fw-bold py-2 text-start px-3 rounded-3 d-flex align-items-center border-2 hover-white">
                                <div class="bg-white bg-opacity-25 p-2 rounded-circle me-3"><i class="bi bi-file-earmark-excel"></i></div>
                                <div>Import Nasabah<br><small class="fw-normal opacity-75">Upload via Excel</small></div>
                            </a>
                        @elseif(Auth::user()->isApprover())
                            <a href="{{ route('berita-acara.index', ['filter' => 'my', 'status' => 'pending']) }}" class="btn btn-light text-company fw-bold shadow-sm py-2 text-start px-3 rounded-3 d-flex align-items-center">
                                <div class="bg-company-subtle p-2 rounded-circle me-3 position-relative">
                                    <i class="bi bi-check-square-fill"></i>
                                    @if($myData && $myData->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light p-1" style="font-size: 8px;">
                                        {{ $myData->count() }}
                                    </span>
                                    @endif
                                </div>
                                <div>Approve BA<br><small class="fw-normal opacity-75">Menunggu Persetujuan</small></div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Role Info -->
            <div class="card shadow-sm border-0 rounded-4 bg-info-subtle">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white p-2 rounded-circle shadow-sm me-3 text-info-emphasis">
                            <i class="bi bi-shield-lock-fill fs-4"></i>
                        </div>
                        <div>
                            <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem;">Login Sebagai</small>
                            <h6 class="mb-0 fw-bold text-company">{{ Auth::user()->getRoleLabel() }}</h6>
                        </div>
                    </div>
                    <p class="small text-muted mb-0" style="line-height: 1.4;">
                        Anda login dengan hak akses <strong>{{ Auth::user()->getRoleLabel() }}</strong>. Gunakan menu di samping untuk navigasi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* --- GENERAL STYLES --- */
    .text-company { color: #1e5a7d; }
    .bg-company-subtle { background-color: rgba(30, 90, 125, 0.1); color: #1e5a7d; }
    
    /* Gradients */
    .bg-gradient-company { background: linear-gradient(135deg, #1e5a7d 0%, #2d7a9e 100%); }
    .gradient-calm-water { background: linear-gradient(135deg, #1e5a7d 0%, #2d7a9e 100%); color: white; }
    .gradient-sincere-yellow { background: linear-gradient(135deg, #f4b41a 0%, #f7c748 100%); color: white; }
    .gradient-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
    .gradient-atmospheric { background: linear-gradient(135deg, #3ba3d8 0%, #5cb8e6 100%); color: white; }

    /* --- HEADER SYNC STYLES --- */
    .welcome-banner {
        min-height: 140px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .welcome-banner:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(30, 90, 125, 0.1) !important;
        border-color: rgba(30, 90, 125, 0.2) !important;
    }
    .text-gradient-company {
        background: linear-gradient(135deg, #1e5a7d 0%, #29AAE2 50%, #2d7a9e 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .decoration-circle-1 { width: 150px; height: 150px; background: #1e5a7d; top: -50px; right: -30px; filter: blur(40px); }
    .decoration-circle-2 { width: 100px; height: 100px; background: #f4b41a; bottom: -30px; left: -20px; filter: blur(40px); }
    
    /* Decoration Clock (Opacity rendah agar tulisan jelas) */
    .decoration-clock-1 { width: 120px; height: 120px; background: #f4b41a; top: -40px; right: -40px; filter: blur(40px); }
    .decoration-clock-2 { width: 100px; height: 100px; background: #3ba3d8; bottom: -30px; left: -30px; filter: blur(40px); }

    .wave-emoji { animation: wave-animation 2.5s infinite; transform-origin: 70% 70%; }
    @keyframes wave-animation { 0%, 60%, 100% { transform: rotate(0.0deg) } 10%, 30% { transform: rotate(14.0deg) } 20% { transform: rotate(-8.0deg) } 40% { transform: rotate(-4.0deg) } 50% { transform: rotate(10.0deg) } }

    /* --- CLOCK FIX (KONTRAS TINGGI) --- */
    .clock-time { 
        font-family: 'Consolas', 'Monaco', monospace; 
        font-weight: 800; 
        /* Hapus text shadow putih yang bikin buram di background putih */
        text-shadow: 2px 2px 0px rgba(0,0,0,0.05); 
    }
    .blink { animation: blinker 1s linear infinite; } 
    @keyframes blinker { 50% { opacity: 0; } }
    .spacing-1 { letter-spacing: 1px; }

    /* --- BUTTON & CARDS --- */
    .hover-white:hover { background-color: rgba(255, 255, 255, 0.2); color: white; }
    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    .status-box { transition: transform 0.2s ease; }
    .status-box:hover { transform: scale(1.02); }
</style>
@endpush

@push('scripts')
<!-- SweetAlert2 Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- JAM REAL-TIME ---
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        // Update dengan warna teks solid (hardcoded di style inline untuk prioritas)
        document.getElementById('clockDisplay').innerHTML = `${h}<span class="blink mx-1" style="color: #1e5a7d;">:</span>${m}<span class="blink mx-1" style="color: #1e5a7d;">:</span>${s}`;
        
        const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
        document.getElementById('dateDisplay').innerText = now.toLocaleDateString('id-ID', options);

        const hour = now.getHours();
        let greeting = 'Halo';
        if (hour >= 5 && hour < 11) greeting = 'Selamat Pagi';
        else if (hour >= 11 && hour < 15) greeting = 'Selamat Siang';
        else if (hour >= 15 && hour < 18) greeting = 'Selamat Sore';
        else greeting = 'Selamat Malam';
        
        const greetingEl = document.getElementById('greetingText');
        if (greetingEl.innerText !== greeting) greetingEl.innerText = greeting;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // --- ANIMASI COUNTER ---
    document.addEventListener("DOMContentLoaded", () => {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = +counter.innerText;
            const increment = target / 200;
            const updateCount = () => {
                const c = +counter.innerText;
                if(c < target) {
                    counter.innerText = Math.ceil(c + increment);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            };
            // updateCount(); 
        });

        // --- NOTIFIKASI KEREN (SWEETALERT2 TOAST) ---
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}' });
        @endif

        @if(session('error'))
            Toast.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}' });
        @endif
        
        @if(session('warning'))
            Toast.fire({ icon: 'warning', title: 'Perhatian!', text: '{{ session('warning') }}' });
        @endif
    });
</script>
@endpush