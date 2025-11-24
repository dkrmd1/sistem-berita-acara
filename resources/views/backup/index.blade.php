@extends('layouts.app')

@section('title', 'Backup & Restore')

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
    .backup-table tbody tr {
        transition: background-color 0.2s;
    }
    .backup-table tbody tr:hover {
        background-color: rgba(64, 107, 133, 0.05);
    }
    .action-btn-group .btn {
        transition: all 0.2s;
    }
    .action-btn-group .btn:hover {
        transform: scale(1.05);
    }
    .upload-zone {
        border: 2px dashed var(--calm-water-blue);
        background: rgba(64, 107, 133, 0.02);
        transition: all 0.3s;
    }
    .upload-zone:hover {
        border-color: var(--atmospheric-blue);
        background: rgba(64, 107, 133, 0.05);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 fw-bold" style="color: var(--calm-water-blue);">
                        <i class="bi bi-database-fill-gear"></i> Backup & Restore
                    </h1>
                    <p class="text-muted mb-0">Kelola backup database dan file sistem</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadBackupModal">
                        <i class="bi bi-cloud-upload"></i> Upload Backup
                    </button>
                    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createBackupModal">
                        <i class="bi bi-plus-circle"></i> Buat Backup Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Database -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-3 p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-person-fill-gear fs-2 text-white"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small text-uppercase fw-semibold">Total Users</div>
                            <div class="fs-3 fw-bold" style="color: var(--calm-water-blue);">{{ $stats['users'] }}</div>
                            <small class="text-success">
                                <i class="bi bi-check-circle-fill"></i> Aktif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-3 p-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="bi bi-people-fill fs-2 text-white"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small text-uppercase fw-semibold">Total Nasabah</div>
                            <div class="fs-3 fw-bold" style="color: var(--calm-water-blue);">{{ number_format($stats['nasabah']) }}</div>
                            <small class="text-info">
                                <i class="bi bi-database-fill"></i> Records
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-3 p-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="bi bi-file-earmark-text-fill fs-2 text-white"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small text-uppercase fw-semibold">Total Berita Acara</div>
                            <div class="fs-3 fw-bold" style="color: var(--calm-water-blue);">{{ number_format($stats['berita_acara']) }}</div>
                            <small class="text-warning">
                                <i class="bi bi-file-pdf-fill"></i> Documents
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-3 p-3" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="bi bi-bell-fill fs-2 text-white"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small text-uppercase fw-semibold">Total Notifikasi</div>
                            <div class="fs-3 fw-bold" style="color: var(--calm-water-blue);">{{ number_format($stats['notifications']) }}</div>
                            <small class="text-secondary">
                                <i class="bi bi-chat-dots-fill"></i> Messages
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Backup -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-archive-fill fs-4 me-2" style="color: var(--calm-water-blue);"></i>
                <h5 class="mb-0 fw-bold">Daftar Backup Tersedia</h5>
                @if(count($backups) > 0)
                <span class="badge bg-primary ms-auto">{{ count($backups) }} File</span>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            @if(count($backups) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 backup-table">
                        <thead style="background: linear-gradient(135deg, var(--calm-water-blue), var(--calm-water-blue-dark)); color: white;">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="33%">
                                    <i class="bi bi-file-earmark-zip me-1"></i> Nama File
                                </th>
                                <th width="12%">
                                    <i class="bi bi-hdd me-1"></i> Ukuran
                                </th>
                                <th width="25%">
                                    <i class="bi bi-calendar3 me-1"></i> Tanggal Dibuat
                                </th>
                                <th width="25%" class="text-center">
                                    <i class="bi bi-gear-fill me-1"></i> Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $index => $backup)
                            <tr>
                                <td class="text-center">
                                    <span class="badge rounded-circle bg-light text-dark border">{{ $index + 1 }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-zip-fill fs-3 text-warning me-2"></i>
                                        <div>
                                            <div class="fw-semibold">{{ $backup['name'] }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-shield-check"></i> Backup Lengkap
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-gradient px-3 py-2">
                                        <i class="bi bi-hdd-fill me-1"></i> {{ $backup['size'] }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">
                                            <i class="bi bi-calendar-check me-1 text-primary"></i>
                                            {{ $backup['date']->format('d M Y') }}
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $backup['date']->format('H:i:s') }} WIB
                                        </small>
                                        <br>
                                        <small class="badge bg-info text-dark mt-1">
                                            {{ $backup['date']->diffForHumans() }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2 action-btn-group">
                                        <a href="{{ route('backup.download', $backup['name']) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Download Backup"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success" 
                                                onclick="confirmRestore('{{ $backup['name'] }}')"
                                                title="Restore dari Backup"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-arrow-clockwise"></i> Restore
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="confirmDelete('{{ $backup['name'] }}')"
                                                title="Hapus Backup"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-inbox fs-1 text-muted" style="font-size: 5rem; opacity: 0.2;"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum Ada Backup Tersedia</h5>
                    <p class="text-muted mb-3">Pilih salah satu opsi di bawah untuk memulai</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadBackupModal">
                            <i class="bi bi-cloud-upload"></i> Upload Backup
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBackupModal">
                            <i class="bi bi-plus-circle"></i> Buat Backup Baru
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Informasi Penting -->
    <div class="row mt-4">
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #17a2b8 !important;">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-info">
                        <i class="bi bi-info-circle-fill"></i> Informasi Backup
                    </h5>
                    <ul class="mb-0 ps-3">
                        <li class="mb-2">Backup mencakup <strong>semua data database</strong> (users, nasabah, berita acara, notifikasi)</li>
                        <li class="mb-2">Backup juga menyertakan <strong>file tanda tangan (TTD)</strong> dan <strong>PDF Berita Acara</strong></li>
                        <li class="mb-2">Format backup: <code>backup_YYYY-MM-DD_HHMMSS.zip</code></li>
                        <li class="mb-2">File backup dapat di-<strong>download</strong> dan disimpan di tempat aman</li>
                        <li>Proses mungkin memakan waktu beberapa menit tergantung ukuran data</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i> Peringatan Restore
                    </h5>
                    <ul class="mb-0 ps-3">
                        <li class="mb-2"><strong class="text-danger">PERINGATAN:</strong> Proses restore akan <u>menimpa semua data</u> yang ada saat ini!</li>
                        <li class="mb-2">Pastikan <strong>backup terlebih dahulu</strong> sebelum restore</li>
                        <li class="mb-2">Tidak ada tombol "undo" setelah restore dilakukan</li>
                        <li class="mb-2">File upload maksimal <strong>500MB</strong></li>
                        <li>Disarankan backup secara berkala (minimal <strong>1 minggu sekali</strong>)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Backup -->
<div class="modal fade" id="uploadBackupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-cloud-upload"></i> Upload File Backup
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('backup.upload') }}" method="POST" enctype="multipart/form-data" id="uploadBackupForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info border-0 d-flex align-items-start">
                        <i class="bi bi-info-circle-fill fs-4 me-3 mt-1"></i>
                        <div>
                            <strong>Catatan Penting:</strong>
                            <ul class="mb-0 mt-2 ps-3">
                                <li>File harus berformat <code>.zip</code></li>
                                <li>Format nama: <code>backup_YYYY-MM-DD_HHMMSS.zip</code></li>
                                <li>Ukuran maksimal: <strong>500MB</strong></li>
                                <li>File backup harus dibuat dari sistem ini</li>
                            </ul>
                        </div>
                    </div>

                    <div class="upload-zone text-center p-4 rounded-3 mb-3">
                        <i class="bi bi-cloud-arrow-up fs-1 text-primary mb-3 d-block"></i>
                        <h6 class="fw-bold mb-2">Pilih File Backup</h6>
                        <p class="text-muted small mb-3">Drag & drop atau klik untuk memilih file</p>
                        <input type="file" 
                               class="form-control" 
                               name="backup_file" 
                               id="backup_file" 
                               accept=".zip"
                               required>
                    </div>

                    <div id="fileInfo" class="alert alert-success border-0 d-none">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-zip-fill fs-3 me-3"></i>
                            <div class="flex-grow-1">
                                <div class="fw-bold" id="fileName">-</div>
                                <small class="text-muted" id="fileSize">-</small>
                            </div>
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success" id="uploadBtn" disabled>
                        <i class="bi bi-cloud-upload"></i> Upload & Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Buat Backup -->
<div class="modal fade" id="createBackupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--calm-water-blue), var(--calm-water-blue-dark));">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle"></i> Buat Backup Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('backup.create') }}" method="POST" id="backupForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning border-0 d-flex align-items-start">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3 mt-1"></i>
                        <div>
                            <strong>Perhatian:</strong> Proses backup akan memakan waktu beberapa saat. Jangan tutup halaman ini sampai proses selesai.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-check2-square text-success"></i> Backup akan mencakup:
                        </h6>
                        <div class="list-group">
                            <div class="list-group-item d-flex align-items-center">
                                <i class="bi bi-database-fill text-primary me-3 fs-5"></i>
                                <div>
                                    <div class="fw-semibold">Semua Data Database</div>
                                    <small class="text-muted">Users, Nasabah, Berita Acara, Notifications</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <i class="bi bi-pen-fill text-info me-3 fs-5"></i>
                                <div>
                                    <div class="fw-semibold">File Tanda Tangan (TTD)</div>
                                    <small class="text-muted">Semua tanda tangan digital pengguna</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <i class="bi bi-file-pdf-fill text-danger me-3 fs-5"></i>
                                <div>
                                    <div class="fw-semibold">File PDF Berita Acara</div>
                                    <small class="text-muted">Semua dokumen BA yang sudah dibuat</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 mb-0">
                        <small>
                            <i class="bi bi-clock-history me-1"></i> 
                            File backup akan disimpan dengan nama: 
                            <strong class="text-primary">backup_{{ date('Y-m-d_His') }}.zip</strong>
                        </small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Ya, Buat Backup Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Hidden untuk Delete -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Form Hidden untuk Restore -->
<form id="restoreForm" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('scripts')
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle file input change
    const fileInput = document.getElementById('backup_file');
    const uploadBtn = document.getElementById('uploadBtn');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Validasi format nama file
            const validFormat = /^backup_\d{4}-\d{2}-\d{2}_\d{6}\.zip$/;
            if (!validFormat.test(file.name)) {
                alert('❌ Format nama file tidak valid!\n\nHarus: backup_YYYY-MM-DD_HHMMSS.zip\nContoh: backup_2024-01-15_143052.zip');
                this.value = '';
                uploadBtn.disabled = true;
                fileInfo.classList.add('d-none');
                return;
            }

            // Validasi ukuran file (max 500MB)
            const maxSize = 500 * 1024 * 1024; // 500MB in bytes
            if (file.size > maxSize) {
                alert('❌ Ukuran file terlalu besar!\n\nMaksimal: 500MB\nUkuran file Anda: ' + formatBytes(file.size));
                this.value = '';
                uploadBtn.disabled = true;
                fileInfo.classList.add('d-none');
                return;
            }

            // Tampilkan info file
            fileName.textContent = file.name;
            fileSize.textContent = formatBytes(file.size);
            fileInfo.classList.remove('d-none');
            uploadBtn.disabled = false;
        }
    });
});

