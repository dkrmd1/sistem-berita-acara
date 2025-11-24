@extends('layouts.app')

@section('title', 'Isi Form Berita Acara')

@section('content')
<div class="container-fluid px-4 py-4">
    
    <!-- 1. HEADER / BAGIAN ATAS (Disamakan dengan Referensi Gambar) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4 d-flex align-items-center">
            <!-- Icon Bulat (Kiri) -->
            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                 style="width: 64px; height: 64px;">
                <i class="bi bi-file-earmark-text-fill fs-2"></i>
            </div>
            
            <!-- Teks Judul & Deskripsi (Tengah) -->
            <div class="flex-grow-1">
                <h4 class="fw-bold text-dark mb-1" style="color: #1e3a8a;">Form Berita Acara</h4>
                <p class="text-muted mb-0">Lengkapi formulir pengajuan dan persetujuan dokumen nasabah.</p>
            </div>

            <!-- Tombol Kembali (Kanan) -->
            <a href="{{ route('berita-acara.create') }}" class="btn btn-light border rounded-pill px-4 fw-bold text-secondary hover-scale ms-3 d-none d-md-block">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Progress Tracker (Opsional - Bisa dihapus jika ingin lebih simple) -->
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="position-relative my-2">
                <div class="progress rounded-pill" style="height: 6px; background-color: #e9ecef;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 50%;"></div>
                </div>
                <!-- Step 1 -->
                <div class="position-absolute top-0 start-0 translate-middle">
                    <div class="step-circle bg-primary text-white shadow-sm"><i class="bi bi-check-lg"></i></div>
                    <div class="step-label text-muted fw-bold">Pilih Nasabah</div>
                </div>
                <!-- Step 2 -->
                <div class="position-absolute top-0 start-50 translate-middle">
                    <div class="step-circle bg-primary text-white shadow-lg ring-effect"><span class="fw-bold">2</span></div>
                    <div class="step-label text-primary fw-bold mt-2">Pengecekan</div>
                </div>
                <!-- Step 3 -->
                <div class="position-absolute top-0 start-100 translate-middle">
                    <div class="step-circle bg-white border text-muted"><span class="small">3</span></div>
                    <div class="step-label text-muted fw-bold">Selesai</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- KOLOM KIRI: FORM -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <!-- Data Nasabah Header -->
                <div class="bg-white p-4 border-bottom">
                    <div class="d-flex align-items-center p-3 rounded-4 bg-light-subtle border">
                        <div class="avatar-circle bg-primary text-white me-3 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            {{ strtoupper(substr($nasabah->nama, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold text-dark mb-1">{{ $nasabah->nama }}</h5>
                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                <span><i class="bi bi-card-heading me-1"></i> {{ $nasabah->getKtpFormatted() }}</span>
                                <span class="vr mx-1"></span>
                                <span><i class="bi bi-card-list me-1"></i> {{ $nasabah->getNpwpFormatted() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="{{ route('berita-acara.store') }}" method="POST" id="baForm">
                        @csrf
                        <input type="hidden" name="nasabah_id" value="{{ $nasabah->id }}">

                        <!-- SECTION 1: Detail Dokumen -->
                        <div class="mb-5">
                            <h6 class="text-uppercase fw-bold text-secondary small mb-3 ls-1">
                                <i class="bi bi-sliders me-1"></i> Konfigurasi Dokumen
                            </h6>

                            <!-- PANEL NOMOR BA -->
                            <div class="card border-0 bg-light rounded-4 mb-4 overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            <h6 class="fw-bold text-primary mb-1">Nomor Berita Acara</h6>
                                            <p class="small text-muted mb-0">Atur penomoran dokumen.</p>
                                        </div>
                                        @if(isset($isAutoEnabled) && $isAutoEnabled)
                                        <div class="form-check form-switch transform-scale-12">
                                            <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="toggleManualBA" name="use_manual_ba" value="1" {{ old('use_manual_ba') ? 'checked' : '' }}>
                                        </div>
                                        @endif
                                    </div>

                                    <hr class="text-muted opacity-25 my-3">

                                    @if(isset($isAutoEnabled) && $isAutoEnabled)
                                        <!-- Input Manual -->
                                        <div id="manualBAInputWrapper" style="display: none;" class="animate__animated animate__fadeIn">
                                            <div class="form-floating">
                                                <input type="text" name="manual_nomor_ba" id="manual_nomor_ba" 
                                                       class="form-control fw-bold font-monospace text-uppercase text-primary border-primary" 
                                                       placeholder="Contoh: BA/2025/..."
                                                       value="{{ old('manual_nomor_ba') }}">
                                                <label for="manual_nomor_ba">Nomor BA (Opsional)</label>
                                            </div>
                                        </div>
                                        <!-- Pesan Auto -->
                                        <div id="autoBAMsg" class="d-flex align-items-center mt-1 animate__animated animate__fadeIn">
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                                                <i class="bi bi-robot me-2"></i> Mode Otomatis
                                            </span>
                                            <span class="ms-2 small text-muted fst-italic">Format: BA/YYYY/MM/XXXX</span>
                                        </div>
                                    @else
                                        <div class="form-floating">
                                            <input type="text" name="manual_nomor_ba" id="manual_nomor_ba_forced" 
                                                   class="form-control fw-bold font-monospace text-uppercase border-warning" 
                                                   placeholder="Isi Nomor..."
                                                   value="{{ old('manual_nomor_ba') }}">
                                            <label for="manual_nomor_ba_forced">Nomor BA (Opsional)</label>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" name="tanggal_ba" class="form-control bg-light border-0 fw-bold" id="tanggal_ba" value="{{ old('tanggal_ba', date('Y-m-d')) }}" required>
                                        <label for="tanggal_ba" class="fw-bold text-secondary">Tanggal Dokumen</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="approver_id" class="form-select bg-light border-0 fw-bold" id="approver_id" required>
                                            <option value="">-- Pilih Pejabat --</option>
                                            @foreach($approvers as $approver)
                                            <option value="{{ $approver->id }}" {{ old('approver_id') == $approver->id ? 'selected' : '' }}>
                                                {{ $approver->name }} - {{ $approver->jabatan }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <label for="approver_id" class="fw-bold text-secondary">Pejabat Approver</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Hasil Pengecekan -->
                        <div class="mb-5">
                            <h6 class="text-uppercase fw-bold text-secondary small mb-3 ls-1">
                                <i class="bi bi-shield-check me-1"></i> Hasil Validasi
                            </h6>

                            <!-- Watchlist Card -->
                            <div class="mb-4">
                                <label class="fw-bold text-dark mb-2 d-block">1. Database Watch List (APU PPT) <span class="text-danger">*</span></label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="watchlist_match" id="wl_no" value="0" checked required>
                                        <label class="selection-card p-3 w-100 rounded-3 d-flex align-items-center cursor-pointer" for="wl_no">
                                            <div class="icon-box bg-success-subtle text-success rounded-circle me-3">
                                                <i class="bi bi-shield-check fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">AMAN / BERSIH</div>
                                                <small class="text-muted" style="font-size: 0.75rem;">Tidak ada kecocokan data</small>
                                            </div>
                                            <div class="check-indicator ms-auto text-success"><i class="bi bi-check-circle-fill fs-4"></i></div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="watchlist_match" id="wl_yes" value="1">
                                        <label class="selection-card p-3 w-100 rounded-3 d-flex align-items-center cursor-pointer danger-mode" for="wl_yes">
                                            <div class="icon-box bg-danger-subtle text-danger rounded-circle me-3">
                                                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">TERINDIKASI (HIT)</div>
                                                <small class="text-muted" style="font-size: 0.75rem;">Terdapat kecocokan data</small>
                                            </div>
                                            <div class="check-indicator ms-auto text-danger"><i class="bi bi-check-circle-fill fs-4"></i></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Card -->
                            <div class="mb-4">
                                <label class="fw-bold text-dark mb-2 d-block">2. Database Existing (Duplikasi) <span class="text-danger">*</span></label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="existing_match" id="ex_no" value="0" checked required>
                                        <label class="selection-card p-3 w-100 rounded-3 d-flex align-items-center cursor-pointer" for="ex_no">
                                            <div class="icon-box bg-success-subtle text-success rounded-circle me-3">
                                                <i class="bi bi-person-plus-fill fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">DATA BARU</div>
                                                <small class="text-muted" style="font-size: 0.75rem;">Nasabah belum terdaftar</small>
                                            </div>
                                            <div class="check-indicator ms-auto text-success"><i class="bi bi-check-circle-fill fs-4"></i></div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="existing_match" id="ex_yes" value="1">
                                        <label class="selection-card p-3 w-100 rounded-3 d-flex align-items-center cursor-pointer danger-mode" for="ex_yes">
                                            <div class="icon-box bg-danger-subtle text-danger rounded-circle me-3">
                                                <i class="bi bi-people-fill fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">DUPLIKASI</div>
                                                <small class="text-muted" style="font-size: 0.75rem;">Nasabah sudah terdaftar</small>
                                            </div>
                                            <div class="check-indicator ms-auto text-danger"><i class="bi bi-check-circle-fill fs-4"></i></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <div class="form-floating">
                                <textarea name="notes" class="form-control bg-white border" id="notes" style="height: 100px" placeholder="Catatan"></textarea>
                                <label for="notes" class="text-muted">Catatan Tambahan (Opsional)</label>
                            </div>
                        </div>

                        <hr class="my-5 opacity-10">

                        <!-- Action Button (TOMBOL BIRU SESUAI REQUEST) -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="bi bi-lock-fill me-1"></i> Data aman & terenkripsi
                            </div>
                            <!-- CLASS BTN-PRIMARY = BIRU -->
                            <button type="button" id="btnSubmit" class="btn btn-primary btn-lg px-5 rounded-pill shadow hover-scale">
                                <i class="bi bi-save2-fill me-2"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: SIDEBAR -->
        <div class="col-12 col-lg-4">
            <div class="sticky-top" style="top: 100px; z-index: 1;">
                <div class="card shadow-sm border-0 rounded-4 mb-4 bg-white overflow-hidden">
                    <div class="card-body p-0">
                        <!-- HEADER SIDEBAR BIRU -->
                        <div class="p-4 bg-primary text-white">
                            <h6 class="mb-1 fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Informasi Approval</h6>
                            <p class="small text-white-50 mb-0">Urutan pejabat berwenang.</p>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item p-3 border-bottom-0 d-flex align-items-center">
                                <div class="badge bg-primary-subtle text-primary rounded-pill me-3">1</div>
                                <div>
                                    <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">Group Head Sales</span>
                                    <small class="text-muted" style="font-size: 0.75rem;">Approver Utama</small>
                                </div>
                            </div>
                            <div class="list-group-item p-3 border-bottom-0 d-flex align-items-center">
                                <div class="badge bg-secondary-subtle text-secondary rounded-pill me-3">2</div>
                                <div>
                                    <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">Direktur Utama</span>
                                    <small class="text-muted" style="font-size: 0.75rem;">Backup Pertama</small>
                                </div>
                            </div>
                            <div class="list-group-item p-3 d-flex align-items-center">
                                <div class="badge bg-secondary-subtle text-secondary rounded-pill me-3">3</div>
                                <div>
                                    <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">Direktur</span>
                                    <small class="text-muted" style="font-size: 0.75rem;">Backup Kedua</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-warning-subtle border-0 rounded-4">
                    <div class="card-body p-3 d-flex">
                        <i class="bi bi-lightbulb-fill text-warning me-3 fs-4"></i>
                        <p class="small text-dark mb-0 lh-sm">
                            Pastikan Anda telah melakukan pengecekan data di aplikasi <strong>BOFIS</strong> dan <strong>PPATK</strong> sebelum menyimpan.
                        </p>
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
    /* VARIABLES */
    :root { 
        --primary-blue: #0d6efd; 
        --dark-navy: #104e70; /* Warna tombol sesuai gambar referensi popup */
    }
    
    .ls-1 { letter-spacing: 1px; }
    .cursor-pointer { cursor: pointer; }
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: translateY(-2px); }

    /* STEP PROGRESS & FORM STYLES (Sama seperti sebelumnya) */
    .step-circle { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2; position: relative; }
    .step-label { font-size: 0.75rem; position: absolute; top: 36px; left: 50%; transform: translateX(-50%); white-space: nowrap; }
    .ring-effect { box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2); }
    .selection-card { border: 2px solid #f1f5f9; transition: all 0.25s ease; background-color: #fff; position: relative; overflow: hidden; }
    .selection-card:hover { border-color: #cbd5e1; transform: translateY(-2px); }
    .btn-check:checked + .selection-card { border-color: #198754; background-color: #f0fdf4; }
    .btn-check:checked + .selection-card.danger-mode { border-color: #dc3545; background-color: #fef2f2; }
    .form-floating > .form-control { border-radius: 0.75rem; }

    /* --- CUSTOM POPUP STYLE (AGAR MIRIP GAMBAR) --- */
    .swal-custom-popup {
        border-radius: 20px !important;
        padding: 2rem !important;
        width: 450px !important;
    }
    
    /* Membuat Icon Tanda Tanya Manual (Bulat, Garis Tipis) */
    .custom-icon-container {
        width: 80px;
        height: 80px;
        border: 3px solid #94a3b8; /* Warna abu-abu border */
        border-radius: 50%;
        margin: 0 auto 1.5rem auto;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 2.5rem;
        font-family: sans-serif;
    }

    /* Tombol Cancel (Putih/Abu) */
    .btn-swal-cancel {
        background-color: #fff !important;
        color: #64748b !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 50px !important;
        padding: 10px 24px !important;
        font-weight: 500 !important;
        margin-right: 10px;
    }
    .btn-swal-cancel:hover { background-color: #f8f9fa !important; }

    /* Tombol Confirm (Biru Tua Gelap - Sesuai Gambar) */
    .btn-swal-confirm {
        background-color: var(--dark-navy) !important;
        color: #fff !important;
        border: none !important;
        border-radius: 50px !important;
        padding: 10px 32px !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 6px rgba(16, 78, 112, 0.3);
    }
    .btn-swal-confirm:hover { background-color: #0c3b55 !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Logic Toggle Manual BA
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('toggleManualBA');
        const inputWrapper = document.getElementById('manualBAInputWrapper');
        const autoMsg = document.getElementById('autoBAMsg');
        const inputField = document.getElementById('manual_nomor_ba');

        function updateState() {
            if (toggle && toggle.checked) {
                inputWrapper.style.display = 'block';
                autoMsg.style.display = 'none';
                setTimeout(() => inputField.focus(), 100); 
            } else if (toggle) {
                inputWrapper.style.display = 'none';
                autoMsg.style.display = 'flex';
                inputField.value = '';
            }
        }
        if (toggle) {
            toggle.addEventListener('change', updateState);
            if (toggle.checked) updateState();
        }
    });

    // --- SWEETALERT CUSTOM LOGIC ---
    document.getElementById('btnSubmit').addEventListener('click', function() {
        const form = document.getElementById('baForm');
        
        // Cek validasi HTML native dulu
        if (!form.checkValidity()) { 
            form.reportValidity(); 
            return; 
        }

        // Tampilkan Popup Custom
        Swal.fire({
            // Kita gunakan HTML custom untuk meniru layout gambar 100%
            html: `
                <div class="custom-icon-container">?</div>
                <h3 class="fw-bold mb-2" style="color: #104e70;">Simpan Dokumen?</h3>
                <p class="text-muted mb-4">
                    Anda akan menyimpan data nasabah <b>{{ $nasabah->nama }}</b>.<br>
                    <span class="small">Pastikan dokumen sudah diperiksa dengan benar.</span>
                </p>
            `,
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true, // Agar tombol Batal di kiri, Simpan di kanan
            buttonsStyling: false, // Matikan styling default SweetAlert
            customClass: {
                popup: 'swal-custom-popup',
                confirmButton: 'btn-swal-confirm',
                cancelButton: 'btn-swal-cancel'
            },
            focusConfirm: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Loading State
                Swal.fire({
                    title: 'Memproses...',
                    html: '<div class="spinner-border text-primary" role="status"></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    customClass: { popup: 'swal-custom-popup' }
                });
                form.submit();
            }
        });
    });
</script>
@endpush