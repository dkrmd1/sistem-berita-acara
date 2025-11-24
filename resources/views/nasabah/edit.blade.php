@extends('layouts.app')

@section('title', 'Edit Nasabah')

@section('content')
<div class="container-fluid px-4">
    
    <!-- PAGE HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-lg bg-warning-subtle text-warning-emphasis rounded-circle me-3">
                            <i class="bi bi-pencil-square display-6"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-company mb-1">Edit Data Nasabah</h3>
                            <p class="text-muted mb-0">Perbarui informasi identitas nasabah.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('nasabah.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-arrow-left me-2"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM EDIT -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-company text-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-person-lines-fill me-2"></i> Form Edit Nasabah</h5>
                </div>
                <div class="card-body p-5">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                            <ul class="mb-0 fw-semibold">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('nasabah.update', $nasabah->id) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')

                        <!-- NAMA -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Nama Lengkap (Sesuai KTP) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person-fill text-muted"></i></span>
                                <input type="text" name="nama" class="form-control form-control-lg bg-light border-start-0" value="{{ old('nama', $nasabah->nama) }}" required style="text-transform: uppercase;">
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <!-- KTP -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Nomor KTP (16 Digit) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-card-heading text-muted"></i></span>
                                    <input type="text" name="ktp" class="form-control form-control-lg bg-light border-start-0 font-monospace" value="{{ old('ktp', $nasabah->ktp) }}" maxlength="16" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="form-text small">Hanya angka, tanpa spasi/tanda baca.</div>
                            </div>
                            <!-- NPWP -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Nomor NPWP (15 Digit) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-card-list text-muted"></i></span>
                                    <input type="text" name="npwp" class="form-control form-control-lg bg-light border-start-0 font-monospace" value="{{ old('npwp', $nasabah->npwp) }}" maxlength="15" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            <!-- TANGGAL LAHIR -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Tanggal Lahir <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-calendar-event text-muted"></i></span>
                                    <input type="date" name="tanggal_lahir" class="form-control form-control-lg bg-light border-start-0" value="{{ old('tanggal_lahir', $nasabah->tanggal_lahir ? \Carbon\Carbon::parse($nasabah->tanggal_lahir)->format('Y-m-d') : '') }}" required>
                                </div>
                            </div>
                            <!-- NEGARA -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Negara <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-globe-asia-australia text-muted"></i></span>
                                    <select name="negara" class="form-select form-select-lg bg-light border-start-0">
                                        <option value="INDONESIA" {{ old('negara', $nasabah->negara) == 'INDONESIA' ? 'selected' : '' }}>INDONESIA</option>
                                        <option value="ASING" {{ old('negara', $nasabah->negara) != 'INDONESIA' ? 'selected' : '' }}>ASING (LAINNYA)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-company btn-lg rounded-pill shadow-sm fw-bold" id="btnSubmit">
                                <i class="bi bi-save-fill me-2"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    :root { --calm-water-blue: #165581; --atmospheric-blue: #29AAE2; --sincere-yellow: #EFCA18; }
    .text-company { color: var(--calm-water-blue); }
    .bg-gradient-company { background: linear-gradient(135deg, var(--calm-water-blue) 0%, #2d7a9e 100%); }
    .btn-company { background-color: var(--calm-water-blue); border-color: var(--calm-water-blue); color: white; transition: all 0.3s ease; }
    .btn-company:hover { background-color: #114466; border-color: #114466; color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(22, 85, 129, 0.3); }
    .icon-box-lg { width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; }
    .font-monospace { font-family: 'Consolas', 'Monaco', monospace; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert saat submit
    document.getElementById('editForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });
</script>
@endpush