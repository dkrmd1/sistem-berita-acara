@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="text-center">

        <div class="mb-4">
            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
        </div>

        <h1 class="fw-bold text-primary" style="font-size: 4rem;">404</h1>
        <h4 class="mt-3 text-secondary">Halaman Tidak Ditemukan</h4>

        <p class="text-muted mb-4">
            Maaf, halaman yang Anda cari tidak tersedia atau mungkin telah dipindahkan.
        </p>

        <a href="{{ route('dashboard') }}" class="btn btn-primary px-4 py-2">
            <i class="bi bi-house-door"></i> Kembali ke Dashboard
        </a>

    </div>
</div>
@endsection
