@extends('layouts.app')

@section('title', 'Detail Nasabah')

@section('content')
<div class="container-fluid px-4">
    
    <!-- 1. PAGE HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-lg bg-company-subtle text-company rounded-circle me-3">
                            <i class="bi bi-person-circle-fill display-6"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-company mb-1">Detail Nasabah</h3>
                            <p class="text-muted mb-0">Informasi lengkap dan riwayat Berita Acara</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('nasabah.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                        @if(Auth::user()->isCS() && !$nasabah->has_berita_acara)
                        <a href="{{ route('berita-acara.create.form', $nasabah->id) }}" class="btn btn-company rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-plus-lg me-2"></i> Buat Berita Acara
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- KOLOM KIRI: Data Nasabah -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-company"><i class="bi bi-person-badge-fill me-2"></i> Informasi Pribadi</h6>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless align-middle detail-table mb-0">
                        <tbody>
                            <tr>
                                <td width="35%" class="text-muted small fw-bold text-uppercase">Nama Lengkap</td>
                                <td width="5%">:</td>
                                <td class="fs-5 fw-bold text-dark">{{ $nasabah->nama }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small fw-bold text-uppercase">Nomor KTP</td>
                                <td>:</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="font-monospace bg-light px-2 py-1 rounded border me-2">{{ $nasabah->getKtpFormatted() }}</span>
                                        <button class="btn btn-sm btn-light text-primary rounded-circle border" onclick="copyToClipboard('{{ $nasabah->ktp }}', 'Nomor KTP')" data-bs-toggle="tooltip" title="Salin KTP">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted small fw-bold text-uppercase">Nomor NPWP</td>
                                <td>:</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($nasabah->npwp)
                                            <span class="font-monospace bg-light px-2 py-1 rounded border me-2">{{ $nasabah->getNpwpFormatted() }}</span>
                                            <button class="btn btn-sm btn-light text-primary rounded-circle border" onclick="copyToClipboard('{{ $nasabah->npwp }}', 'Nomor NPWP')" data-bs-toggle="tooltip" title="Salin NPWP">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        @else
                                            <span class="text-muted fst-italic">- Tidak Ada -</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted small fw-bold text-uppercase">Tanggal Lahir</td>
                                <td>:</td>
                                <td>
                                    <i class="bi bi-calendar-event me-2 text-secondary"></i>
                                    {{ $nasabah->getTanggalLahirFormatted() }}
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2 border">{{ $nasabah->getUmur() }} Tahun</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted small fw-bold text-uppercase">Negara</td>
                                <td>:</td>
                                <td>
                                    <i class="bi bi-geo-alt-fill me-2 text-danger"></i> {{ $nasabah->negara }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted small fw-bold text-uppercase">Terdaftar Sejak</td>
                                <td>:</td>
                                <td>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i> {{ $nasabah->created_at->isoFormat('D MMMM Y, HH:mm') }} WIB</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Status & Aksi -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow-sm border-0 rounded-4 mb-4 text-center overflow-hidden position-relative">
                <div class="card-body p-5 {{ $nasabah->has_berita_acara ? 'bg-success-subtle' : 'bg-warning-subtle' }}">
                    <div class="mb-3">
                        <i class="bi {{ $nasabah->has_berita_acara ? 'bi-check-circle-fill text-success' : 'bi-exclamation-circle-fill text-warning' }}" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold {{ $nasabah->has_berita_acara ? 'text-success' : 'text-warning-emphasis' }}">
                        {{ $nasabah->has_berita_acara ? 'Sudah Ada BA' : 'Belum Ada BA' }}
                    </h5>
                    <p class="small text-muted mb-4 px-3">
                        {{ $nasabah->has_berita_acara ? 'Nasabah ini telah memiliki dokumen Berita Acara yang valid.' : 'Nasabah ini belum diproses. Silakan buat Berita Acara baru.' }}
                    </p>

                    @if(!$nasabah->has_berita_acara && Auth::user()->isCS())
                    <a href="{{ route('berita-acara.create.form', $nasabah->id) }}" class="btn btn-company w-100 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Buat BA Sekarang
                    </a>
                    @endif
                </div>
            </div>

            <!-- Quick Action: Delete (Hanya jika belum ada BA) -->
            @if(Auth::user()->isCS() && !$nasabah->has_berita_acara)
            <div class="card shadow-sm border-0 rounded-4 bg-danger-subtle">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Zona Bahaya</h6>
                    <p class="small text-danger-emphasis mb-3">Menghapus data nasabah tidak dapat dibatalkan.</p>
                    <form action="{{ route('nasabah.destroy', $nasabah->id) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger w-100 fw-bold bg-white" id="btnDelete">
                            <i class="bi bi-trash-fill me-2"></i> Hapus Data Nasabah
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- RIWAYAT BERITA ACARA -->
    @if($nasabah->beritaAcaras->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-company text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i> Riwayat Berita Acara</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 text-secondary small text-uppercase py-3">No</th>
                                    <th class="text-secondary small text-uppercase py-3">Nomor Dokumen</th>
                                    <th class="text-secondary small text-uppercase py-3">Tanggal Pembuatan</th>
                                    <th class="text-secondary small text-uppercase py-3 text-center">Status</th>
                                    <th class="text-secondary small text-uppercase py-3">Dibuat Oleh</th>
                                    <th class="text-secondary small text-uppercase py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nasabah->beritaAcaras as $index => $ba)
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-bold text-company">{{ $ba->nomor_ba }}</td>
                                    <td>
                                        <i class="bi bi-calendar-check me-2 text-secondary"></i>
                                        {{ $ba->getTanggalBaFormatted() }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-{{ $ba->getStatusBadgeColor() }} bg-opacity-10 text-{{ $ba->getStatusBadgeColor() }} border border-{{ $ba->getStatusBadgeColor() }} px-3">
                                            {{ $ba->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:24px;height:24px;font-size:10px;">
                                                {{ substr($ba->creator->name, 0, 1) }}
                                            </div>
                                            <span class="small">{{ $ba->creator->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm rounded-pill" role="group">
                                            <a href="{{ route('berita-acara.show', $ba->id) }}" class="btn btn-sm btn-light text-primary" data-bs-toggle="tooltip" title="Lihat Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            @if($ba->pdf_path)
                                            <a href="{{ route('berita-acara.download', $ba->id) }}" class="btn btn-sm btn-light text-success" data-bs-toggle="tooltip" title="Download PDF">
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* === BRAND VARIABLES === */
    :root { --calm-water-blue: #165581; --atmospheric-blue: #29AAE2; --sincere-yellow: #EFCA18; }
    
    /* === UTILS === */
    .font-monospace { font-family: 'Consolas', 'Monaco', monospace; letter-spacing: 0.5px; }
    .text-company { color: var(--calm-water-blue); }
    .bg-company-subtle { background-color: rgba(22, 85, 129, 0.1); color: var(--calm-water-blue); }
    .bg-gradient-company { background: linear-gradient(135deg, var(--calm-water-blue) 0%, #2d7a9e 100%); }
    .rounded-4 { border-radius: 1rem !important; }
    
    /* === BUTTONS === */
    .btn-company { background-color: var(--calm-water-blue); border-color: var(--calm-water-blue); color: white; transition: all 0.3s ease; }
    .btn-company:hover { background-color: #114466; border-color: #114466; color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(22, 85, 129, 0.3); }
    
    /* === TABLE === */
    .detail-table td { padding: 1rem 0.5rem; border-bottom: 1px solid #f8f9fa; }
    .detail-table tr:last-child td { border-bottom: none; }
    
    /* === ICON BOX === */
    .icon-box-lg { width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; }

    /* === SWEETALERT CUSTOM === */
    .custom-swal-popup { border-radius: 24px !important; padding-top: 0 !important; font-family: 'Segoe UI', sans-serif; }
    .swal2-confirm-btn { background: linear-gradient(135deg, var(--calm-water-blue) 0%, #12466b 100%) !important; border-radius: 50px !important; padding: 10px 30px !important; }
    .swal2-cancel-btn { border-radius: 50px !important; padding: 10px 24px !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })

    // COPY TO CLIPBOARD (SWEETALERT TOAST)
    function copyToClipboard(text, label) {
        navigator.clipboard.writeText(text).then(function() {
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true,
                didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
            });
            Toast.fire({ icon: 'success', title: 'Disalin!', text: `${label} berhasil disalin ke clipboard` });
        }, function() {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat menyalin teks', timer: 1500, showConfirmButton: false });
        });
    }

    // DELETE CONFIRMATION
    const deleteBtn = document.getElementById('btnDelete');
    if(deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Hapus Data Nasabah?',
                text: "Tindakan ini tidak dapat dibatalkan. Data nasabah akan hilang permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'custom-swal-popup', confirmButton: 'swal2-confirm-btn', cancelButton: 'swal2-cancel-btn me-2' },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...', html: 'Mohon tunggu sebentar.', timerProgressBar: true,
                        didOpen: () => { Swal.showLoading(); },
                        allowOutsideClick: false
                    });
                    document.getElementById('deleteForm').submit();
                }
            });
        });
    }
</script>
@endpush