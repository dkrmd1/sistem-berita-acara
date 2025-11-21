@extends('layouts.app')

@section('title', 'Isi Form Berita Acara')

@section('content')
<div class="container-fluid px-4">
    <!-- Header & Progress Tracker (SAMA SEPERTI SEBELUMNYA) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold text-company mb-1">
                <i class="bi bi-file-earmark-richtext-fill me-2"></i>Form Berita Acara
            </h2>
            <p class="text-muted mb-0">Lengkapi formulir pengecekan data nasabah di bawah ini.</p>
        </div>
        <a href="{{ route('berita-acara.create') }}" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <!-- Progress Tracker -->
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="position-relative my-2">
                <div class="progress" style="height: 4px; background-color: #e9ecef;">
                    <div class="progress-bar bg-gradient-company" role="progressbar" style="width: 50%;"></div>
                </div>
                <div class="position-absolute top-0 start-0 translate-middle btn btn-company rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 3rem; height:3rem;">
                    <i class="bi bi-check-lg fs-4"></i>
                </div>
                <div class="position-absolute top-0 start-50 translate-middle btn btn-company rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="width: 3rem; height:3rem; border: 4px solid #fff;">
                    <span class="fw-bold fs-5">2</span>
                </div>
                <div class="position-absolute top-0 start-100 translate-middle btn btn-light border rounded-circle d-flex align-items-center justify-content-center" style="width: 3rem; height:3rem;">
                    <span class="fw-bold fs-5 text-muted">3</span>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3 px-1">
                <span class="small fw-bold text-muted">Pilih Nasabah</span>
                <span class="small fw-bold text-company">Isi Form & Approver</span>
                <span class="small fw-bold text-muted">Selesai</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- KOLOM KIRI: FORM -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-company text-white py-3 rounded-top-4">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i> Data & Pengecekan</h6>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <!-- Info Nasabah -->
                    <div class="alert bg-light border-0 border-start border-5 border-primary rounded-3 mb-5 shadow-sm">
                        <div class="row g-3">
                            <div class="col-12 border-bottom pb-2 mb-2">
                                <small class="text-uppercase text-muted fw-bold d-block mb-1">Nama Nasabah</small>
                                <div class="fs-4 fw-bold text-dark d-flex align-items-center">
                                    <i class="bi bi-person-circle text-primary me-2"></i> {{ $nasabah->nama }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-uppercase text-muted fw-bold d-block mb-1">No. KTP</small>
                                <div class="fs-5 font-monospace text-dark d-flex align-items-center">
                                    <i class="bi bi-card-heading text-primary me-2"></i> {{ $nasabah->getKtpFormatted() }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-uppercase text-muted fw-bold d-block mb-1">No. NPWP</small>
                                <div class="fs-5 font-monospace text-dark d-flex align-items-center">
                                    <i class="bi bi-card-list text-primary me-2"></i> {{ $nasabah->getNpwpFormatted() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('berita-acara.store') }}" method="POST" id="baForm">
                        @csrf
                        <input type="hidden" name="nasabah_id" value="{{ $nasabah->id }}">

                        <!-- Detail Dokumen -->
                        <div class="mb-5">
                            <h6 class="text-company fw-bold mb-3 pb-2 border-bottom">
                                <span class="bg-white pe-3"><i class="bi bi-calendar-week me-2"></i> Detail Dokumen</span>
                            </h6>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">TANGGAL BERITA ACARA <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white text-primary border-end-0"><i class="bi bi-calendar-event"></i></span>
                                        <input type="date" name="tanggal_ba" class="form-control border-start-0 ps-0" value="{{ old('tanggal_ba', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">APPROVER (PEJABAT) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white text-primary border-end-0"><i class="bi bi-person-check-fill"></i></span>
                                        <select name="approver_id" class="form-select border-start-0 ps-0" required>
                                            <option value="">-- Pilih Pejabat --</option>
                                            @foreach($approvers as $approver)
                                            <option value="{{ $approver->id }}" {{ old('approver_id') == $approver->id ? 'selected' : '' }}>
                                                {{ $approver->name }} - {{ $approver->jabatan }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hasil Pengecekan -->
                        <div class="mb-4">
                            <h6 class="text-company fw-bold mb-4 pb-2 border-bottom">
                                <span class="bg-white pe-3"><i class="bi bi-shield-check me-2"></i> Hasil Pengecekan</span>
                            </h6>

                            <!-- Watchlist -->
                            <div class="mb-4 p-3 rounded-3 bg-light border border-dashed">
                                <label class="form-label fw-bold text-dark mb-3 d-block">
                                    1. Pengecekan Database Watch List (APU PPT) <span class="text-danger">*</span>
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="watchlist_match" id="wl_no" value="0" checked required>
                                        <label class="btn btn-outline-success w-100 p-3 text-start rounded-3 d-flex align-items-center gap-3" for="wl_no">
                                            <div class="icon-wrapper bg-success bg-opacity-10 text-success rounded-circle p-2">
                                                <i class="bi bi-check-lg fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">AMAN / BERSIH</div>
                                                <small style="font-size: 0.8rem;">Tidak terdapat kecocokan data</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="watchlist_match" id="wl_yes" value="1">
                                        <label class="btn btn-outline-danger w-100 p-3 text-start rounded-3 d-flex align-items-center gap-3" for="wl_yes">
                                            <div class="icon-wrapper bg-danger bg-opacity-10 text-danger rounded-circle p-2">
                                                <i class="bi bi-exclamation-lg fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">TERINDIKASI (HIT)</div>
                                                <small style="font-size: 0.8rem;">Terdapat kecocokan data</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing -->
                            <div class="mb-4 p-3 rounded-3 bg-light border border-dashed">
                                <label class="form-label fw-bold text-dark mb-3 d-block">
                                    2. Pengecekan Database Existing (Duplikasi) <span class="text-danger">*</span>
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="existing_match" id="ex_no" value="0" checked required>
                                        <label class="btn btn-outline-success w-100 p-3 text-start rounded-3 d-flex align-items-center gap-3" for="ex_no">
                                            <div class="icon-wrapper bg-success bg-opacity-10 text-success rounded-circle p-2">
                                                <i class="bi bi-person-check fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">DATA BARU</div>
                                                <small style="font-size: 0.8rem;">Belum ada di database</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="existing_match" id="ex_yes" value="1">
                                        <label class="btn btn-outline-danger w-100 p-3 text-start rounded-3 d-flex align-items-center gap-3" for="ex_yes">
                                            <div class="icon-wrapper bg-danger bg-opacity-10 text-danger rounded-circle p-2">
                                                <i class="bi bi-people-fill fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">DUPLIKASI</div>
                                                <small style="font-size: 0.8rem;">Nasabah sudah terdaftar</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">CATATAN TAMBAHAN (OPSIONAL)</label>
                            <textarea name="notes" class="form-control bg-white border-secondary-subtle" rows="3" placeholder="Contoh: Nasabah memiliki nama mirip tapi tanggal lahir berbeda..."></textarea>
                        </div>

                        <hr class="my-4 opacity-25">

                        <!-- Action Button -->
                        <div class="d-flex justify-content-end">
                            <button type="button" id="btnSubmit" class="btn btn-company btn-lg px-5 rounded-pill shadow-sm">
                                <i class="bi bi-save2-fill me-2"></i> Simpan & Buat BA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: SIDEBAR -->
        <div class="col-12 col-lg-4">
            <div class="sticky-top" style="top: 90px; z-index: 1;">
                <div class="card shadow-sm border-0 rounded-4 mb-4 bg-white">
                    <div class="card-header bg-info-subtle text-info-emphasis py-3 rounded-top-4 border-0">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle-fill me-2"></i> Panduan Approver</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item border-0 ps-0 d-flex"><i class="bi bi-1-circle-fill text-primary me-2 fs-5"></i> <div><span class="fw-bold text-dark">Group Head Sales</span><p class="mb-0 text-muted">Prioritas Utama</p></div></li>
                            <li class="list-group-item border-0 ps-0 d-flex"><i class="bi bi-2-circle-fill text-secondary me-2 fs-5"></i> <div><span class="fw-bold text-dark">Direktur Utama</span><p class="mb-0 text-muted">Backup 1</p></div></li>
                            <li class="list-group-item border-0 ps-0 d-flex"><i class="bi bi-3-circle-fill text-secondary me-2 fs-5"></i> <div><span class="fw-bold text-dark">Direktur</span><p class="mb-0 text-muted">Backup 2</p></div></li>
                        </ul>
                    </div>
                </div>
                <div class="card shadow-sm border-0 rounded-4 bg-light">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock-fill me-2 text-company"></i> Keamanan Data</h6>
                        <p class="small text-muted mb-0" style="text-align: justify;">Data yang sudah disimpan tidak dapat diubah. Pastikan pengecekan dilakukan dengan teliti.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    :root { --calm-water-blue: #165581; --atmospheric-blue: #29AAE2; --sincere-yellow: #EFCA18; }
    .text-company { color: #165581; }
    .bg-gradient-company { background: linear-gradient(135deg, #165581 0%, #2d7a9e 100%); }
    .btn-company { background-color: #165581; border-color: #165581; color: white; transition: background-color 0.2s; }
    .btn-company:hover { background-color: #114466; border-color: #114466; }
    .form-control, .form-select { border-color: #dee2e6; padding: 12px 15px; }
    .form-control:focus, .form-select:focus { border-color: #29AAE2; box-shadow: none; background-color: #f8fcff; }
    .btn-check:checked + .btn-outline-success { background-color: #d1e7dd !important; color: #0f5132 !important; border-color: #198754 !important; }
    .btn-check:checked + .btn-outline-danger { background-color: #f8d7da !important; color: #842029 !important; border-color: #dc3545 !important; }
    .rounded-4 { border-radius: 1rem; }
    .border-dashed { border-style: dashed !important; border-color: #cbd5e1 !important; }
    
    /* === UPDATE PENTING: CSS LOADING BARU SESUAI PERMINTAAN === */
    .custom-swal-popup { 
        border-radius: 24px !important; 
        padding-top: 0 !important; 
        overflow: hidden; 
        font-family: 'Segoe UI', Tahoma, sans-serif; 
    }
    /* Strip Warna-warni di atas */
    .custom-swal-popup::before { 
        content: ''; 
        display: block; 
        height: 8px; 
        width: 100%; 
        background: linear-gradient(90deg, var(--calm-water-blue) 0%, var(--calm-water-blue) 33%, var(--atmospheric-blue) 33%, var(--atmospheric-blue) 66%, var(--sincere-yellow) 66%, var(--sincere-yellow) 100%); 
    }
    
    .swal2-confirm-btn { background: linear-gradient(135deg, #165581, #12466b) !important; border-radius: 50px !important; padding: 12px 30px !important; }
    .swal2-cancel-btn { border-radius: 50px !important; padding: 12px 24px !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btnSubmit').addEventListener('click', function() {
        const form = document.getElementById('baForm');
        if (!form.checkValidity()) { form.reportValidity(); return; }

        Swal.fire({
            title: 'Konfirmasi Simpan',
            html: "Pastikan hasil pengecekan <b>Watchlist</b> & <b>Existing</b> sudah benar.<br>Data tidak bisa diubah setelah disimpan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan Data',
            cancelButtonText: 'Cek Lagi',
            reverseButtons: true,
            backdrop: `rgba(22, 85, 129, 0.4)`,
            customClass: {
                popup: 'custom-swal-popup', 
                confirmButton: 'swal2-confirm-btn',
                cancelButton: 'swal2-cancel-btn me-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // === UPDATE: TAMPILAN LOADING JAM PASIR ===
                Swal.fire({
                    title: 'Sedang Menyimpan...',
                    html: '<div class="mb-3"><i class="bi bi-hourglass-split fs-1 text-primary"></i></div>Sistem sedang memproses dokumen Berita Acara...',
                    timerProgressBar: true,
                    didOpen: () => { Swal.showLoading(); },
                    allowOutsideClick: false,
                    backdrop: `rgba(255,255,255,0.9)`, // Putih transparan
                    color: '#165581',
                    customClass: { popup: 'custom-swal-popup border-0 shadow-lg' }, // Pakai class yang ada strip warna
                    showConfirmButton: false
                });
                
                form.submit();
            }
        });
    });
</script>
@endpush