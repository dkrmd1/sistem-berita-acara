@extends('layouts.app')

@section('title', 'Data Nasabah')

@section('content')
<div class="container-fluid px-4">
    
    <!-- 1. PAGE HEADER (SINKRON DENGAN DASHBOARD) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-lg bg-company-subtle text-company rounded-circle me-3">
                            <i class="bi bi-people-fill display-6"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-company mb-1">Data Nasabah</h3>
                            <p class="text-muted mb-0">Kelola data nasabah untuk pembuatan Berita Acara</p>
                        </div>
                    </div>
                    
                    @if(Auth::user()->isCS())
                    <div class="d-flex gap-2">
                        <a href="{{ route('nasabah.import.form') }}" class="btn btn-company rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-file-earmark-arrow-up-fill me-2"></i> Import Excel
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 2. FILTER & SEARCH -->
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('nasabah.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Pencarian Data</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-2 bg-light" placeholder="Cari Nama, KTP, atau NPWP..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Status Berita Acara</label>
                        <select name="status" class="form-select bg-light cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Punya BA</option>
                            <option value="sudah" {{ request('status') == 'sudah' ? 'selected' : '' }}>Sudah Punya BA</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-company px-4 fw-bold flex-grow-1"><i class="bi bi-funnel-fill me-2"></i> Filter</button>
                            <a href="{{ route('nasabah.index') }}" class="btn btn-outline-secondary px-3" data-bs-toggle="tooltip" title="Reset Filter"><i class="bi bi-arrow-clockwise"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. STATISTIK MINI (WITH HOVER EFFECT) -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-calm-water card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">Total Nasabah</p>
                            <h2 class="mb-0 fw-bold text-white">{{ $nasabahs->total() }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-people-fill fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-sincere-yellow card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">Belum Punya BA</p>
                            <h2 class="mb-0 fw-bold text-white">{{ \App\Models\Nasabah::belumPunyaBeritaAcara()->count() }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-clock-history fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-atmospheric card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">Sudah Punya BA</p>
                            <h2 class="mb-0 fw-bold text-white">{{ \App\Models\Nasabah::sudahPunyaBeritaAcara()->count() }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-check-circle-fill fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. TABEL DATA -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-company"><i class="bi bi-table me-2"></i> Daftar Nasabah</h6>
            <small class="text-muted">Menampilkan {{ $nasabahs->count() }} data</small>
        </div>
        <div class="card-body p-0">
            @if($nasabahs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center text-secondary small text-uppercase py-3">No</th>
                            <th class="text-secondary small text-uppercase py-3">Identitas Nasabah</th>
                            <th class="text-secondary small text-uppercase py-3">Dokumen (KTP/NPWP)</th>
                            <th class="text-secondary small text-uppercase py-3">Informasi Lahir</th>
                            <th class="text-center text-secondary small text-uppercase py-3">Status</th>
                            <th class="text-center text-secondary small text-uppercase py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nasabahs as $index => $nasabah)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $nasabahs->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-company-light text-company me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($nasabah->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $nasabah->nama }}</div>
                                        <small class="text-muted"><i class="bi bi-geo-alt-fill me-1 text-danger"></i> {{ $nasabah->negara }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="font-monospace badge bg-light text-dark border text-start"><i class="bi bi-card-heading me-2 text-primary"></i>{{ $nasabah->getKtpFormatted() }}</span>
                                    @if($nasabah->npwp)
                                    <span class="font-monospace badge bg-light text-dark border text-start"><i class="bi bi-card-list me-2 text-success"></i>{{ $nasabah->getNpwpFormatted() }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $nasabah->getTanggalLahirFormatted() }}</span>
                                    <small class="text-muted">Umur: {{ $nasabah->getUmur() }} tahun</small>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($nasabah->has_berita_acara)
                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3">
                                    <i class="bi bi-check-circle-fill me-1"></i> Sudah Ada BA
                                </span>
                                @else
                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3">
                                    <i class="bi bi-clock-fill me-1"></i> Belum Ada BA
                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-pill" role="group">
                                    <a href="{{ route('nasabah.show', $nasabah->id) }}" class="btn btn-sm btn-light text-primary" data-bs-toggle="tooltip" title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    
                                    @if(Auth::user()->isCS() && !$nasabah->has_berita_acara)
                                    <a href="{{ route('berita-acara.create.form', $nasabah->id) }}" class="btn btn-sm btn-light text-success" data-bs-toggle="tooltip" title="Buat Berita Acara">
                                        <i class="bi bi-plus-circle-fill"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-light text-danger btn-delete" 
                                            data-id="{{ $nasabah->id }}" 
                                            data-nama="{{ $nasabah->nama }}"
                                            data-bs-toggle="tooltip" title="Hapus Data">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                    @endif
                                </div>
                                <!-- Hidden Delete Form -->
                                <form id="delete-form-{{ $nasabah->id }}" action="{{ route('nasabah.destroy', $nasabah->id) }}" method="POST" class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-4 bg-light border-top">
                <small class="text-muted fw-semibold">
                    Halaman {{ $nasabahs->currentPage() }} dari {{ $nasabahs->lastPage() }}
                </small>
                <div>{{ $nasabahs->links() }}</div>
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-3 opacity-25"><i class="bi bi-folder-x display-1 text-secondary"></i></div>
                <h5 class="text-muted fw-semibold">Data Tidak Ditemukan</h5>
                <p class="text-muted small mb-4">Coba ubah kata kunci pencarian atau filter status.</p>
                @if(Auth::user()->isCS())
                <a href="{{ route('nasabah.import.form') }}" class="btn btn-company px-4 rounded-pill shadow-sm">
                    <i class="bi bi-file-earmark-arrow-up-fill me-2"></i> Import Data
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    /* === BRAND VARIABLES & UTILS === */
    :root { --calm-water-blue: #165581; --atmospheric-blue: #29AAE2; --sincere-yellow: #EFCA18; }
    .font-monospace { font-family: 'Consolas', 'Monaco', monospace; }
    .text-company { color: var(--calm-water-blue); }
    .bg-company-subtle { background-color: rgba(22, 85, 129, 0.1); color: var(--calm-water-blue); }
    .cursor-pointer { cursor: pointer; }

    /* === BUTTONS === */
    .btn-company { background-color: var(--calm-water-blue); border-color: var(--calm-water-blue); color: white; transition: all 0.3s ease; }
    .btn-company:hover { background-color: #114466; border-color: #114466; color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(22, 85, 129, 0.3); }

    /* === CARDS & GRADIENTS === */
    .rounded-4 { border-radius: 1rem !important; }
    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    
    .gradient-calm-water { background: linear-gradient(135deg, #165581 0%, #2d7a9e 100%); color: white; }
    .gradient-atmospheric { background: linear-gradient(135deg, #29AAE2 0%, #5cb8e6 100%); color: white; }
    .gradient-sincere-yellow { background: linear-gradient(135deg, #EFCA18 0%, #f7c748 100%); color: white; }

    /* === HEADER ICON === */
    .icon-box-lg { width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; }

    /* === SWEETALERT CUSTOM === */
    .custom-swal-popup { border-radius: 24px !important; padding-top: 0 !important; overflow: hidden; font-family: 'Segoe UI', Tahoma, sans-serif; }
    .custom-swal-popup::before { content: ''; display: block; height: 8px; width: 100%; background: linear-gradient(90deg, var(--calm-water-blue) 0%, var(--calm-water-blue) 33%, var(--atmospheric-blue) 33%, var(--atmospheric-blue) 66%, var(--sincere-yellow) 66%, var(--sincere-yellow) 100%); }
    .custom-swal-title { color: var(--calm-water-blue) !important; font-weight: 800; font-size: 1.5rem; margin-top: 1.5rem; }
    .swal2-confirm-btn { background: linear-gradient(135deg, var(--calm-water-blue) 0%, #12466b 100%) !important; border-radius: 50px !important; padding: 10px 30px !important; box-shadow: 0 4px 15px rgba(22, 85, 129, 0.3); }
    .swal2-cancel-btn { border-radius: 50px !important; padding: 10px 24px !important; background: #f8f9fa !important; color: #6c757d !important; border: 1px solid #dee2e6 !important; }
    .highlight-name { color: #dc3545; font-weight: 700; background-color: rgba(220, 53, 69, 0.08); padding: 2px 8px; border-radius: 6px; }
</style>
@endpush

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Tooltip Init
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })

    // DELETE CONFIRMATION (PREMIUM STYLE)
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const formId = this.getAttribute('data-id');
            const namaNasabah = this.getAttribute('data-nama');
            
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Data Nasabah?',
                html: `<div class="mb-2">Anda akan menghapus data:</div><div class="mb-4 fs-5"><span class="highlight-name">${namaNasabah}</span></div><div class="small text-muted">Data yang dihapus tidak dapat dikembalikan.</div>`,
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                backdrop: `rgba(22, 85, 129, 0.4)`,
                customClass: {
                    popup: 'custom-swal-popup animate__animated animate__zoomIn',
                    title: 'custom-swal-title',
                    htmlContainer: 'custom-swal-html',
                    confirmButton: 'swal2-confirm-btn',
                    cancelButton: 'swal2-cancel-btn me-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Sedang menghapus data.',
                        timerProgressBar: true,
                        didOpen: () => { Swal.showLoading(); },
                        backdrop: `rgba(255,255,255,0.8)`,
                        color: '#165581',
                        customClass: { popup: 'custom-swal-popup border-0 shadow-none' }
                    });
                    document.getElementById(`delete-form-${formId}`).submit();
                }
            });
        });
    });

    // TOAST NOTIFICATION (MATCHING DASHBOARD)
    @if(session('success'))
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true,
            didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
        });
        Toast.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}' });
    @endif
</script>
@endpush