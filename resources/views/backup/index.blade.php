@extends('layouts.app')

@section('title', 'Backup & Restore')

@push('styles')
<style>
    :root {
        --primary-dark: #0f4c75; /* Warna Biru Tua sesuai referensi */
        --primary-light: #3282b8;
        --accent: #bbe1fa;
        --danger: #dc3545;
        --success: #198754;
    }

    /* --- 1. Header Style (Mirip Gambar Referensi) --- */
    .header-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    
    .header-icon-wrapper {
        width: 56px;
        height: 56px;
        background-color: rgba(15, 76, 117, 0.1);
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

    .btn-header-action {
        background-color: var(--primary-dark);
        color: white;
        font-weight: 600;
        padding: 0.6rem 1.5rem;
        border-radius: 50px; /* Pill shape */
        border: none;
        transition: all 0.3s;
    }

    .btn-header-action:hover {
        background-color: #0b3b5b;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 76, 117, 0.3);
    }

    /* --- 2. Stats Cards Modern --- */
    .stat-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* --- 3. Table & Content --- */
    .content-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }

    .table-modern thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #6c757d;
        border-bottom: 2px solid #eee;
        padding: 1rem;
    }
    
    .table-modern tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f2f2f2;
    }

    .table-modern tbody tr:hover {
        background-color: #fcfcfc;
    }

    /* --- 4. Upload Zone --- */
    .upload-zone {
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        background: #f8fafc;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .upload-zone:hover {
        border-color: var(--primary-dark);
        background: #f1f5f9;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <!-- BAGIAN 1: HEADER (Sesuai Request Gambar) -->
    <div class="card header-card border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <!-- Kiri: Icon & Judul -->
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon-wrapper">
                        <i class="bi bi-database-fill-gear"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--primary-dark);">Backup & Restore</h4>
                        <p class="mb-0 text-muted">Kelola keamanan data sistem dan file dokumen</p>
                    </div>
                </div>
                <!-- Kanan: Tombol -->
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#uploadBackupModal">
                        <i class="bi bi-cloud-upload me-1"></i> Upload
                    </button>
                    <button class="btn btn-header-action" data-bs-toggle="modal" data-bs-target="#createBackupModal">
                        <i class="bi bi-plus-lg me-1"></i> Buat Backup Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- BAGIAN 2: STATISTIK QUICK VIEW -->
    <div class="row g-4 mb-4">
        <!-- Card Database -->
        <div class="col-md-4">
            <div class="card stat-card h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                        <i class="bi bi-hdd-stack"></i>
                    </div>
                    <div>
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Database Size</small>
                        <h5 class="mb-0 fw-bold">{{ $dbSize ?? '0 MB' }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card Files -->
        <div class="col-md-4">
            <div class="card stat-card h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div>
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">File Documents</small>
                        <h5 class="mb-0 fw-bold">{{ $fileSize ?? '0 MB' }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card Last Backup -->
        <div class="col-md-4">
            <div class="card stat-card h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Last Backup</small>
                        <h5 class="mb-0 fw-bold">{{ $lastBackupDate ?? '-' }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BAGIAN 3: TABEL DAFTAR BACKUP -->
    <div class="card content-card">
        <div class="card-header bg-white border-0 py-3 px-4">
            <h5 class="fw-bold mb-0" style="color: var(--primary-dark);">Riwayat Backup Tersedia</h5>
        </div>
        
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="35%">Nama File Backup</th>
                        <th width="15%">Ukuran</th>
                        <th width="25%">Tanggal Dibuat</th>
                        <th width="20%" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backups as $index => $backup)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-zip-fill text-warning fs-4 me-3"></i>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $backup['name'] }}</div>
                                    <small class="text-muted">Full Backup (DB + Files)</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $backup['size'] }}</span>
                        </td>
                        <td>
                            <div>
                                <i class="bi bi-calendar2-week me-1 text-muted"></i> 
                                {{ $backup['date']->format('d M Y') }}
                                <small class="text-muted ms-1">({{ $backup['date']->format('H:i') }})</small>
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="btn-group shadow-sm" role="group">
                                <a href="{{ route('backup.download', $backup['name']) }}" 
                                   class="btn btn-sm btn-light text-primary" 
                                   data-bs-toggle="tooltip" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-light text-warning" 
                                        onclick="openRestoreModal('{{ $backup['name'] }}')"
                                        data-bs-toggle="tooltip" title="Restore">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-light text-danger" 
                                        onclick="openDeleteModal('{{ $backup['name'] }}')"
                                        data-bs-toggle="tooltip" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="opacity-50 mb-3">
                                <i class="bi bi-inbox fs-1"></i>
                            </div>
                            <h6 class="text-muted">Belum ada data backup</h6>
                            <small>Silakan buat backup baru terlebih dahulu</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALS SECTION -->
<!-- ============================================ -->

