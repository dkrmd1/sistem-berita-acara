@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid px-4">
    
    <!-- 1. PAGE HEADER (CLEAN STYLE) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <!-- Kiri: Judul -->
                        <div class="d-flex align-items-center">
                            <div class="icon-box-lg bg-company-subtle text-company rounded-circle me-3">
                                <i class="bi bi-person-circle display-6"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-company mb-1">Profil Saya</h3>
                                <p class="text-muted mb-0">Kelola informasi pribadi dan keamanan akun Anda.</p>
                            </div>
                        </div>
                        
                        <!-- Kanan: Role Badge -->
                        <div class="d-flex align-items-center bg-light px-4 py-2 rounded-pill border shadow-sm">
                            <div class="bg-info bg-opacity-25 text-info-emphasis rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                <i class="bi bi-shield-check small"></i>
                            </div>
                            <div>
                                <small class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.6rem; line-height: 1;">Role Akses</small>
                                <span class="fw-bold text-company">{{ Auth::user()->getRoleLabel() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <!-- KOLOM KIRI: Edit Profil & Password -->
        <div class="col-lg-7">
            
            <!-- 1. Edit Profil Card -->
            <div class="card shadow-sm border-0 rounded-4 mb-4 card-hover">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                        <!-- Avatar Inisial -->
                        <div class="avatar-profile me-3 shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">{{ Auth::user()->name }}</h5>
                            <small class="text-muted">{{ Auth::user()->email }}</small>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-company-subtle text-company px-3 py-2 rounded-pill">
                                <i class="bi bi-pencil-square me-1"></i> Edit Mode
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-company"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 bg-light ps-0 fw-semibold" value="{{ old('name', Auth::user()->name) }}" required>
                                </div>
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">NIP</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-heading text-company"></i></span>
                                    <input type="text" name="nip" class="form-control border-start-0 bg-light ps-0" value="{{ old('nip', Auth::user()->nip) }}" placeholder="-">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Jabatan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-briefcase text-company"></i></span>
                                    <input type="text" name="jabatan" class="form-control border-start-0 bg-light ps-0" value="{{ old('jabatan', Auth::user()->jabatan) }}" placeholder="-">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-company"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 bg-light ps-0" value="{{ old('email', Auth::user()->email) }}" required>
                                </div>
                                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-company px-4 rounded-pill shadow-sm fw-bold hover-scale">
                                <i class="bi bi-save me-2"></i> Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. Ubah Password Card -->
            <div class="card shadow-sm border-0 rounded-4 mb-4 bg-warning bg-opacity-10 card-hover">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center">
                        <i class="bi bi-shield-lock-fill me-2 text-warning"></i> Ganti Password
                    </h6>
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-key text-muted"></i></span>
                                    <input type="password" name="current_password" class="form-control border-start-0 ps-0" placeholder="Password Lama" required>
                                </div>
                                @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Password Baru" required>
                                </div>
                                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-check2-circle text-muted"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="Ulangi Password" required>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-warning px-4 rounded-pill shadow-sm fw-bold text-dark hover-scale">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: TTD & Tips -->
        <div class="col-lg-5">
            
            <!-- 3. Tanda Tangan Digital Card -->
            <div class="card shadow-sm border-0 rounded-4 mb-4 card-hover">
                <div class="card-header bg-gradient-success text-white py-3 rounded-top-4 border-0">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-pen-fill me-2"></i> Tanda Tangan Digital</h6>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="border-2 border-dashed rounded-4 bg-light position-relative overflow-hidden d-flex align-items-center justify-content-center" 
                             style="height: 180px; border-style: dashed; border-color: #198754;">
                            
                            @if(Auth::user()->ttd_path)
                                <img src="{{ asset('storage/' . str_replace('public/', '', Auth::user()->ttd_path)) }}" 
                                     class="img-fluid p-3 hover-zoom" 
                                     style="max-height: 100%; object-fit: contain;">
                                <span class="badge bg-success position-absolute top-0 end-0 m-3 shadow-sm">Aktif</span>
                            @else
                                <div class="text-center text-muted opacity-50">
                                    <i class="bi bi-file-earmark-x-fill display-4"></i>
                                    <p class="small mt-2 mb-0 fw-bold">Belum ada tanda tangan</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(Auth::user()->ttd_path)
                    <form action="{{ route('profile.delete-ttd') }}" method="POST" class="mb-4 d-grid">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill fw-bold border-2" onclick="return confirm('Yakin ingin menghapus tanda tangan?')">
                            <i class="bi bi-trash-fill me-1"></i> Hapus Tanda Tangan
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('profile.upload-ttd') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="file" name="ttd" class="form-control form-control-sm rounded-3" accept="image/png,image/jpg,image/jpeg" required>
                            <div class="form-text small text-muted"><i class="bi bi-info-circle me-1"></i> Format: JPG/PNG (Max 2MB). Transparan lebih baik.</div>
                            @error('ttd')<div class="text-danger small mt-1 fw-bold">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success rounded-pill shadow-sm fw-bold hover-scale">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload TTD Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 4. Tips Card -->
            <div class="card shadow-sm border-0 rounded-4 bg-gradient-info text-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 p-2 rounded-circle me-3">
                            <i class="bi bi-lightbulb-fill fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Tips Tanda Tangan</h6>
                    </div>
                    <ul class="small mb-0 ps-3 opacity-90" style="line-height: 1.6;">
                        <li>Gunakan pulpen <strong>tinta hitam tebal</strong> di atas kertas putih polos.</li>
                        <li>Foto di tempat dengan <strong>cahaya terang</strong> (hindari bayangan).</li>
                        <li>Gunakan alat <strong>Remove Background</strong> online agar hasil maksimal di dokumen PDF.</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* === VARIABLES === */
    :root {
        --calm-water-blue: #165581;
        --atmospheric-blue: #29AAE2;
        --sincere-yellow: #EFCA18;
    }

    /* === GRADIENTS === */
    .bg-gradient-company { background: linear-gradient(135deg, #165581 0%, #2d7a9e 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #0dcaf0 0%, #3dd5f3 100%); }
    .bg-company-subtle { background-color: rgba(22, 85, 129, 0.1); }

    /* === UTILITIES === */
    .text-company { color: var(--calm-water-blue); }
    .rounded-4 { border-radius: 1rem !important; }
    .icon-box-lg { width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; }
    
    /* === AVATAR PROFILE === */
    .avatar-profile {
        width: 60px; height: 60px;
        background: linear-gradient(135deg, var(--sincere-yellow) 0%, #f7c748 100%);
        color: #165581;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; font-weight: bold;
        border: 3px solid #fff;
    }

    /* === BUTTONS & INPUTS === */
    .btn-company { background-color: #165581; border-color: #165581; color: white; transition: all 0.3s ease; }
    .btn-company:hover { background-color: #114466; border-color: #114466; color: white; }
    
    .form-control:focus { border-color: #29AAE2; box-shadow: none; background-color: #fff; }
    .input-group-text { border-color: #dee2e6; color: #6c757d; }
    .input-group:focus-within .input-group-text { border-color: #29AAE2; color: #165581; background-color: #eef9fe !important; }

    /* === ANIMATIONS === */
    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important; }
    
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.03); }
    
    .hover-zoom { transition: transform 0.3s; }
    .hover-zoom:hover { transform: scale(1.1); }
    
    .border-dashed { border-style: dashed !important; }
</style>
@endpush