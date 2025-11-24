@extends('layouts.app')

@section('title', 'Riwayat Login User')

@section('content')
<div class="container-fluid px-4 pb-5">
    
    <!-- 1. PAGE HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-lg bg-company-subtle text-company rounded-circle me-3">
                            <i class="bi bi-clock-history display-6"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-company mb-1">Riwayat Login User</h3>
                            <p class="text-muted mb-0">Memantau aktivitas login dan keamanan sistem</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm disabled">
                            <i class="bi bi-shield-check me-2"></i> Log Aman
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STATISTIK MINI -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-atmospheric card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">Login Hari Ini</p>
                            <h2 class="mb-0 fw-bold text-white">
                                {{ \App\Models\LoginLog::whereDate('login_at', \Carbon\Carbon::today())->count() }}
                            </h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-calendar-check fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-calm-water card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">User Unik Hari Ini</p>
                            <h2 class="mb-0 fw-bold text-white">
                                {{ \App\Models\LoginLog::whereDate('login_at', \Carbon\Carbon::today())->distinct('user_id')->count() }}
                            </h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-people-fill fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-sincere-yellow card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">Admin Login</p>
                            <h2 class="mb-0 fw-bold text-white">
                                {{ \App\Models\LoginLog::whereHas('user', function($q){ $q->where('role', 'admin'); })->whereDate('login_at', \Carbon\Carbon::today())->count() }}
                            </h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-shield-lock-fill fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm border-0 h-100 gradient-dark card-hover rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 small fw-bold text-uppercase">Total Riwayat</p>
                            <h2 class="mb-0 fw-bold text-white">{{ \App\Models\LoginLog::count() }}</h2>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-hdd-stack-fill fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. FILTER & SEARCH -->
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('activity-logs.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <!-- Search -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Pencarian User</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-2 bg-light" 
                                   placeholder="Cari Nama User atau IP Address..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <!-- Filter Role -->
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Filter Role</label>
                        <select name="role" class="form-select bg-light cursor-pointer">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="cs" {{ request('role') == 'cs' ? 'selected' : '' }}>Customer Service</option>
                            <option value="group_head" {{ request('role') == 'group_head' ? 'selected' : '' }}>Group Head</option>
                            <option value="direktur" {{ request('role') == 'direktur' ? 'selected' : '' }}>Direktur</option>
                        </select>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-company flex-grow-1 fw-bold"><i class="bi bi-funnel-fill me-2"></i> Filter</button>
                            <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary px-3" data-bs-toggle="tooltip" title="Reset Filter"><i class="bi bi-arrow-clockwise"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. TABEL LOG -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-company"><i class="bi bi-list-columns-reverse me-2"></i> Data Aktivitas</h6>
            <small class="text-muted">Menampilkan {{ $logs->count() }} data</small>
        </div>
        <div class="card-body p-0">
            @if($logs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%" class="text-center text-secondary small text-uppercase py-3">No</th>
                            <th class="text-secondary small text-uppercase py-3">User Pengguna</th>
                            <th class="text-secondary small text-uppercase py-3">Role</th>
                            <th class="text-secondary small text-uppercase py-3">IP Address</th>
                            <th class="text-secondary small text-uppercase py-3">Perangkat / Browser</th>
                            <th class="text-secondary small text-uppercase py-3">Waktu Login</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $index => $log)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $logs->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-company-light text-company me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        @if($log->user)
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        @else
                                            <i class="bi bi-person-x"></i>
                                        @endif
                                    </div>
                                    <div>
                                        @if($log->user)
                                            <div class="fw-bold text-dark">{{ $log->user->name }}</div>
                                            <small class="text-muted font-monospace" style="font-size: 0.75rem;">{{ $log->user->email }}</small>
                                        @else
                                            <div class="fw-bold text-danger fst-italic">User Terhapus</div>
                                            <small class="text-muted">ID: {{ $log->user_id }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($log->user)
                                    @php
                                        // Mapping Role ke warna yang lebih soft (Subtle)
                                        $roleClass = match($log->user->role) {
                                            'admin' => 'danger',
                                            'cs' => 'success',
                                            'group_head' => 'primary',
                                            'direktur' => 'warning',
                                            'direktur_utama' => 'info',
                                            default => 'secondary'
                                        };
                                        $roleLabel = ucwords(str_replace('_', ' ', $log->user->role));
                                    @endphp
                                    <span class="badge rounded-pill bg-{{ $roleClass }}-subtle text-{{ $roleClass }}-emphasis border border-{{ $roleClass }}-subtle px-3">
                                        {{ $roleLabel }}
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-secondary bg-opacity-25 text-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="font-monospace badge bg-light text-dark border fw-bold text-start">
                                    {{ $log->ip_address }}
                                </span>
                            </td>
                            <td>
                                <span class="browser-info text-muted small" data-bs-toggle="tooltip" title="{{ $log->user_agent }}">
                                    <i class="bi bi-laptop me-1"></i>
                                    {{ \Illuminate\Support\Str::limit($log->user_agent, 40) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-dark small">
                                        {{ $log->login_at->translatedFormat('d M Y') }}
                                    </span>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $log->login_at->format('H:i:s') }} WIB
                                    </small>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                <div><small class="text-muted fw-semibold">Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }} dari {{ $logs->total() }} data</small></div>
                <div>{{ $logs->links() }}</div>
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-3 opacity-25"><i class="bi bi-clock-history display-1 text-secondary"></i></div>
                <h5 class="text-muted fw-semibold">Belum Ada Riwayat Login</h5>
                <p class="text-muted small mb-4">Aktivitas login akan muncul di sini setelah user masuk ke sistem.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Menggunakan Variabel Global dari Layouts.app */
    .text-company { color: var(--calm-water-blue); }
    .bg-company-subtle { background-color: rgba(64, 107, 133, 0.1); color: var(--calm-water-blue); }
    .bg-company-light { background-color: rgba(64, 107, 133, 0.05); }
    
    /* Gradients (Disesuaikan dengan Brand bjb) */
    .gradient-calm-water { 
        background: linear-gradient(135deg, var(--calm-water-blue) 0%, var(--calm-water-blue-dark) 100%); 
        color: white; 
    }
    .gradient-atmospheric { 
        background: linear-gradient(135deg, var(--atmospheric-blue) 0%, #0d6efd 100%); 
        color: white; 
    }
    .gradient-sincere-yellow { 
        background: linear-gradient(135deg, var(--sincere-yellow) 0%, #e6d400 100%); 
        color: var(--calm-water-blue-dark); /* Text gelap agar kontras */
    }
    .gradient-sincere-yellow h2, 
    .gradient-sincere-yellow p, 
    .gradient-sincere-yellow i {
        color: var(--calm-water-blue-dark) !important;
    }
    .gradient-dark {
        background: linear-gradient(135deg, #343a40 0%, #212529 100%);
        color: white;
    }
    
    /* === COMPONENTS === */
    .rounded-4 { border-radius: 1rem !important; }
    .font-monospace { font-family: 'Consolas', 'Monaco', monospace; }
    .icon-box-lg { width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; }
    .cursor-pointer { cursor: pointer; }
    
    /* Card Hover Effect */
    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }

    /* Buttons */
    .btn-company { 
        background-color: var(--calm-water-blue); 
        border-color: var(--calm-water-blue); 
        color: white; 
        transition: all 0.3s ease; 
    }
    .btn-company:hover { 
        background-color: var(--calm-water-blue-dark); 
        border-color: var(--calm-water-blue-dark); 
        color: white; 
        transform: translateY(-2px); 
        box-shadow: 0 4px 12px rgba(64, 107, 133, 0.3); 
    }

    /* Table Header */
    .table thead.bg-light th { background-color: #f8f9fa; font-weight: 600; letter-spacing: 0.5px; }

    /* Browser Info Helper */
    .browser-info {
        display: inline-block;
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
        cursor: help;
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize Tooltips untuk User Agent yang panjang
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush