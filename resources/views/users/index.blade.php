@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container-fluid px-4">
    
    <!-- 1. PAGE HEADER (STYLE DATA NASABAH) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    
                    <!-- Bagian Kiri: Icon & Judul -->
                    <div class="d-flex align-items-center">
                        <div class="icon-box-lg bg-company-subtle text-company rounded-circle me-3">
                            <i class="bi bi-people-fill display-6"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-company mb-1">Manajemen User</h3>
                            <p class="text-muted mb-0">Kelola data pengguna, hak akses, dan struktur organisasi</p>
                        </div>
                    </div>
                    
                    <!-- Bagian Kanan: Tombol -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('users.create') }}" class="btn btn-company rounded-pill px-4 shadow-sm fw-bold">
                            <i class="bi bi-plus-lg me-2"></i> Tambah User Baru
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- 2. FILTER & SEARCH CARD -->
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="row g-3 align-items-end">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0 bg-light" 
                                   placeholder="Nama, Email, atau NIP..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <!-- Filter Role -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">Filter Role</label>
                        <select name="role" class="form-select bg-light cursor-pointer">
                            <option value="">Semua Role</option>
                            <option value="cs" {{ request('role') == 'cs' ? 'selected' : '' }}>Customer Service</option>
                            <option value="group_head" {{ request('role') == 'group_head' ? 'selected' : '' }}>Group Head</option>
                            <option value="direktur_utama" {{ request('role') == 'direktur_utama' ? 'selected' : '' }}>Direktur Utama</option>
                            <option value="direktur" {{ request('role') == 'direktur' ? 'selected' : '' }}>Direktur</option>
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">Status Akun</label>
                        <select name="status" class="form-select bg-light cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-company flex-grow-1 rounded-pill fw-bold">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-circle" data-bs-toggle="tooltip" title="Reset Filter">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. USER LIST TABLE -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-company"><i class="bi bi-list-ul me-2"></i> Daftar Pengguna</h6>
            <span class="badge bg-light text-dark border">{{ $users->total() }} Data</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%" class="text-center py-3 text-secondary small text-uppercase">No</th>
                            <th class="py-3 text-secondary small text-uppercase">Nama & Email</th>
                            <th class="py-3 text-secondary small text-uppercase">NIP</th>
                            <th class="py-3 text-secondary small text-uppercase">Role & Jabatan</th>
                            <th width="10%" class="text-center py-3 text-secondary small text-uppercase">TTD</th>
                            <th width="10%" class="text-center py-3 text-secondary small text-uppercase">Status</th>
                            <th width="15%" class="text-center py-3 text-secondary small text-uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-company-subtle text-company me-3 rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        <small class="text-muted"><i class="bi bi-envelope me-1"></i> {{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->nip)
                                    <span class="font-monospace badge bg-light text-dark border">{{ $user->nip }}</span>
                                @else
                                    <span class="text-muted small fst-italic">-</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    @php
                                        $roleClass = match($user->role) {
                                            'cs' => 'bg-info text-dark',
                                            'group_head' => 'bg-primary',
                                            'direktur_utama' => 'bg-danger',
                                            'direktur' => 'bg-warning text-dark',
                                            'admin' => 'bg-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $roleClass }} mb-1 rounded-1">{{ $user->getRoleLabel() }}</span>
                                    <div class="small text-muted">{{ $user->jabatan ?? '-' }}</div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($user->ttd_path)
                                    <i class="bi bi-check-circle-fill text-success fs-5" data-bs-toggle="tooltip" title="TTD Tersedia"></i>
                                @else
                                    <i class="bi bi-x-circle-fill text-danger fs-5 opacity-50" data-bs-toggle="tooltip" title="Belum Upload TTD"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($user->is_active)
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-3">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-1">
                                    <!-- Detail -->
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-light text-info border" data-bs-toggle="tooltip" title="Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    
                                    @if($user->id !== auth()->id())
                                        <!-- Edit -->
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light text-primary border" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <!-- Toggle Status -->
                                        <button type="button" 
                                                class="btn btn-sm {{ $user->is_active ? 'btn-light text-warning border' : 'btn-light text-success border' }} btn-toggle-status"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-status="{{ $user->is_active ? 'nonaktifkan' : 'aktifkan' }}"
                                                data-bs-toggle="tooltip"
                                                title="{{ $user->is_active ? 'Nonaktifkan User' : 'Aktifkan User' }}">
                                            <i class="bi bi-{{ $user->is_active ? 'slash-circle' : 'check-circle' }}"></i>
                                        </button>
                                        <form id="toggle-form-{{ $user->id }}" action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                                        
                                        <!-- Delete -->
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-danger border btn-delete"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-bs-toggle="tooltip"
                                                title="Hapus Permanen">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                                    @else
                                        <span class="btn btn-sm btn-light border disabled text-muted" title="Akun Anda"><i class="bi bi-person-fill"></i></span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="opacity-50 mb-3"><i class="bi bi-people text-secondary display-4"></i></div>
                                <h6 class="text-muted">Tidak ada user ditemukan</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                <div><small class="text-muted fw-semibold">Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} data</small></div>
                <div>{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* === BRAND COLORS & VARIABLES === */
    :root {
        --calm-water-blue: #165581; 
        --atmospheric-blue: #29AAE2;
    }

    /* === UTILS === */
    .rounded-4 { border-radius: 1rem !important; }
    .font-monospace { font-family: 'Consolas', monospace; }
    
    /* === HEADER STYLES (ICON BOX) === */
    .icon-box-lg { 
        width: 64px; 
        height: 64px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
    }
    
    /* === COLORS === */
    .text-company { color: var(--calm-water-blue); }
    .bg-company-subtle { background-color: rgba(22, 85, 129, 0.1); color: var(--calm-water-blue); }

    /* === BUTTONS === */
    .btn-company { 
        background-color: var(--calm-water-blue); 
        border-color: var(--calm-water-blue); 
        color: white; 
        transition: all 0.3s ease; 
    }
    .btn-company:hover { 
        background-color: #114466; 
        border-color: #114466; 
        transform: translateY(-2px); 
        box-shadow: 0 4px 12px rgba(22, 85, 129, 0.3);
    }

    /* === TABLE & FORMS === */
    .table thead.bg-light th { background-color: #f8f9fa; letter-spacing: 0.5px; }
    .form-control:focus, .form-select:focus { border-color: var(--calm-water-blue); box-shadow: none; }
    
    /* === SWEETALERT === */
    .custom-swal-popup { border-radius: 24px !important; padding-top: 0 !important; font-family: 'Segoe UI', sans-serif; }
    .custom-swal-popup::before { content: ''; display: block; height: 6px; width: 100%; background: linear-gradient(90deg, #165581, #29AAE2); }
    .swal2-confirm-btn { background: #165581 !important; border-radius: 50px !important; padding: 10px 30px !important; }
    .swal2-cancel-btn { background: #f8f9fa !important; color: #6c757d !important; border-radius: 50px !important; padding: 10px 24px !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Init Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })

    // Delete Logic
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            Swal.fire({
                icon: 'warning', title: 'Hapus User?',
                html: `Hapus user <b>${name}</b> secara permanen?`,
                showCancelButton: true, confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal',
                customClass: { popup: 'custom-swal-popup', confirmButton: 'swal2-confirm-btn bg-danger', cancelButton: 'swal2-cancel-btn' }
            }).then((result) => { if (result.isConfirmed) document.getElementById(`delete-form-${id}`).submit(); });
        });
    });

    // Toggle Status Logic
    document.querySelectorAll('.btn-toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const action = this.getAttribute('data-status');
            Swal.fire({
                icon: 'question', title: 'Ubah Status?',
                html: `Yakin ingin <b>${action}</b> user <b>${name}</b>?`,
                showCancelButton: true, confirmButtonText: 'Ya, Lanjutkan', cancelButtonText: 'Batal',
                customClass: { popup: 'custom-swal-popup', confirmButton: 'swal2-confirm-btn', cancelButton: 'swal2-cancel-btn' }
            }).then((result) => { if (result.isConfirmed) document.getElementById(`toggle-form-${id}`).submit(); });
        });
    });
    
    @if(session('success'))
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 }).fire({ icon: 'success', title: '{{ session("success") }}' });
    @endif
</script>
@endpush