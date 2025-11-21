@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div>
            <h2 class="mb-1 fw-bold" style="color: #165581;"><i class="bi bi-person-circle me-2"></i> Detail User</h2>
            <p class="text-muted mb-0">Informasi lengkap profil dan aktivitas pengguna</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold"><i class="bi bi-arrow-left me-2"></i> Kembali</a>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Profil Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden text-center">
                <div class="bg-gradient-company py-5">
                    <div class="d-inline-block position-relative mt-2">
                        <div class="bg-white p-1 rounded-circle shadow-lg">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-company" style="width: 110px; height: 110px; font-size: 3rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-2 {{ $user->is_active ? 'bg-success' : 'bg-secondary' }} border border-white rounded-circle shadow-sm"></span>
                    </div>
                </div>
                <div class="card-body p-4 pt-2">
                    <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill mb-3">{{ $user->getRoleLabel() }}</span>
                    <hr class="opacity-25">
                    <div class="row text-start small">
                        <div class="col-6 border-end">
                            <div class="text-muted mb-1">NIP</div>
                            <div class="fw-bold text-dark">{{ $user->nip ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted mb-1">Bergabung</div>
                            <div class="fw-bold text-dark">{{ $user->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Tanda Tangan -->
            <div class="card shadow-sm border-0 rounded-4 mt-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-company"><i class="bi bi-pen-fill me-2"></i> Tanda Tangan Digital</h6>
                </div>
                <div class="card-body text-center p-4">
                    @if($user->ttd_path)
                        <div class="p-3 border border-dashed rounded bg-light mb-3">
                            <img src="{{ Storage::url($user->ttd_path) }}" class="img-fluid" style="max-height: 100px;">
                        </div>
                        <span class="badge bg-success rounded-pill"><i class="bi bi-check-lg me-1"></i> Terverifikasi</span>
                    @else
                        <div class="py-4 opacity-50">
                            <i class="bi bi-file-earmark-x display-4 text-secondary"></i>
                            <p class="text-muted mt-2 small">Belum ada Tanda Tangan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail Info -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-company"><i class="bi bi-info-circle-fill me-2"></i> Informasi Lengkap</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase mb-1">JABATAN</label>
                            <div class="fs-6 text-dark border-bottom pb-2">{{ $user->jabatan ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase mb-1">STATUS AKUN</label>
                            <div class="fs-6 text-dark border-bottom pb-2">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase mb-1">TERAKHIR UPDATE</label>
                            <div class="fs-6 text-dark border-bottom pb-2">{{ $user->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Aktivitas (Placeholder logic) -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 bg-info-subtle h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-white p-3 rounded-circle text-info shadow-sm me-3"><i class="bi bi-activity fs-3"></i></div>
                            <div>
                                <h5 class="mb-0 fw-bold text-info-emphasis">Aktivitas</h5>
                                <small class="text-muted">Statistik User</small>
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
<style>
    .bg-gradient-company { background: linear-gradient(135deg, #165581 0%, #2d7a9e 100%); }
    .text-company { color: #165581; }
    .rounded-4 { border-radius: 1rem !important; }
</style>
@endpush