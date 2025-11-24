@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4 text-company fw-bold">Pengaturan Sistem</h3>
    <hr>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-sliders me-2"></i> Konfigurasi Berita Acara</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="autoBaSwitch" name="auto_generate_ba" value="1" {{ $autoGenerateBA == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="autoBaSwitch">Aktifkan Auto Generate Nomor BA</label>
                            <div class="form-text text-muted small mt-2">
                                <strong>Jika Aktif (ON):</strong> CS bisa memilih antara Otomatis atau Manual.<br>
                                <strong>Jika Mati (OFF):</strong> CS hanya bisa input Manual (atau Kosong), fitur otomatis dimatikan total.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 mt-3">
                            <i class="bi bi-save me-2"></i> Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection