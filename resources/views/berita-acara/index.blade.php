@extends('layouts.app')

@section('title', 'Daftar Berita Acara')

@section('content')
<div class="container-fluid px-4">
    
    <!-- 1. PAGE HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-lg bg-company-subtle text-company rounded-circle me-3">
                            <i class="bi bi-file-earmark-text-fill display-6"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-company mb-1">Daftar Berita Acara</h3>
                            <p class="text-muted mb-0">Monitor status pengajuan dan persetujuan dokumen nasabah</p>
                        </div>
                    </div>
                    
                    @if(Auth::user()->isCS())
                    <div class="d-flex gap-2">
                        <a href="{{ route('berita-acara.create') }}" class="btn btn-company rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-plus-lg me-2"></i> Buat BA Baru
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STATISTIK MINI -->
    <!-- Note: Kartu Rejected dihapus, layout tetap menggunakan col-md-3 -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-sincere-yellow card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">Pending</p>
                            <h2 class="mb-0 fw-bold text-white">{{ \App\Models\BeritaAcara::pending()->count() }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-hourglass-split fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-calm-water card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">Approved</p>
                            <h2 class="mb-0 fw-bold text-white">{{ \App\Models\BeritaAcara::approved()->count() }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-check-circle-fill fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Kartu Rejected dihapus dari sini -->
        <div class="col-md-3">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-atmospheric card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">Total Dokumen</p>
                            <h2 class="mb-0 fw-bold text-white">{{ \App\Models\BeritaAcara::count() }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-files fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. FILTER & SEARCH -->
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('berita-acara.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-2 bg-light" 
                                   placeholder="Nomor BA atau Nama Nasabah..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <!-- Filter Status -->
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Status Dokumen</label>
                        <select name="status" class="form-select bg-light cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                            <!-- Opsi Rejected dihapus dari sini -->
                        </select>
                    </div>

                    <!-- Filter My BA -->
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Filter Data</label>
                        <select name="filter" class="form-select bg-light cursor-pointer">
                            <option value="">Semua Data</option>
                            <option value="my" {{ request('filter') == 'my' ? 'selected' : '' }}>
                                {{ Auth::user()->isCS() ? 'BA Buatan Saya' : 'BA Perlu Saya Approve' }}
                            </option>
                        </select>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-company flex-grow-1 fw-bold"><i class="bi bi-funnel-fill me-2"></i> Filter</button>
                            <a href="{{ route('berita-acara.index') }}" class="btn btn-outline-secondary px-3" data-bs-toggle="tooltip" title="Reset Filter"><i class="bi bi-arrow-clockwise"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. TABEL BERITA ACARA -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-company"><i class="bi bi-table me-2"></i> Daftar Dokumen</h6>
            <small class="text-muted">Menampilkan {{ $beritaAcaras->count() }} data</small>
        </div>
        <div class="card-body p-0">
            @if($beritaAcaras->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%" class="text-center text-secondary small text-uppercase py-3">No</th>
                            <th class="text-secondary small text-uppercase py-3">Nomor Dokumen</th>
                            <th class="text-secondary small text-uppercase py-3">Nasabah</th>
                            <th class="text-secondary small text-uppercase py-3">Tanggal</th>
                            <th class="text-secondary small text-uppercase py-3">Approver</th>
                            <th class="text-center text-secondary small text-uppercase py-3">Status</th>
                            <th width="18%" class="text-center text-secondary small text-uppercase py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beritaAcaras as $index => $ba)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $beritaAcaras->firstItem() + $index }}</td>
                            <td>
                                <span class="font-monospace badge bg-light text-dark border fw-bold text-start">
                                    {{ $ba->nomor_ba }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-company-light text-company me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        {{ strtoupper(substr($ba->nasabah->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $ba->nasabah->nama }}</div>
                                        <small class="text-muted font-monospace" style="font-size: 0.75rem;">{{ $ba->nasabah->ktp }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar3 text-secondary"></i>
                                    <span class="small">{{ $ba->getTanggalBaFormatted() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-light text-secondary rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                        <i class="bi bi-person-check-fill" style="font-size: 0.7rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold small">{{ $ba->approver->name }}</div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">{{ $ba->approver->jabatan }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($ba->status == 'pending')
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3">
                                        <i class="bi bi-hourglass-split me-1"></i> Pending
                                    </span>
                                @elseif($ba->status == 'approved')
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3">
                                        <i class="bi bi-check-circle-fill me-1"></i> Approved
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-pill" role="group">
                                    <!-- Detail -->
                                    <a href="{{ route('berita-acara.show', $ba->id) }}" 
                                       class="btn btn-sm btn-light text-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    <!-- PDF Actions -->
                                    @if($ba->pdf_path)
                                        <a href="{{ route('berita-acara.view-pdf', $ba->id) }}" 
                                           target="_blank"
                                           class="btn btn-sm btn-light text-secondary" 
                                           data-bs-toggle="tooltip" 
                                           title="Lihat PDF">
                                            <i class="bi bi-file-pdf-fill"></i>
                                        </a>
                                        
                                        <a href="{{ route('berita-acara.download', $ba->id) }}" 
                                           class="btn btn-sm btn-light text-secondary" 
                                           data-bs-toggle="tooltip" 
                                           title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @endif

                                    <!-- APPROVE BUTTON (Hanya untuk Approver) -->
                                    @if(Auth::user()->isApprover() && $ba->canBeApprovedBy(Auth::id()))
                                        <button type="button" 
                                                class="btn btn-sm btn-success text-white btn-approve"
                                                data-id="{{ $ba->id }}"
                                                data-nomor="{{ $ba->nomor_ba }}"
                                                data-bs-toggle="tooltip"
                                                title="Approve">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        
                                        <form id="approve-form-{{ $ba->id }}" action="{{ route('berita-acara.approve', $ba->id) }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                <div><small class="text-muted fw-semibold">Menampilkan {{ $beritaAcaras->firstItem() }} - {{ $beritaAcaras->lastItem() }} dari {{ $beritaAcaras->total() }} data</small></div>
                <div>{{ $beritaAcaras->links() }}</div>
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-3 opacity-25"><i class="bi bi-file-earmark-x display-1 text-secondary"></i></div>
                <h5 class="text-muted fw-semibold">Belum Ada Berita Acara</h5>
                <p class="text-muted small mb-4">Data tidak ditemukan atau belum ada pengajuan.</p>
                @if(Auth::user()->isCS())
                <a href="{{ route('berita-acara.create') }}" class="btn btn-company px-4 rounded-pill shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Buat BA Baru
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('styles')
<!-- SweetAlert & Animate -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    /* === BRAND COLORS & GRADIENTS === */
    :root {
        --calm-water-blue: #165581; --atmospheric-blue: #29AAE2; --sincere-yellow: #EFCA18; --danger-red: #dc3545;
    }
    .text-company { color: var(--calm-water-blue); }
    .bg-company-subtle { background-color: rgba(22, 85, 129, 0.1); color: var(--calm-water-blue); }
    
    /* Gradients */
    .gradient-calm-water { background: linear-gradient(135deg, #165581 0%, #2d7a9e 100%); color: white; }
    .gradient-atmospheric { background: linear-gradient(135deg, #29AAE2 0%, #5cb8e6 100%); color: white; }
    .gradient-sincere-yellow { background: linear-gradient(135deg, #EFCA18 0%, #f7c748 100%); color: white; }
    /* .gradient-rejected dihapus */
    
    /* === COMPONENTS === */
    .rounded-4 { border-radius: 1rem !important; }
    .font-monospace { font-family: 'Consolas', 'Monaco', monospace; }
    .icon-box-lg { width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; }
    .cursor-pointer { cursor: pointer; }
    
    /* Card Hover Effect */
    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }

    /* Buttons */
    .btn-company { background-color: var(--calm-water-blue); border-color: var(--calm-water-blue); color: white; transition: all 0.3s ease; }
    .btn-company:hover { background-color: #114466; border-color: #114466; color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(22, 85, 129, 0.3); }

    /* === PREMIUM POPUP STYLES === */
    .custom-swal-popup {
        border-radius: 24px !important; padding-top: 0 !important; overflow: hidden;
        box-shadow: 0 20px 50px rgba(22, 85, 129, 0.2) !important; font-family: 'Segoe UI', Tahoma, sans-serif;
    }
    .custom-swal-popup::before {
        content: ''; display: block; height: 8px; width: 100%;
        background: linear-gradient(90deg, var(--calm-water-blue) 0%, var(--calm-water-blue) 33%, var(--atmospheric-blue) 33%, var(--atmospheric-blue) 66%, var(--sincere-yellow) 66%, var(--sincere-yellow) 100%);
    }
    .custom-swal-title { color: var(--calm-water-blue) !important; font-weight: 800 !important; font-size: 1.6rem !important; margin-top: 1.5rem !important; }
    
    .swal2-confirm-btn {
        background: linear-gradient(135deg, var(--calm-water-blue) 0%, #12466b 100%) !important;
        border-radius: 50px !important; font-weight: 600 !important; padding: 12px 32px !important;
        box-shadow: 0 4px 15px rgba(22, 85, 129, 0.3) !important; transition: transform 0.2s;
    }
    .swal2-confirm-btn:hover { transform: translateY(-2px); }

    .swal2-cancel-btn {
        background: #f8f9fa !important; color: #6c757d !important; border: 1px solid #dee2e6 !important;
        border-radius: 50px !important; font-weight: 600 !important; padding: 12px 24px !important;
    }
    
    .highlight-ba { color: var(--atmospheric-blue); font-weight: 700; background-color: rgba(41, 170, 226, 0.1); padding: 2px 8px; border-radius: 6px; }
    
    /* Table Header */
    .table thead.bg-light th { background-color: #f8f9fa; font-weight: 600; letter-spacing: 0.5px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Init Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })

    // === LOGIC APPROVE (POPUP KEREN) ===
    document.querySelectorAll('.btn-approve').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nomor = this.getAttribute('data-nomor');
            
            Swal.fire({
                icon: 'question',
                title: 'Setujui Dokumen?',
                html: `<div class="mb-2">Anda akan menyetujui BA Nomor:</div><div class="fs-5 mb-3"><span class="highlight-ba">${nomor}</span></div><div class="small text-muted">Pastikan dokumen sudah diperiksa dengan benar.</div>`,
                showCancelButton: true,
                confirmButtonText: 'Ya, Approve',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                backdrop: `rgba(22, 85, 129, 0.4)`,
                customClass: {
                    popup: 'custom-swal-popup animate__animated animate__zoomIn',
                    title: 'custom-swal-title',
                    confirmButton: 'swal2-confirm-btn',
                    cancelButton: 'swal2-cancel-btn me-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    document.getElementById(`approve-form-${id}`).submit();
                }
            });
        });
    });

    // Loading Animation (Jam Pasir)
    function showLoading() {
        Swal.fire({
            title: 'Memproses...',
            html: '<div class="mb-3"><i class="bi bi-hourglass-split fs-1 text-primary"></i></div>Mohon tunggu sebentar.',
            timerProgressBar: true,
            didOpen: () => { Swal.showLoading(); },
            backdrop: `rgba(255,255,255,0.9)`,
            color: '#165581',
            customClass: { popup: 'custom-swal-popup border-0 shadow-lg' },
            allowOutsideClick: false,
            showConfirmButton: false
        });
    }

    // Toast Notification (Konsisten)
    @if(session('success'))
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true,
            didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
        });
        Toast.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}' });
    @endif
</script>
@endpush