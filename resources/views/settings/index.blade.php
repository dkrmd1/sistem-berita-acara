@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@push('styles')
<style>
    :root {
        --primary-dark: #0f4c75; /* Biru Tua */
        --primary-light: #3282b8;
        --accent: #bbe1fa;
    }

    /* --- 1. Header Style (Sama dengan Backup) --- */
    .header-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: none;
    }
    
    .header-icon-wrapper {
        width: 56px;
        height: 56px;
        background-color: rgba(15, 76, 117, 0.1); /* Background biru transparan */
        color: var(--primary-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        transition: transform 0.3s;
    }

    .header-card:hover .header-icon-wrapper {
        transform: scale(1.1);
        background-color: var(--primary-dark);
        color: #fff;
    }

    /* --- 2. Content Card Style --- */
    .content-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        background: #fff;
        transition: all 0.3s;
    }

    .content-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    }

    .card-header-custom {
        background-color: transparent;
        border-bottom: 1px solid #f0f0f0;
        padding: 1.5rem;
    }

    /* --- 3. Custom Switch & Button --- */
    .form-check-input:checked {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .btn-action-primary {
        background-color: var(--primary-dark);
        color: white;
        border: none;
        padding: 0.6rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-action-primary:hover {
        background-color: #0b3b5b;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 76, 117, 0.3);
    }
    
    .info-box {
        background-color: #f8f9fa;
        border-left: 4px solid var(--primary-light);
        border-radius: 8px;
        padding: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <!-- BAGIAN 1: HEADER (Style Backup & Restore) -->
    <div class="card header-card mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <!-- Kiri: Icon & Judul -->
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon-wrapper">
                        <i class="bi bi-sliders"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--primary-dark);">Pengaturan Sistem</h4>
                        <p class="mb-0 text-muted">Konfigurasi fitur utama dan preferensi aplikasi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BAGIAN 2: KONTEN PENGATURAN -->
    <div class="row">
        <div class="col-lg-7">
            <div class="card content-card h-100">
                <div class="card-header card-header-custom">
                    <h5 class="fw-bold mb-0" style="color: var(--primary-dark);">
                        <i class="bi bi-file-earmark-text me-2"></i>Konfigurasi Berita Acara
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="fw-bold text-dark mb-3 d-block">Metode Penomoran</label>
                            
                            <div class="d-flex align-items-start gap-3">
                                <div class="form-check form-switch mt-1">
                                    <input class="form-check-input" style="width: 3em; height: 1.5em;" 
                                           type="checkbox" role="switch" 
                                           id="autoBaSwitch" 
                                           name="auto_generate_ba" 
                                           value="1" 
                                           {{ $autoGenerateBA == '1' ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label class="form-check-label fw-bold" for="autoBaSwitch" style="font-size: 1.1rem;">
                                        Aktifkan Auto Generate Nomor BA
                                    </label>
                                    <p class="text-muted small mb-0 mt-1">
                                        Mengizinkan sistem untuk membuat nomor dokumen secara otomatis.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Info Box Modern -->
                        <div class="info-box mb-4">
                            <h6 class="fw-bold mb-2 text-primary-dark">
                                <i class="bi bi-info-circle-fill me-1"></i> Penjelasan Logika:
                            </h6>
                            <ul class="mb-0 small text-secondary ps-3">
                                <li class="mb-1">
                                    <strong>Jika Aktif (ON):</strong> Staff CS memiliki opsi untuk memilih input "Otomatis" atau "Manual" saat membuat Berita Acara.
                                </li>
                                <li>
                                    <strong>Jika Mati (OFF):</strong> Fitur otomatis dimatikan total. Staff CS wajib menginput nomor secara manual.
                                </li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end pt-2 border-top">
                            <button type="submit" class="btn btn-action-primary">
                                <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Opsional: Kolom Kanan untuk Info Tambahan / Log -->
        <div class="col-lg-5">
            <div class="card content-card h-100 bg-light border-0">
                <div class="card-body p-4 d-flex flex-column justify-content-center text-center text-muted">
                    <i class="bi bi-gear-wide-connected display-1 mb-3 opacity-25"></i>
                    <h6 class="fw-bold">Pusat Kontrol</h6>
                    <p class="small">
                        Pengaturan ini berdampak langsung pada operasional input data. Pastikan perubahan sudah dikoordinasikan dengan tim terkait.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Opsional: Menambahkan efek toast/alert sederhana jika diperlukan
    document.addEventListener('DOMContentLoaded', function () {
        // Init Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush