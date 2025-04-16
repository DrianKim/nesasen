@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column justify-content-center align-items-center position-relative"
        style="height: 100vh; overflow: hidden; background-color: #f8f9fa;">
        <!-- Background elements -->
        {{-- <div class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; opacity: 0.05; z-index: 0;">
            <div class="position-absolute"
                style="font-size: 400px; font-weight: 900; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-15deg); color: #dc3545;">
                404</div>
        </div> --}}

        <!-- Main content -->
        <div class="px-3 text-center z-1">
            <h1 class="mb-0 display-1 fw-bold text-danger" style="text-shadow: 3px 3px 0 rgba(0,0,0,0.1);">404</h1>

            {{-- <div class="mt-3 mb-4">
                <img src="{{ asset('img/undraw_page_not_found_re_e9o6.svg') }}" alt="Page Not Found" class="img-fluid"
                    style="max-width: 300px;">
            </div> --}}

            <h2 class="mb-3 fs-1 fw-bold">Waduh, Nyasar!</h2>
            <p class="mb-2 fs-4">Halaman yang Anda cari tidak ditemukan.</p>
            <p class="mb-4 text-muted">Mungkin sudah dihapus, atau anda tidak mempunyai akses.</p>

            <div class="gap-3 mb-3 d-flex flex-column flex-md-row justify-content-center">
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-home"></i> Kembali ke Dashboard
                </a>
            </div>

            <p class="mt-3 text-muted">Butuh bantuan? <a href="#" class="text-decoration-none">Hubungi Admin</a></p>
        </div>
    </div>
    @php $title = '404 - Halaman Tidak Ditemukan'; @endphp
@endsection
