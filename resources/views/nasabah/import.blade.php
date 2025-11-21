@extends('layouts.app')

@section('title', 'Import Data Nasabah')

@section('content')
<div class="container-fluid px-4">
    
    <!-- 1. PAGE HEADER (STYLE BARU) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-lg bg-company-subtle text-company rounded-circle me-3">
                            <i class="bi bi-file-earmark-arrow-up-fill display-6"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-company mb-1">Import Data Nasabah</h3>
                            <p class="text-muted mb-0">Upload file Excel untuk import data massal. Data ganda otomatis dilewati.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('nasabah.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Warning -->
    @if(session('warning'))
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-start fade show rounded-4" role="alert">
        <div class="fs-3 me-3 text-warning-emphasis flex-shrink-0">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="alert-heading fw-bold mb-2 text-warning-emphasis">Laporan Import</h6>
            <p class="mb-0 fw-semibold fs-6">{{ session('warning') }}</p>
            @if(session('errors_detail'))
            <hr class="my-2 border-warning-subtle">
            <p class="mb-0 small text-muted">
                <i class="bi bi-arrow-down-circle me-1"></i> Lihat tabel di bawah untuk detail data yang dilewati.
            </p>
            @endif
        </div>
        <button type="button" class="btn-close ms-2 flex-shrink-0" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-start fade show rounded-4" role="alert">
        <div class="fs-3 me-3 text-danger flex-shrink-0">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="alert-heading fw-bold mb-2 text-danger">Gagal Memproses File</h6>
            <p class="mb-0 fw-semibold">{{ session('error') }}</p>
        </div>
        <button type="button" class="btn-close ms-2 flex-shrink-0" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-start fade show rounded-4" role="alert">
        <div class="fs-3 me-3 text-danger flex-shrink-0">
            <i class="bi bi-exclamation-octagon-fill"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="alert-heading fw-bold mb-2 text-danger">Validasi Gagal!</h6>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                <li class="fw-semibold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close ms-2 flex-shrink-0" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- KOLOM KIRI: Form Upload -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-company text-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-cloud-upload-fill me-2"></i> Upload File Excel</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('nasabah.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold text-company">Pilih File Excel <span class="text-danger">*</span></label>
                            <div class="upload-area p-5 text-center border rounded-4 bg-light position-relative" id="dropArea">
                                <input type="file" name="file" id="fileInput" class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0 pointer" accept=".xlsx,.xls,.csv" required>
                                <i class="bi bi-file-earmark-excel text-success mb-3" style="font-size: 3rem;"></i>
                                <h5 class="mb-2">Klik atau Seret File ke Sini</h5>
                                <p class="text-muted small mb-0">Format: .xlsx, .xls, .csv (Maks. 5MB)</p>
                                <div id="fileNameDisplay" class="mt-3 badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6 d-none">
                                    <i class="bi bi-check-circle me-1"></i> <span id="selectedFileName">filename.xlsx</span>
                                </div>
                                @error('file')
                                    <div class="text-danger mt-2 fw-bold small"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Alert Info -->
                        <div class="alert alert-info border-0 d-flex align-items-start mb-4 rounded-4">
                            <i class="bi bi-info-circle-fill fs-4 me-3 text-info-emphasis"></i>
                            <div>
                                <h6 class="alert-heading fw-bold text-info-emphasis mb-2">Perhatian Penting:</h6>
                                <ul class="mb-0 small ps-3 text-muted fw-semibold">
                                    <li>Sistem mengecek duplikat berdasarkan <strong>KTP, NPWP, atau Nama</strong>.</li>
                                    <li>Data yang sudah ada di sistem akan <strong>DILEWATI (SKIP)</strong>.</li>
                                    <li>Format tanggal: <strong>25-Sep-72</strong>, <strong>15-Jan-85</strong>, atau <strong>10-Mar-90</strong> (TEXT biasa).</li>
                                    <li><strong>SEMUA kolom di template sudah diformat sebagai TEXT</strong> - tinggal copy-paste saja!</li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-company btn-lg shadow-sm py-3 rounded-pill" id="submitBtn">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Proses Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Detail Errors -->
            @if(session('errors_detail'))
            <div class="card border-warning shadow-sm mb-4 rounded-4" id="errorDetailCard">
                <div class="card-header bg-warning bg-opacity-10 text-dark py-3 border-warning">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-clipboard-data-fill me-2 text-warning-emphasis"></i> Detail Data Dilewati / Gagal ({{ count(session('errors_detail')) }} Baris)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="error-list-container overflow-auto" style="max-height: 500px;">
                        <table class="table table-sm table-hover mb-0 table-striped">
                            <thead class="table-light sticky-top border-bottom">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(session('errors_detail') as $index => $error)
                                <tr>
                                    <td class="text-center fw-bold text-secondary">{{ $index + 1 }}</td>
                                    <td class="font-monospace small py-2">
                                        @if(str_contains($error, 'DILEWATI') || str_contains($error, 'SKIP'))
                                            <span class="badge bg-warning text-dark me-1">SKIP</span>
                                        @elseif(str_contains($error, 'Gagal') || str_contains($error, 'Error'))
                                            <span class="badge bg-danger me-1">ERROR</span>
                                        @else
                                            <i class="bi bi-dot text-muted"></i>
                                        @endif
                                        {{ $error }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- KOLOM KANAN -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-download me-2"></i> Template Excel</h6>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-3"><i class="bi bi-file-earmark-excel-fill text-success" style="font-size: 3rem;"></i></div>
                    <p class="small text-muted mb-3">Template Excel dengan format <strong>anti-rumus</strong>. Semua kolom sudah diset sebagai <span class="badge bg-success-subtle text-success">TEXT</span></p>
                    
                    <!-- TOMBOL DOWNLOAD TANPA JAVASCRIPT -->
                    <a href="{{ route('nasabah.template.download') }}" class="btn btn-success w-100 shadow-sm mb-3 rounded-pill fw-bold">
                        <i class="bi bi-file-earmark-excel-fill me-2"></i> Download Template
                    </a>

                    <div class="d-flex align-items-center justify-content-center gap-2 small text-success mb-3">
                        <i class="bi bi-check-circle-fill"></i> <span class="fw-semibold">Format Terbaru 2025</span>
                    </div>
                    
                    <!-- Info Format -->
                    <div class="alert alert-success-subtle border border-success-subtle rounded-3 py-2 px-3 mb-0">
                        <small class="text-success-emphasis">
                            <i class="bi bi-shield-fill-check me-1"></i>
                            <strong>100% Anti-Rumus:</strong><br>
                            KTP, NPWP & Tanggal tidak akan rusak saat edit!
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Tips -->
            <div class="card shadow-sm border-0 bg-gradient-info text-white rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-lightbulb-fill me-2"></i> Tips Penggunaan Template</h6>
                    <ul class="small mb-0 opacity-90 ps-3">
                        <li class="mb-2"><strong>SEMUA kolom sudah TEXT</strong> - tidak ada rumus otomatis!</li>
                        <li class="mb-2"><strong>KTP & NPWP</strong> tidak akan jadi scientific notation</li>
                        <li class="mb-2"><strong>Tanggal</strong> tidak akan jadi rumus Excel</li>
                        <li class="mb-0"><strong>Copy-paste bebas</strong> dari Excel/CSV lain tanpa khawatir!</li>
                    </ul>
                </div>
            </div>
            
            <!-- Warning -->
            <div class="card shadow-sm border-0 border-start border-warning border-4 rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2 text-warning"><i class="bi bi-exclamation-triangle-fill me-2"></i> Anti Duplikat</h6>
                    <p class="small mb-0 text-muted">Sistem akan menolak jika <strong>KTP, NPWP, atau Nama</strong> persis sudah ada di database.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* === BRAND VARIABLES === */
    :root { --calm-water-blue: #165581; --atmospheric-blue: #29AAE2; --sincere-yellow: #EFCA18; }
    
    /* === UTILS === */
    .font-monospace { font-family: 'Courier New', Consolas, monospace; }
    .rounded-4 { border-radius: 1rem !important; }
    
    /* === HEADER ICON === */
    .icon-box-lg { width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; }
    .text-company { color: var(--calm-water-blue); }
    .bg-company-subtle { background-color: rgba(22, 85, 129, 0.1); color: var(--calm-water-blue); }
    
    .bg-gradient-company { background: linear-gradient(135deg, var(--calm-water-blue) 0%, #2d7a9e 100%); }
    .btn-company { background-color: var(--calm-water-blue); border-color: var(--calm-water-blue); color: white; font-weight: 600; transition: all 0.3s ease; }
    .btn-company:hover { background-color: #114466; border-color: #114466; color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(22, 85, 129, 0.3); }
    
    /* Upload Area */
    .upload-area { transition: all 0.3s ease; border: 2px dashed #dee2e6 !important; }
    .upload-area:hover, .upload-area.dragover { border-color: var(--calm-water-blue) !important; background-color: rgba(22, 85, 129, 0.05) !important; transform: scale(1.01); }
    .pointer { cursor: pointer; }
    
    .sticky-top { position: sticky; top: 0; z-index: 10; }
    .bg-gradient-info { background: linear-gradient(135deg, #0dcaf0 0%, #3dd5f3 100%); }
    .error-list-container { scrollbar-width: thin; scrollbar-color: #EFCA18 #fff3cd; }

    /* SweetAlert Custom */
    .custom-swal-popup { border-radius: 24px !important; padding-top: 0 !important; overflow: hidden; font-family: 'Segoe UI', Tahoma, sans-serif; }
    .custom-swal-popup::before { content: ''; display: block; height: 8px; width: 100%; background: linear-gradient(90deg, var(--calm-water-blue) 0%, var(--calm-water-blue) 33%, var(--atmospheric-blue) 33%, var(--atmospheric-blue) 66%, var(--sincere-yellow) 66%, var(--sincere-yellow) 100%); }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const dropArea = document.getElementById('dropArea');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const selectedFileName = document.getElementById('selectedFileName');
    const submitBtn = document.getElementById('submitBtn');
    const importForm = document.getElementById('importForm');
    
    // === DRAG & DROP ===
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });
    function preventDefaults(e) { e.preventDefault(); e.stopPropagation(); }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.add('dragover'), false);
    });
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.remove('dragover'), false);
    });
    
    dropArea.addEventListener('drop', handleDrop, false);
    fileInput.addEventListener('change', function() { handleFiles(this.files); });
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFiles(files);
    }
    
    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];
            const fileName = file.name;
            const fileSize = (file.size / (1024 * 1024)).toFixed(2);
            selectedFileName.textContent = file.name + ' (' + fileSize + ' MB)';
            fileNameDisplay.classList.remove('d-none');
        } else {
            fileNameDisplay.classList.add('d-none');
        }
    }
    
    // === SUBMIT IMPORT (FULL SCREEN LOADING) ===
    importForm.addEventListener('submit', function(e) {
        if(fileInput.files.length === 0) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'File Belum Dipilih', text: 'Silakan pilih file Excel.', confirmButtonColor: '#165581' });
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sedang Memproses...';
        
        Swal.fire({
            title: 'Sedang Import...',
            html: '<div class="mb-3"><i class="bi bi-hourglass-split fs-1 text-primary"></i></div>Sistem sedang mengecek data...',
            timerProgressBar: true,
            didOpen: () => { Swal.showLoading(); },
            backdrop: `rgba(255,255,255,0.9)`,
            color: '#165581',
            customClass: { popup: 'custom-swal-popup border-0 shadow-lg' },
            allowOutsideClick: false
        });
    });

    @if(session('success'))
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', iconColor: '#29AAE2', timer: 5000, showConfirmButton: false });
    @endif

    @if(session('errors_detail'))
        setTimeout(() => {
            const errorCard = document.getElementById('errorDetailCard');
            if (errorCard) errorCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 500);
    @endif
});
</script>
@endpush