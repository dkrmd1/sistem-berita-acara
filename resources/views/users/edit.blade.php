@extends('layouts.app')

@section('title', 'Edit User')

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
                            <i class="bi bi-pencil-square display-6"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-company mb-1">Edit User</h3>
                            <p class="text-muted mb-0">Perbarui data pengguna: <span class="fw-bold text-dark">{{ $user->name }}</span></p>
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

    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <!-- CARD EDIT DATA UTAMA -->
            <div class="card shadow-lg border-0 rounded-4 mb-5 overflow-hidden">
                <div class="card-header bg-gradient-company text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person-gear me-2"></i> Form Edit Data</h6>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf @method('PUT')

                        <h6 class="text-company fw-bold text-uppercase mb-3 border-bottom pb-2">Identitas & Akses</h6>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">NAMA LENGKAP <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-fill text-muted"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 bg-light" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">EMAIL <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope-at-fill text-muted"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 bg-light" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">NIP</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-heading text-muted"></i></span>
                                    <input type="text" name="nip" class="form-control border-start-0 bg-light" value="{{ old('nip', $user->nip) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">JABATAN</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-briefcase-fill text-muted"></i></span>
                                    <input type="text" name="jabatan" class="form-control border-start-0 bg-light" value="{{ old('jabatan', $user->jabatan) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">ROLE <span class="text-danger">*</span></label>
                                <select class="form-select bg-light" name="role" required>
                                    <option value="cs" {{ $user->role == 'cs' ? 'selected' : '' }}>CS (Checker)</option>
                                    <option value="group_head" {{ $user->role == 'group_head' ? 'selected' : '' }}>Group Head</option>
                                    <option value="direktur_utama" {{ $user->role == 'direktur_utama' ? 'selected' : '' }}>Direktur Utama</option>
                                    <option value="direktur" {{ $user->role == 'direktur' ? 'selected' : '' }}>Direktur</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">STATUS</label>
                                <select class="form-select bg-light" name="is_active" required>
                                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <button type="submit" class="btn btn-company rounded-pill px-5 fw-bold shadow-sm"><i class="bi bi-check-lg me-2"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- CARD RESET PASSWORD (DIPISAH) -->
            <div class="card shadow-sm border-0 rounded-4 mb-5 bg-danger-subtle">
                <div class="card-header bg-danger text-white py-3 rounded-top-4">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill me-2"></i> Reset Password User</h6>
                </div>
                <div class="card-body p-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-white p-3 rounded-circle text-danger shadow-sm me-3"><i class="bi bi-exclamation-triangle-fill fs-4"></i></div>
                        <div>
                            <h6 class="fw-bold text-danger mb-1">Area Sensitif</h6>
                            <p class="mb-0 small text-secondary">Gunakan form ini hanya jika user lupa password. Password baru akan menimpa yang lama.</p>
                        </div>
                    </div>

                    <form action="{{ route('users.reset-password', $user->id) }}" method="POST" id="resetPassForm">
                        @csrf @method('PATCH')
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">PASSWORD BARU</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-danger"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">KONFIRMASI PASSWORD</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-danger"><i class="bi bi-check-circle"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control border-start-0" required>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" id="btnReset" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm"><i class="bi bi-arrow-counterclockwise me-2"></i> Reset Password</button>
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
    .btn-company:hover { background-color: #114466; }
    
    .form-control:focus, .form-select:focus { border-color: #165581; box-shadow: none; background-color: #fff; }

    /* === SWEETALERT === */
    .custom-swal-popup { border-radius: 20px !important; font-family: 'Segoe UI', sans-serif; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btnReset').addEventListener('click', function() {
        Swal.fire({
            title: 'Reset Password?', text: "Password lama akan diganti dengan yang baru.", icon: 'warning',
            showCancelButton: true, confirmButtonText: 'Ya, Reset', cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545', customClass: { popup: 'custom-swal-popup' }
        }).then((res) => { if(res.isConfirmed) document.getElementById('resetPassForm').submit(); });
    });
</script>
@endpush