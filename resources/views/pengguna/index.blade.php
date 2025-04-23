@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="shadow-sm card rounded-4">
            <div class="card-body">
                @if ($profil)
                <div class="mb-4 d-flex align-items-center">
                    <img src="{{ asset('sbadmin2\img\undraw_profile.svg' . auth()->user()->foto_profil) }}" alt="Foto Profil"
                        class="rounded-circle me-3" width="80" height="80">
                    <div class="ml-3">
                        <h5 class="mb-0">{{ $profil->nama }}</h5>
                        <small class="text-muted">{{ $profil->user->role->deskripsi }}</small>
                    </div>
                    <div class="ml-3 ms-auto">
                        <button class="btn btn-outline-primary" data-toggle="modal"
                            data-target="#editProfilModal">Edit Profil</button>
                    </div>
                    @include('pengguna.modal')
                </div>

                <ul class="nav nav-tabs" id="profilTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                            type="button" role="tab">Informasi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="aktivitas-tab" data-bs-toggle="tab" data-bs-target="#aktivitas"
                            type="button" role="tab">Aktivitas</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pengaturan-tab" data-bs-toggle="tab" data-bs-target="#pengaturan"
                            type="button" role="tab">Pengaturan</button>
                    </li>
                </ul>
                <div class="pt-3 tab-content" id="profilTabContent">
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <p><strong>Username:</strong> {{ $profil->user->username }}</p>
                        <p><strong>Email:</strong> {{ $profil->email }}</p>
                        <p><strong>No HP:</strong> {{ $profil->no_hp }}</p>
                        <p><strong>Jenis Kelamin:</strong> {{ $profil->jenis_kelamin }}</p>
                        <p><strong>Alamat:</strong> {{ $profil->alamat }}</p>
                    </div>
                    <div class="tab-pane fade" id="aktivitas" role="tabpanel">
                        <p>Belum ada aktivitas terbaru.</p>
                    </div>
                    <div class="tab-pane fade" id="pengaturan" role="tabpanel">
                        <p>Pengaturan akun akan tersedia di sini.</p>
                    </div>
                </div>
                @else
                <h3>Halo <Strong>Admin</Strong></h3>
                @endif
            </div>
        </div>
    </div>
@endsection
