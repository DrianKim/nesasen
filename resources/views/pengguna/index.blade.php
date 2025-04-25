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
                                {{-- foto profil --}}
                                <img src="{{ $profil->foto_profil ? asset('foto_profil/' . $profil->foto_profil) : asset('sbadmin2/img/undraw_profile.svg') }}"
                                    alt="Foto Profil" class="shadow-sm rounded-circle"
                                    style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #f8f9fa;">

                                {{-- upload foto_profil --}}
                                <label for="upload-foto-profil"
                                    class="bottom-0 mb-1 cursor-pointer position-absolute end-0 me-1">
                                    <div class="text-white d-flex align-items-center justify-content-center bg-primary rounded-circle"
                                        style="width: 30px; height: 30px; cursor: pointer;">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <input type="file" id="upload-foto-profil" style="display: none;"
                                        accept="image/jpeg,image/png,image/jpg">
                                </label>
                            </div>
                        </div>
                        <div class="ml-2 col">
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
<script>
    @if ($profil)
    document.getElementById('upload-foto-profil').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const formData = new FormData();
            formData.append('foto_profil', this.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            formData.append('email', '{{ $profil->email ?? '-' }}');
            formData.append('no_hp', '{{ $profil->no_hp ?? '-' }}');
            formData.append('tanggal_lahir', '{{ $profil->tanggal_lahir ?? '-' }}');
            formData.append('jenis_kelamin', '{{ $profil->jenis_kelamin ?? '-' }}');
            formData.append('alamat', '{{ $profil->alamat ?? '-' }}');

            @if ($profil->user->role_id == 4)
                formData.append('nis', '{{ $profil->nis ?? '-' }}');
            @else
                formData.append('nip', '{{ $profil->nip ?? '-' }}');
            @endif

            // Tampilkan loading
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className =
                'position-absolute top-0 start-0 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 rounded-circle';
            loadingOverlay.style.width = '100px';
            loadingOverlay.style.height = '100px';
            loadingOverlay.innerHTML = '<div class="text-white spinner-border" role="status"></div>';
            this.parentElement.parentElement.appendChild(loadingOverlay);

            // Kirim request untuk update foto
            fetch('{{ route('profil.update') }}', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Gagal mengupload foto');
            }).then(data => {
                // Refresh halaman setelah berhasil
                window.location.reload();
            }).catch(error => {
                alert('Gagal mengupload foto: ' + error.message);
                loadingOverlay.remove();
            });
        }
    });
    @endif
</script>