<!-- 1. Modal Buat Backup -->
<div class="modal fade" id="createBackupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Buat Backup Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('backup.create') }}" method="POST" id="formCreate">
                @csrf
                <div class="modal-body text-center p-4">
                    <div id="initialState">
                        <div class="mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3">
                                <i class="bi bi-database-add text-primary display-5"></i>
                            </div>
                        </div>
                        <p class="text-muted mb-4">
                            Proses ini akan mencadangkan seluruh <strong>Database</strong> dan <strong>File Dokumen</strong> ke dalam satu file ZIP.
                        </p>
                        <button type="submit" class="btn btn-header-action w-100 py-2">
                            <i class="bi bi-play-circle me-2"></i>Mulai Proses Backup
                        </button>
                    </div>
                    
                    <!-- Loading State (Hidden by default) -->
                    <div id="loadingState" class="d-none py-3">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <h6 class="fw-bold">Sedang Memproses...</h6>
                        <p class="small text-muted">Mohon jangan tutup halaman ini.</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. Modal Upload Backup -->
<div class="modal fade" id="uploadBackupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Upload File Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('backup.upload') }}" method="POST" enctype="multipart/form-data" id="formUpload">
                @csrf
                <div class="modal-body pt-0">
                    <div class="upload-zone text-center p-4 mb-3 position-relative">
                        <input type="file" name="backup_file" id="backupInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;" accept=".zip" required>
                        <i class="bi bi-cloud-arrow-up display-6 text-muted mb-2"></i>
                        <h6 class="fw-bold mb-1">Klik atau Drop file disini</h6>
                        <p class="small text-muted mb-0" id="fileNameDisplay">Format .zip (Max 500MB)</p>
                    </div>
                    <div class="alert alert-warning border-0 d-flex align-items-center small p-2">
                        <i class="bi bi-exclamation-circle me-2 fs-5"></i>
                        <div>Pastikan file backup berasal dari sistem yang sama.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 3. Modal Konfirmasi Restore -->
<div class="modal fade" id="restoreModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-top: 5px solid #ffc107 !important;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Konfirmasi Restore</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="restoreForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-exclamation-triangle text-warning display-3 mb-2"></i>
                        <h5 class="fw-bold text-danger">PERINGATAN KERAS!</h5>
                        <p class="text-muted small">Anda akan mengembalikan sistem menggunakan file:</p>
                        <div class="badge bg-light text-dark border p-2 fs-6" id="restoreFileName">filename.zip</div>
                    </div>
                    
                    <div class="alert alert-danger border-0 small">
                        <ul class="mb-0 ps-3">
                            <li>Data saat ini akan <strong>DIHAPUS & DITIMPA</strong>.</li>
                            <li>Tindakan ini <strong>TIDAK DAPAT DIBATALKAN</strong>.</li>
                        </ul>
                    </div>

                    <div class="form-check bg-light p-3 rounded border">
                        <input class="form-check-input" type="checkbox" id="confirmRestoreCheck">
                        <label class="form-check-label fw-semibold small" for="confirmRestoreCheck">
                            Saya mengerti resikonya dan ingin melanjutkan restore.
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4" id="btnSubmitRestore" disabled>
                        <i class="bi bi-arrow-counterclockwise"></i> Ya, Restore Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 4. Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <i class="bi bi-trash3 text-danger display-4 mb-3"></i>
                <h5 class="fw-bold">Hapus File?</h5>
                <p class="text-muted small mb-4">File backup ini akan dihapus permanen.</p>
                
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-sm px-4">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // 1. Logic Modal Create (Loading)
    document.getElementById('formCreate').addEventListener('submit', function() {
        document.getElementById('initialState').classList.add('d-none');
        document.getElementById('loadingState').classList.remove('d-none');
    });

    // 2. Logic Modal Upload (File Name)
    document.getElementById('backupInput').addEventListener('change', function(e) {
        if(this.files && this.files[0]) {
            document.getElementById('fileNameDisplay').innerHTML = 
                '<span class="text-success fw-bold"><i class="bi bi-check-circle"></i> ' + this.files[0].name + '</span>';
        }
    });

    // 3. Logic Modal Restore
    function openRestoreModal(filename) {
        var modal = new bootstrap.Modal(document.getElementById('restoreModal'));
        document.getElementById('restoreFileName').innerText = filename;
        document.getElementById('restoreForm').action = "/backup/restore/" + filename; // Sesuaikan route
        
        // Reset
        document.getElementById('confirmRestoreCheck').checked = false;
        document.getElementById('btnSubmitRestore').disabled = true;
        
        modal.show();
    }

    document.getElementById('confirmRestoreCheck').addEventListener('change', function() {
        document.getElementById('btnSubmitRestore').disabled = !this.checked;
    });

    // 4. Logic Modal Delete
    function openDeleteModal(filename) {
        var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('deleteForm').action = "/backup/delete/" + filename; // Sesuaikan route
        modal.show();
    }
</script>
@endpush