// Format bytes
function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Konfirmasi Restore
function confirmRestore(filename) {
    const confirmed = confirm(
        '⚠️ PERINGATAN PENTING!\n\n' +
        '═══════════════════════════════════\n\n' +
        'Proses RESTORE akan:\n' +
        '• MENGHAPUS semua data yang ada saat ini\n' +
        '• MENGGANTI dengan data dari backup\n' +
        '• Tidak bisa di-undo (dibatalkan)\n\n' +
        '═══════════════════════════════════\n\n' +
        'File: ' + filename + '\n\n' +
        'Apakah Anda YAKIN ingin melanjutkan?\n' +
        '(Semua perubahan setelah backup akan hilang!)'
    );
    
    if (confirmed) {
        const pageLoader = document.getElementById('pageLoader');
        if (pageLoader) {
            pageLoader.style.display = 'flex';
        }
        
        const form = document.getElementById('restoreForm');
        form.action = `/backup/restore/${filename}`;
        form.submit();
    }
}

// Konfirmasi Delete
function confirmDelete(filename) {
    const confirmed = confirm(
        '🗑️ HAPUS BACKUP\n\n' +
        '═══════════════════════════════════\n\n' +
        'Apakah Anda yakin ingin menghapus backup ini?\n\n' +
        'File: ' + filename + '\n\n' +
        '⚠️ Tindakan ini TIDAK DAPAT dibatalkan!\n' +
        'File backup akan dihapus permanen.'
    );
    
    if (confirmed) {
        const form = document.getElementById('deleteForm');
        form.action = `/backup/delete/${filename}`;
        form.submit();
    }
}

// Loading indicator saat submit form
document.getElementById('backupForm')?.addEventListener('submit', function() {
    const pageLoader = document.getElementById('pageLoader');
    if (pageLoader) {
        pageLoader.style.display = 'flex';
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Membuat Backup...';
});

document.getElementById('uploadBackupForm')?.addEventListener('submit', function() {
    const pageLoader = document.getElementById('pageLoader');
    if (pageLoader) {
        pageLoader.style.display = 'flex';
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Uploading...';
});
</script>
@endpush