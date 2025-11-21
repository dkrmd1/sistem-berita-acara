@extends('layouts.app')

@section('title', 'Detail Berita Acara')

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
                            <h3 class="fw-bold text-company mb-1">Detail Berita Acara</h3>
                            <p class="text-muted mb-0 font-monospace">{{ $beritaAcara->nomor_ba }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 flex-wrap justify-content-center">
                        <a href="{{ route('berita-acara.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                        
                        @if($beritaAcara->pdf_path)
                        <div class="btn-group shadow-sm rounded-pill" role="group">
                            <a href="{{ route('berita-acara.view-pdf', $beritaAcara->id) }}" 
                               target="_blank" class="btn btn-info text-white fw-bold px-3" title="Lihat PDF">
                                <i class="bi bi-eye-fill me-2"></i> Lihat
                            </a>
                            <button type="button" class="btn btn-warning text-dark fw-bold px-3" 
                                    onclick="printPDF({{ $beritaAcara->id }})" title="Print PDF">
                                <i class="bi bi-printer-fill me-2"></i> Print
                            </button>
                            <a href="{{ route('berita-acara.download', $beritaAcara->id) }}" 
                               class="btn btn-success fw-bold px-3" title="Download PDF">
                                <i class="bi bi-download me-2"></i> Download
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STATUS BADGE -->
    <div class="card mb-4 shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body text-center py-5 {{ $beritaAcara->isPending() ? 'bg-warning-subtle' : ($beritaAcara->isApproved() ? 'bg-success-subtle' : 'bg-danger-subtle') }}">
            <h6 class="text-muted mb-3 text-uppercase fw-bold small ls-1">Status Dokumen Saat Ini</h6>
            
            @if($beritaAcara->isPending())
                <div class="d-inline-flex align-items-center justify-content-center bg-warning text-dark px-5 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-hourglass-split fs-4 me-2"></i>
                    <span class="fs-4 fw-bold">Menunggu Approval</span>
                </div>
                <p class="mt-3 mb-0 text-muted small">Menunggu persetujuan dari <strong>{{ $beritaAcara->approver->jabatan }}</strong></p>
            
            @elseif($beritaAcara->isApproved())
                <div class="d-inline-flex align-items-center justify-content-center bg-success text-white px-5 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                    <span class="fs-4 fw-bold">Telah Disetujui</span>
                </div>
                <p class="mt-3 mb-0 text-success-emphasis small">
                    Disetujui pada {{ $beritaAcara->approved_at->format('d F Y, H:i') }} WIB
                </p>
            
            @else
                <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white px-5 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-x-circle-fill fs-4 me-2"></i>
                    <span class="fs-4 fw-bold">Ditolak (Rejected)</span>
                </div>
                <p class="mt-3 mb-0 text-danger-emphasis small">Dokumen ini telah ditolak oleh Approver.</p>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- KOLOM KIRI: INFORMASI -->
        <div class="col-lg-8">
            
            <!-- DATA NASABAH -->
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-header bg-gradient-company text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person-badge-fill me-2"></i> Data Nasabah</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase mb-1">Nama Lengkap</label>
                            <div class="fs-5 fw-bold text-company border-bottom pb-2">{{ $beritaAcara->nasabah->nama }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase mb-1">Negara Asal</label>
                            <div class="border-bottom pb-2">
                                <span class="badge badge-atmospheric">{{ $beritaAcara->nasabah->negara }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase mb-1">Nomor KTP</label>
                            <div class="font-monospace fs-6 bg-light p-2 rounded border">{{ $beritaAcara->nasabah->getKtpFormatted() }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase mb-1">Nomor NPWP</label>
                            <div class="font-monospace fs-6 bg-light p-2 rounded border">
                                {{ $beritaAcara->nasabah->npwp ? $beritaAcara->nasabah->getNpwpFormatted() : '-' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase mb-1">Tanggal Lahir</label>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-event me-2 text-secondary"></i>
                                {{ $beritaAcara->nasabah->getTanggalLahirFormatted() }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase mb-1">Usia Saat Ini</label>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-vcard me-2 text-secondary"></i>
                                {{ $beritaAcara->nasabah->getUmur() }} Tahun
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HASIL PENGECEKAN -->
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-header py-3 d-flex align-items-center text-dark" style="background-color: #fff3cd;">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-clipboard-check-fill me-2 text-warning"></i> Hasil Pengecekan</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Watchlist -->
                        <div class="col-md-6">
                            <div class="p-4 rounded-4 h-100 border {{ $beritaAcara->watchlist_match ? 'border-danger bg-danger-subtle' : 'border-success bg-success-subtle' }} position-relative overflow-hidden">
                                <h6 class="fw-bold mb-3 text-uppercase small opacity-75">Database Watch List</h6>
                                <div class="d-flex align-items-center position-relative z-1">
                                    <div class="rounded-circle p-3 me-3 {{ $beritaAcara->watchlist_match ? 'bg-danger text-white' : 'bg-success text-white' }}">
                                        <i class="bi {{ $beritaAcara->watchlist_match ? 'bi-exclamation-triangle-fill' : 'bi-check-lg' }} fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold {{ $beritaAcara->watchlist_match ? 'text-danger' : 'text-success' }}">
                                            {{ $beritaAcara->getWatchlistResult() }}
                                        </h5>
                                        <small class="text-muted fw-semibold">Status Pengecekan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Existing -->
                        <div class="col-md-6">
                            <div class="p-4 rounded-4 h-100 border {{ $beritaAcara->existing_match ? 'border-danger bg-danger-subtle' : 'border-success bg-success-subtle' }} position-relative overflow-hidden">
                                <h6 class="fw-bold mb-3 text-uppercase small opacity-75">Database Existing</h6>
                                <div class="d-flex align-items-center position-relative z-1">
                                    <div class="rounded-circle p-3 me-3 {{ $beritaAcara->existing_match ? 'bg-danger text-white' : 'bg-success text-white' }}">
                                        <i class="bi {{ $beritaAcara->existing_match ? 'bi-people-fill' : 'bi-person-check-fill' }} fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold {{ $beritaAcara->existing_match ? 'text-danger' : 'text-success' }}">
                                            {{ $beritaAcara->getExistingResult() }}
                                        </h5>
                                        <small class="text-muted fw-semibold">Status Pengecekan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($beritaAcara->notes)
                    <div class="mt-4">
                        <label class="small text-muted fw-bold text-uppercase mb-2">Catatan Tambahan</label>
                        <div class="alert alert-secondary border-0 bg-light rounded-3 d-flex align-items-start p-3">
                            <i class="bi bi-chat-quote-fill me-3 fs-4 text-secondary opacity-50"></i>
                            <div class="fst-italic text-dark">{{ $beritaAcara->notes }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- APPROVAL SECTION (Khusus Approver) -->
            @if(Auth::user()->isApprover() && $beritaAcara->canBeApprovedBy(Auth::id()))
            <div class="card shadow-lg border-success border-2 mb-4 rounded-4 bg-white">
                <div class="card-body p-5 text-center">
                    <div class="mb-3 text-success">
                        <i class="bi bi-shield-lock-fill" style="font-size: 3.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-company mb-2">Persetujuan Diperlukan</h4>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">
                        Anda login sebagai <strong>{{ Auth::user()->jabatan }}</strong>. Silakan tinjau dokumen ini. Jika data sudah sesuai, klik tombol di bawah untuk menyetujui.
                    </p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" 
                                class="btn btn-lg btn-success px-5 py-3 rounded-pill shadow-sm btn-action-approve hover-scale"
                                data-id="{{ $beritaAcara->id }}"
                                data-nomor="{{ $beritaAcara->nomor_ba }}">
                            <i class="bi bi-check-circle-fill me-2"></i> Setujui Dokumen
                        </button>
                        
                        <button type="button" 
                                class="btn btn-lg btn-outline-danger px-4 py-3 rounded-pill shadow-sm btn-action-reject hover-scale"
                                data-id="{{ $beritaAcara->id }}"
                                data-nomor="{{ $beritaAcara->nomor_ba }}">
                            <i class="bi bi-x-circle-fill me-2"></i> Tolak
                        </button>
                    </div>
                    
                    <!-- Forms Hidden -->
                    <form id="approve-form-{{ $beritaAcara->id }}" action="{{ route('berita-acara.approve', $beritaAcara->id) }}" method="POST" class="d-none">@csrf</form>
                    <form id="reject-form-{{ $beritaAcara->id }}" action="{{ route('berita-acara.reject', $beritaAcara->id) }}" method="POST" class="d-none">
                        @csrf
                        <input type="hidden" name="notes" id="reject-notes-{{ $beritaAcara->id }}">
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- KOLOM KANAN: TIMELINE -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 rounded-4 bg-white">
                <div class="card-header bg-light py-3 rounded-top-4 border-bottom">
                    <h6 class="mb-0 fw-bold text-company"><i class="bi bi-clock-history me-2"></i> Riwayat Dokumen</h6>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        <!-- Step 1: Dibuat -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success">
                                <i class="bi bi-pencil-fill text-white" style="font-size: 0.7rem;"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold mb-1 text-dark">Dibuat (Checker)</h6>
                                <div class="d-flex align-items-center mb-1 bg-light p-2 rounded">
                                    <div class="avatar-xs bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:24px;height:24px;font-size:10px;">
                                        {{ substr($beritaAcara->creator->name, 0, 1) }}
                                    </div>
                                    <span class="small fw-semibold text-dark">{{ $beritaAcara->creator->name }}</span>
                                </div>
                                <small class="text-muted d-block ms-1">
                                    <i class="bi bi-clock me-1"></i> {{ $beritaAcara->created_at->format('d M Y, H:i') }}
                                </small>
                            </div>
                        </div>

                        <!-- Step 2: Approval -->
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $beritaAcara->isPending() ? 'bg-warning' : ($beritaAcara->isApproved() ? 'bg-success' : 'bg-danger') }}">
                                <i class="bi {{ $beritaAcara->isPending() ? 'bi-hourglass-split' : ($beritaAcara->isApproved() ? 'bi-check-lg' : 'bi-x-lg') }} text-white" style="font-size: 0.8rem;"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold mb-1 text-dark">Approval (Signer)</h6>
                                <div class="d-flex align-items-center mb-2 bg-light p-2 rounded">
                                    <div class="avatar-xs bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:24px;height:24px;font-size:10px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <span class="small fw-semibold d-block text-dark">{{ $beritaAcara->approver->name }}</span>
                                        <span class="small text-muted" style="font-size: 0.7rem;">{{ $beritaAcara->approver->jabatan }}</span>
                                    </div>
                                </div>
                                
                                @if($beritaAcara->isApproved())
                                    <div class="alert alert-success py-2 px-3 mb-0 border-0 d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <small>Disetujui pada<br><strong>{{ $beritaAcara->approved_at->format('d M Y, H:i') }}</strong></small>
                                    </div>
                                @elseif($beritaAcara->isRejected())
                                    <div class="alert alert-danger py-2 px-3 mb-0 border-0">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="bi bi-x-circle-fill me-2"></i> <small class="fw-bold">Ditolak</small>
                                        </div>
                                        @if($beritaAcara->rejection_note)
                                        <small class="d-block fst-italic opacity-75">"{{ $beritaAcara->rejection_note }}"</small>
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-warning py-2 px-3 mb-0 border-0 d-flex align-items-center">
                                        <i class="bi bi-hourglass-split me-2"></i>
                                        <small class="fw-bold">Menunggu...</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    /* === BRAND COLORS === */
    :root { --calm-water-blue: #165581; --atmospheric-blue: #29AAE2; --sincere-yellow: #EFCA18; }
    .font-monospace { font-family: 'Courier New', Consolas, monospace; letter-spacing: 0.5px; }
    .text-company { color: var(--calm-water-blue); }
    .bg-gradient-company { background: linear-gradient(135deg, var(--calm-water-blue) 0%, #2d7a9e 100%); }
    .badge-atmospheric { background-color: rgba(41, 170, 226, 0.1); color: var(--atmospheric-blue); border: 1px solid var(--atmospheric-blue); padding: 0.3rem 0.6rem; }
    .rounded-4 { border-radius: 1rem !important; }
    .ls-1 { letter-spacing: 1px; }
    .icon-box-lg { width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; }
    .bg-company-subtle { background-color: rgba(22, 85, 129, 0.1); color: var(--calm-water-blue); }

    /* === TIMELINE === */
    .timeline { position: relative; padding-left: 1.5rem; border-left: 2px solid #e9ecef; margin-left: 0.5rem; }
    .timeline-item { position: relative; padding-bottom: 2rem; }
    .timeline-item:last-child { padding-bottom: 0; }
    .timeline-marker { position: absolute; left: -1.6rem; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #fff; box-shadow: 0 0 0 2px #e9ecef; z-index: 1; }
    .timeline-content { padding-left: 0.8rem; }

    /* === ANIMASI BUTTON === */
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.05); }

    /* === POPUP STYLE === */
    .custom-swal-popup { border-radius: 24px !important; padding-top: 0 !important; overflow: hidden; box-shadow: 0 20px 50px rgba(22, 85, 129, 0.2) !important; font-family: 'Segoe UI', sans-serif; }
    .custom-swal-popup::before { content: ''; display: block; height: 8px; width: 100%; background: linear-gradient(90deg, var(--calm-water-blue) 0%, var(--calm-water-blue) 33%, var(--atmospheric-blue) 33%, var(--atmospheric-blue) 66%, var(--sincere-yellow) 66%, var(--sincere-yellow) 100%); }
    
    .swal2-confirm-btn { background: linear-gradient(135deg, var(--calm-water-blue) 0%, #12466b 100%) !important; border-radius: 50px !important; padding: 12px 32px !important; color:white !important; border:none !important;}
    .swal2-cancel-btn { background: #f8f9fa !important; color: #6c757d !important; border: 1px solid #dee2e6 !important; border-radius: 50px !important; padding: 12px 24px !important; }
    .swal2-reject-btn { background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%) !important; border-radius: 50px !important; color: white !important; padding: 12px 32px !important; border:none !important;}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })

    function printPDF(baId) {
        const printWindow = window.open('{{ url("berita-acara") }}/' + baId + '/print', '_blank');
        if (printWindow) {
            printWindow.addEventListener('load', function() { setTimeout(function() { printWindow.print(); }, 500); });
        }
    }

    // === POPUP CEK TANDA TANGAN APPROVER ===
    @if(session('ttd_missing'))
    Swal.fire({
        title: 'Gagal Melakukan Approval!',
        html: `
            <div class="mb-3">
                <div class="position-relative d-inline-block">
                     <img src="https://cdn-icons-png.flaticon.com/512/5610/5610944.png" width="110" class="img-fluid mb-2 animate__animated animate__headShake animate__delay-1s">
                     <div class="position-absolute top-0 end-0 translate-middle bg-danger border border-light rounded-circle p-2">
                        <span class="visually-hidden">Alert</span>
                     </div>
                </div>
            </div>
            <p class="text-dark px-2 mb-1 fw-bold">
                Tanda Tangan Digital tidak ditemukan.
            </p>
            <p class="small text-muted mb-3">
                Anda harus mengupload tanda tangan terlebih dahulu untuk melakukan persetujuan (Approval) dokumen ini secara sah.
            </p>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-person-circle me-2"></i> Ke Profil & Upload',
        cancelButtonText: 'Tutup',
        reverseButtons: true,
        backdrop: `rgba(220, 53, 69, 0.1)`, // Merah tipis
        customClass: {
            popup: 'custom-swal-popup shadow-lg border-0 animate__animated animate__fadeInDown',
            confirmButton: 'swal2-confirm-btn',
            cancelButton: 'swal2-cancel-btn me-2'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('profile.index') }}";
        }
    });
    @endif

    // APPROVE LOGIC
    document.querySelectorAll('.btn-action-approve').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nomor = this.getAttribute('data-nomor');
            
            Swal.fire({
                icon: 'question',
                title: 'Setujui Dokumen?',
                html: `<div class="mb-2">Anda akan menyetujui BA Nomor:</div><div class="fs-4 mb-3 fw-bold text-primary">${nomor}</div>`,
                showCancelButton: true, confirmButtonText: 'Ya, Setujui', cancelButtonText: 'Batal', reverseButtons: true, backdrop: `rgba(22, 85, 129, 0.4)`,
                customClass: { popup: 'custom-swal-popup animate__animated animate__zoomIn', confirmButton: 'swal2-confirm-btn', cancelButton: 'swal2-cancel-btn me-2' }, buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    document.getElementById(`approve-form-${id}`).submit();
                }
            });
        });
    });

    // REJECT LOGIC
    document.querySelectorAll('.btn-action-reject').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nomor = this.getAttribute('data-nomor');
            
            Swal.fire({
                icon: 'warning',
                title: 'Tolak Dokumen?',
                html: `<div class="mb-2">Tolak BA Nomor: <strong>${nomor}</strong></div><textarea id="reject-note" class="form-control" placeholder="Alasan penolakan..."></textarea>`,
                showCancelButton: true, confirmButtonText: 'Tolak', cancelButtonText: 'Batal', reverseButtons: true, backdrop: `rgba(220, 53, 69, 0.2)`,
                customClass: { popup: 'custom-swal-popup animate__animated animate__shakeX', confirmButton: 'swal2-reject-btn', cancelButton: 'swal2-cancel-btn me-2' }, buttonsStyling: false,
                preConfirm: () => {
                    const note = document.getElementById('reject-note').value;
                    if (!note) Swal.showValidationMessage('Alasan wajib diisi');
                    return note;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    document.getElementById(`reject-notes-${id}`).value = result.value;
                    document.getElementById(`reject-form-${id}`).submit();
                }
            });
        });
    });

    function showLoading() {
        Swal.fire({ title: 'Memproses...', html: 'Mohon tunggu sebentar.', timerProgressBar: true, didOpen: () => Swal.showLoading(), backdrop: `rgba(255,255,255,0.9)`, customClass: { popup: 'custom-swal-popup border-0 shadow-lg' }, showConfirmButton: false });
    }

    @if(session('success'))
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); } }).fire({ icon: 'success', title: '{{ session("success") }}', iconColor: '#29AAE2' });
    @endif
</script>
@endpush