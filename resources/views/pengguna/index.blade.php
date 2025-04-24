@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="border-0 shadow card rounded-4">
            <div class="p-4 card-body">
                @if ($profil)
                    <!-- Header Profil -->
                    <div class="mb-4 row align-items-center">
                        <div class="col-auto">
                            <div class="position-relative">
                                <img src="{{ $profil->foto_profil . asset('sbadmin2\img\undraw_profile.svg') }}"
                                    alt="Foto Profil" class="shadow-sm rounded-circle"
                                    style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #f8f9fa;">
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-1 fw-bold">{{ $profil->nama }}</h4>
                            <span class="mb-2 badge bg-light text-secondary">{{ $profil->user->role->deskripsi }}</span>
                        </div>
                        <div class="col-auto">
                            <button class="px-4 btn btn-primary rounded-pill" data-toggle="modal"
                                data-target="#editProfilModal">
                                <i class="fas fa-edit me-2"></i>Edit Profil
                            </button>
                            @include('pengguna.modal')
                        </div>
                    </div>

                    <!-- Navigasi Tab -->
                    <ul class="mb-4 nav nav-pills" id="profilTab" role="tablist">
                        <li class="nav-item me-2" role="presentation">
                            <button class="px-4 nav-link active" id="info-tab" data-toggle="tab" data-target="#info"
                                type="button" role="tab">
                                <i class="mr-2 fas fa-user-circle me-2"></i>Informasi
                            </button>
                        </li>
                        <li class="nav-item me-2" role="presentation">
                            <button class="px-4 nav-link" id="aktivitas-tab" data-toggle="tab" data-target="#aktivitas"
                                type="button" role="tab">
                                <i class="mr-2 fas fa-chart-line me-2"></i>Aktivitas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="px-4 nav-link" id="pengaturan-tab" data-toggle="tab" data-target="#pengaturan"
                                type="button" role="tab">
                                <i class="mr-2 fas fa-cog me-2"></i>Pengaturan
                            </button>
                        </li>
                    </ul>
                    <div class="p-3 tab-content bg-light rounded-3" id="profilTabContent">
                        @include('pengguna.informasi')
                        @include('pengguna.aktivitas')
                        @include('pengguna.pengaturan')
                    </div>
                @else
                    <div class="py-5 text-center">
                        <i class="mb-3 fas fa-user-circle fa-5x text-primary"></i>
                        <h3>Halo <strong>Admin</strong></h3>
                        <p class="text-muted">Selamat datang di panel administrasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
