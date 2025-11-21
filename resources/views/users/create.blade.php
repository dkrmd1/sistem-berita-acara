@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
<div class="container-fluid px-4">
    
    <!-- 1. PAGE HEADER (Updated Style) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    
                    <!-- Bagian Kiri: Icon & Judul -->
                    <div class="d-flex align-items-center">
                        <div class="icon-box-lg bg-company-subtle text-company rounded-circle me-3">
                            <i class="bi bi-person-plus-fill display-6"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-company mb-1">Tambah User Baru</h3>
                            <p class="text-muted mb-0">Buat akun pengguna baru dengan hak akses spesifik</p>
                        </div>
                    </div>
                    
                    <!-- Bagian Kanan: Tombol -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm fw-bold">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- 2. FORM CARD -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
                <div class="card-header bg-gradient-company text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-person me-2"></i> Formulir Data Pengguna</h6>
                </div>
                
                <div class="card-body p-5">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <!-- Group 1: Identitas -->
                        <h6 class="text-company fw-bold text-uppercase mb-3 border-bottom pb-2">1. Identitas Personal</h6>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">NAMA LENGKAP <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-fill text-muted"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 bg-light" value="{{ old('name') }}" placeholder="Nama Lengkap User" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">EMAIL <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope-at-fill text-muted"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 bg-light" value="{{ old('email') }}" placeholder="email@perusahaan.co.id" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">NIP (Opsional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-heading text-muted"></i></span>
                                    <input type="text" name="nip" class="form-control border-start-0 bg-light" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">JABATAN (Opsional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-briefcase-fill text-muted"></i></span>
                                    <input type="text" name="jabatan" class="form-control border-start-0 bg-light" value="{{ old('jabatan') }}" placeholder="Contoh: Staff IT">
                                </div>
                            </div>
                        </div>

                        <!-- Group 2: Hak Akses -->
                        <h6 class="text-company fw-bold text-uppercase mb-3 border-bottom pb-2 mt-5">2. Hak Akses & Role</h6>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">PILIH ROLE <span class="text-danger">*</span></label>
                                <select class="form-select bg-light" id="role" name="role" required>
                                    <option value="">-- Pilih Level Akses --</option>
                                    <option value="cs">CS (Checker)</option>
                                    <option value="group_head">Group Head (Approver)</option>
                                    <option value="direktur_utama">Direktur Utama (Backup)</option>
                                    <option value="direktur">Direktur (Backup)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">STATUS AKUN</label>
                                <select class="form-select bg-light" name="is_active" required>
                                    <option value="1" selected>Aktif (Bisa Login)</option>
                                    <option value="0">Nonaktif (Diblokir)</option>
                                </select>
                            </div>
                            <!-- Dynamic Role Info -->
                            <div class="col-12" id="roleInfo" style="display: none;">
                                <div class="alert bg-info-subtle border-0 border-start border-4 border-info rounded-3 d-flex align-items-start">
                                    <i class="bi bi-info-circle-fill text-info fs-5 me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold text-info-emphasis mb-1">Deskripsi Role:</h6>
                                        <p class="mb-0 small text-muted" id="roleDescription"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Group 3: Keamanan -->
                        <h6 class="text-company fw-bold text-uppercase mb-3 border-bottom pb-2 mt-5">3. Keamanan</h6>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">PASSWORD <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-key-fill text-muted"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 bg-light" placeholder="Min. 8 Karakter" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">ULANGI PASSWORD <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-check-circle-fill text-muted"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control border-start-0 bg-light" placeholder="Ketik ulang password" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-light border rounded-pill px-4 fw-bold">Batal</a>
                            <button type="submit" class="btn btn-company rounded-pill px-5 fw-bold shadow-sm"><i class="bi bi-save-fill me-2"></i> Simpan User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* === BRAND COLORS & VARIABLES === */
    :root {
        --calm-water-blue: #165581; 
        --atmospheric-blue: #29AAE2;
    }

    /* === HEADER STYLES (ICON BOX) === */
    .icon-box-lg { 
        width: 64px; 
        height: 64px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
    }
    .bg-company-subtle { background-color: rgba(22, 85, 129, 0.1); color: var(--calm-water-blue); }
    .text-company { color: var(--calm-water-blue); }

    /* === CARD & LAYOUT === */
    .bg-gradient-company { background: linear-gradient(135deg, #165581 0%, #2d7a9e 100%); }
    .rounded-4 { border-radius: 1rem !important; }
    
    /* === BUTTONS & INPUTS === */
    .btn-company { background-color: #165581; border-color: #165581; color: white; transition: all 0.3s; }
    .btn-company:hover { background-color: #114466; border-color: #114466; transform: translateY(-2px); }
    .form-control:focus, .form-select:focus { border-color: #165581; box-shadow: none; background-color: #fff; }
</style>
@endpush

@push('scripts')
<script>
    // Logic untuk menampilkan deskripsi Role saat dipilih
    document.getElementById('role').addEventListener('change', function() {
        const roleInfo = document.getElementById('roleInfo');
        const roleDesc = document.getElementById('roleDescription');
        const val = this.value;
        const descs = {
            'cs': '<strong>Checker / CS:</strong> Input data nasabah & buat draft BA.',
            'group_head': '<strong>Approver Utama:</strong> Menyetujui Berita Acara.',
            'direktur_utama': '<strong>Backup Approver 1:</strong> Pengganti jika Group Head berhalangan.',
            'direktur': '<strong>Backup Approver 2:</strong> Pengganti level 2.'
        };
        
        if (val && descs[val]) { 
            roleDesc.innerHTML = descs[val]; 
            roleInfo.style.display = 'block'; 
        } else { 
            roleInfo.style.display = 'none'; 
        }
    });
</script>
@endpush