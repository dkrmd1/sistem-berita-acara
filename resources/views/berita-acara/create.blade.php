@extends('layouts.app')

@section('title', 'Buat Berita Acara Baru')

@section('content')
<div class="container-fluid px-4">
    
    <!-- 1. PAGE HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-lg bg-company-subtle text-company rounded-circle me-3">
                            <i class="bi bi-plus-circle-fill display-6"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-company mb-1">Buat Berita Acara</h3>
                            <p class="text-muted mb-0">Pilih nasabah dari daftar di bawah untuk memulai.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('berita-acara.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STATISTIK MINI -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-sincere-yellow card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">Belum Punya BA</p>
                            <h2 class="mb-0 fw-bold text-white">{{ $nasabahs->total() }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-person-exclamation fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-calm-water card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">BA Dibuat Hari Ini</p>
                            <h2 class="mb-0 fw-bold text-white">
                                {{ \App\Models\BeritaAcara::whereDate('created_at', today())->count() }}
                            </h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-calendar-check fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PENCARIAN NASABAH -->
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('berita-acara.create') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-10">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Cari Nasabah</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-2 bg-light" 
                                   placeholder="Ketik Nama, Nomor KTP, atau NPWP..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-company w-100 fw-bold"><i class="bi bi-search me-2"></i> Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. TABEL PEMILIHAN -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-company"><i class="bi bi-people-fill me-2"></i> Pilih Nasabah</h6>
        </div>
        <div class="card-body p-0">
            @if($nasabahs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%" class="text-center text-secondary small text-uppercase py-3">No</th>
                            <th class="text-secondary small text-uppercase py-3">Nama Nasabah</th>
                            <th class="text-secondary small text-uppercase py-3">Identitas (KTP)</th>
                            <th class="text-secondary small text-uppercase py-3">NPWP</th>
                            <th class="text-secondary small text-uppercase py-3">Tanggal Lahir</th>
                            <th width="15%" class="text-center text-secondary small text-uppercase py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nasabahs as $index => $nasabah)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $nasabahs->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-company-light text-company me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        {{ strtoupper(substr($nasabah->nama, 0, 1)) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $nasabah->nama }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="font-monospace bg-light px-2 py-1 rounded border text-dark fw-bold">{{ $nasabah->getKtpFormatted() }}</span>
                            </td>
                            <td>
                                @if($nasabah->npwp)
                                    <span class="font-monospace bg-light px-2 py-1 rounded border text-dark">{{ $nasabah->getNpwpFormatted() }}</span>
                                @else
                                    <span class="text-muted small fst-italic">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $nasabah->getTanggalLahirFormatted() }}</span>
                                    <small class="text-muted">Umur: {{ $nasabah->getUmur() }} thn</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-1">
                                    <!-- Tombol Detail -->
                                    <a href="{{ route('nasabah.show', $nasabah->id) }}" 
                                       class="btn btn-sm btn-light text-primary border rounded-circle" 
                                       data-bs-toggle="tooltip" 
                                       title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    
                                    <!-- Tombol Pilih (Primary) -->
                                    <a href="{{ route('berita-acara.create.form', $nasabah->id) }}" 
                                       class="btn btn-sm btn-company px-3 rounded-pill shadow-sm fw-bold"
                                       title="Pilih Nasabah">
                                        Pilih <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                <div><small class="text-muted fw-semibold">Menampilkan {{ $nasabahs->firstItem() }} - {{ $nasabahs->lastItem() }} dari {{ $nasabahs->total() }} data</small></div>
                <div>{{ $nasabahs->appends(request()->query())->links() }}</div>
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-3 opacity-25"><i class="bi bi-search display-1 text-secondary"></i></div>
                <h5 class="text-muted fw-semibold">
                    @if(request('search')) Nasabah Tidak Ditemukan @else Semua Nasabah Sudah Memiliki BA @endif
                </h5>
                <p class="text-muted small mb-4">
                    @if(request('search')) Coba kata kunci lain atau reset pencarian. @else Anda dapat mengimport data nasabah baru. @endif
                </p>
                
                @if(request('search'))
                    <a href="{{ route('berita-acara.create') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-clockwise me-2"></i> Reset Pencarian
                    </a>
                @else
                    <a href="{{ route('nasabah.import.form') }}" class="btn btn-company rounded-pill px-4 shadow-sm">
                        <i class="bi bi-file-earmark-arrow-up me-2"></i> Import Nasabah
                    </a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Info Card -->
    <div class="card mt-4 shadow-sm border-0 bg-info-subtle rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-start">
                <div class="me-3 text-company bg-white p-2 rounded-circle shadow-sm">
                    <i class="bi bi-lightbulb-fill fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-company mb-2">Panduan Pembuatan:</h6>
                    <ol class="mb-0 ps-3 small text-muted fw-semibold" style="line-height: 1.6;">
                        <li>Cari nasabah yang belum memiliki Berita Acara pada tabel di atas.</li>
                        <li>Klik tombol <strong>"Pilih"</strong> berwarna biru tua di sebelah kanan data nasabah.</li>
                        <li>Anda akan diarahkan ke halaman formulir pengecekan (Watchlist & Existing Database).</li>
                        <li>Setelah diisi, Berita Acara akan dibuat dan menunggu persetujuan (Approval).</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Tambahkan Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    /* === BRAND COLORS === */
    :root { --calm-water-blue: #165581; --atmospheric-blue: #29AAE2; --sincere-yellow: #EFCA18; }
    
    /* === UTILS === */
    .font-monospace { font-family: 'Courier New', Consolas, monospace; }
    .rounded-4 { border-radius: 1rem !important; }
    .icon-box-lg { width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; }
    .text-company { color: var(--calm-water-blue); }
    .bg-company-subtle { background-color: rgba(22, 85, 129, 0.1); color: var(--calm-water-blue); }

    /* === CARDS === */
    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    
    .gradient-calm-water { background: linear-gradient(135deg, var(--calm-water-blue) 0%, #2d7a9e 100%); color: white; }
    .gradient-sincere-yellow { background: linear-gradient(135deg, var(--sincere-yellow) 0%, #f7c748 100%); color: white; }

    /* === BUTTONS === */
    .btn-company { background-color: var(--calm-water-blue); border-color: var(--calm-water-blue); color: white; transition: all 0.3s ease; }
    .btn-company:hover { background-color: #114466; border-color: #114466; color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(22, 85, 129, 0.3); }
    
    /* === SWEETALERT CUSTOM === */
    .custom-swal-popup {
        border-radius: 24px !important; padding-top: 0 !important; overflow: hidden;
        box-shadow: 0 20px 50px rgba(22, 85, 129, 0.2) !important; font-family: 'Segoe UI', Tahoma, sans-serif;
    }
    .custom-swal-popup::before {
        content: ''; display: block; height: 8px; width: 100%;
        background: linear-gradient(90deg, var(--calm-water-blue) 0%, var(--calm-water-blue) 33%, var(--atmospheric-blue) 33%, var(--atmospheric-blue) 66%, var(--sincere-yellow) 66%, var(--sincere-yellow) 100%);
    }
    
    .swal2-confirm-btn {
        background: linear-gradient(135deg, var(--calm-water-blue) 0%, #12466b 100%) !important;
        border-radius: 50px !important; font-weight: 600 !important; padding: 12px 32px !important;
        box-shadow: 0 4px 15px rgba(22, 85, 129, 0.3) !important; transition: transform 0.2s; color: white !important; border: none !important;
    }
    .swal2-confirm-btn:hover { transform: translateY(-2px); }

    .swal2-cancel-btn {
        background: #f8f9fa !important; color: #6c757d !important; border: 1px solid #dee2e6 !important;
        border-radius: 50px !important; font-weight: 600 !important; padding: 12px 24px !important;
    }

    .table thead.bg-light th { background-color: #f8f9fa; font-weight: 600; letter-spacing: 0.5px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })

    // === POPUP CEK TANDA TANGAN CS ===
    @if(session('ttd_missing'))
    Swal.fire({
        title: 'Tanda Tangan Dibutuhkan!',
        html: `
            <div class="mb-3">
                <img src="https://cdn-icons-png.flaticon.com/512/1000/1000997.png" width="110" class="img-fluid mb-3 animate__animated animate__pulse animate__infinite" style="filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));">
            </div>
            <h5 class="text-muted fw-bold mb-3">Halo, {{ Auth::user()->name }}</h5>
            <p class="text-dark px-2">
                Untuk membuat <strong>Berita Acara</strong>, sistem memerlukan Tanda Tangan Digital Anda yang akan dibubuhkan otomatis pada dokumen PDF.
            </p>
            <div class="alert alert-warning d-inline-flex align-items-center mt-1 border-0 shadow-sm rounded-3 py-2 px-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <small class="fw-bold">Mohon upload TTD Anda terlebih dahulu.</small>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-upload me-2"></i> Upload Sekarang',
        cancelButtonText: 'Nanti Saja',
        reverseButtons: true,
        focusConfirm: true,
        backdrop: `rgba(22, 85, 129, 0.6)`,
        customClass: {
            popup: 'custom-swal-popup animate__animated animate__zoomIn',
            confirmButton: 'swal2-confirm-btn',
            cancelButton: 'swal2-cancel-btn me-2'
        },
        buttonsStyling: false,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('profile.index') }}";
        }
    });
    @endif
</script>
@endpush