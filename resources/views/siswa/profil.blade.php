@extends('siswa.layouts.app')
@php
    $nama = explode(' ', Auth::user()->siswa->nama);
    $namaPendek = implode(' ', array_slice($nama, 0, 1));
@endphp
@section('content')
    <main>
        <h2 class="section-title-form">Profil Anda</h2>

        <div class="profile-wrapper">
            <!-- Header -->
            <div class="profile-header">
                <div class="profile-main">
                    <div class="profile-photo-lg">
                        <img src="{{ asset('assets/img/smeapng.png') }}" alt="Foto Profil" />
                        {{-- <span class="edit-photo-icon">
                            <i class="material-icons-sharp">photo_camera</i>
                        </span> --}}
                    </div>
                    <div class="profile-info">
                        <h2>{{ $siswa->nama }}</h2>
                        <p class="role-label">Siswa</p>
                    </div>
                </div>
                @include('siswa.modal.edit-profil')
                <button class="btn-edit-profile" onclick="openModal('modalEditProfilSiswa')">
                    <i class="material-icons-sharp">edit</i> Edit Profil
                </button>
            </div>

            <!-- Tab Navigasi -->
            <div class="profile-tabs">
                <button class="tab active">
                    <i class="material-icons-sharp">person</i> Informasi
                </button>
                {{-- <button class="tab">
                    <i class="material-icons-sharp">insights</i> Aktivitas
                </button>
                <button class="tab">
                    <i class="material-icons-sharp">settings</i> Pengaturan
                </button> --}}
            </div>

            <!-- Konten Informasi -->
            <div class="profile-info-card">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="material-icons-sharp">alternate_email</i>
                    </div>
                    <div>
                        <p class="info-label">Username</p>
                        <p class="info-value">{{ $user->username }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="material-icons-sharp">mail</i>
                    </div>
                    <div>
                        <p class="info-label">Email</p>
                        <p class="info-value">{{ $siswa->email ?? '-' }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="material-icons-sharp">badge</i>
                    </div>
                    <div>
                        <p class="info-label">NISN</p>
                        <p class="info-value">{{ $siswa->nisn ?? '-' }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="material-icons-sharp">badge</i>
                    </div>
                    <div>
                        <p class="info-label">NIS</p>
                        <p class="info-value">{{ $siswa->nis ?? '-' }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="material-icons-sharp">call</i>
                    </div>
                    <div>
                        <p class="info-label">No HP</p>
                        <p class="info-value">{{ $siswa->no_hp ?? '-' }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="material-icons-sharp">transgender</i>
                    </div>
                    <div>
                        <p class="info-label">Jenis Kelamin</p>
                        <p class="info-value">{{ $siswa->jenis_kelamin ?? '-' }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="material-icons-sharp">event</i>
                    </div>
                    <div>
                        <p class="info-label">Tanggal Lahir</p>
                        <p class="info-value">{{ $siswa->tanggal_lahir ?? '-' }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="material-icons-sharp">school</i>
                    </div>
                    <div>
                        <p class="info-label">Kelas</p>
                        @if ($siswa->kelas)
                            <p class="info-value">
                                {{ $siswa->kelas->tingkat }} {{ $siswa->kelas->jurusan->kode_jurusan }}
                                {{ $siswa->kelas->no_kelas }}
                            </p>
                            <button type="button" onclick="openModal('modalPilihKelas')"
                                style="font-size: 0.8rem; color: #f39c12; background: none; border: none; padding: 0; text-decoration: underline; cursor: pointer; margin-top: 4px;">
                                Ganti kelas?
                            </button>
                        @else
                            <p class="info-value text-warning">
                                Belum memilih kelas
                            </p>
                            <button type="button" onclick="openModal('modalPilihKelas')"
                                style="font-size: 0.8rem; color: #f39c12; background: none; border: none; padding: 0; text-decoration: underline; cursor: pointer; margin-top: 4px;">
                                Pilih sekarang
                            </button>
                        @endif
                    </div>
                </div>
                @include('siswa.modal.pilih-kelas')
                <div class="info-item full">
                    <div class="info-icon">
                        <i class="material-icons-sharp">location_on</i>
                    </div>
                    <div>
                        <p class="info-label">Alamat</p>
                        <p class="info-value">{{ $siswa->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="statistik-siswa">
            <h4>Statistik Siswa</h4>
            <div class="statistik-box">
                <div class="statistik-item">
                    <h5>{{ $hadir }} Hari</h5>
                    <small>Hadir Bulan Ini</small>
                </div>
                <div class="statistik-item red">
                    <h5>{{ $izin }} Hari</h5>
                    <small>Izin/Sakit</small>
                </div>
            </div>
        </div>
    </main>
    <!-- End of Main Content -->

    <!-- Right Section -->
    <div class="right-section">
        <div class="nav">
            <button id="menu-btn">
                <span class="material-icons-sharp"> menu </span>
            </button>

            <div class="date-today">
                <span id="tanggal-hari-ini"></span>
            </div>

            <div class="dark-mode">
                <span class="material-icons-sharp active"> light_mode </span>
                <span class="material-icons-sharp"> dark_mode </span>
            </div>

            <div class="profile">
                <div class="info">
                    <p>Hallo, <b>{{ $namaPendek }}</b></p>
                    <small class="text-muted">Siswa</small>
                </div>
                <div class="profile-photo">
                    <img src="{{ asset('assets/img/smeapng.png') }}" />
                </div>
            </div>
        </div>

        <!-- End of Nav -->

        <div class="news">
            <iframe src="https://www.instagram.com/p/DJWiNpzSK2i/embed" width="100%" height="335" frameborder="0"
                scrolling="no" allowtransparency="true">
            </iframe>
        </div>

        <div class="reminders hide">
            <div class="header">
                <h2>Reminders</h2>
            </div>

            <div class="notification">
                <div class="icon">
                    <span class="material-icons-sharp"> mosque </span>
                </div>
                <div class="content">
                    <div class="info">
                        <h3>Sholat Ashar</h3>
                        <small class="text_muted"> 15:10 - 15:30 </small>
                    </div>
                    <span class="material-icons-sharp"> more_vert </span>
                </div>
            </div>

            @include('siswa.modal.reminder')
            <div class="notification add-reminder">
                <div onclick="openReminderModal()" style="cursor: pointer;">
                    <span class="material-icons-sharp">add</span>
                    <h3>Add Reminder</h3>
                </div>
            </div>
        </div>
    </div>
@endsection

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

@if ($errors->any())
    <script>
        openModal();
    </script>
@endif

<script>
    function openModal(id = "modalEditProfilSiswa") {
        const modal = document.getElementById(id);
        if (modal) {
            document.body.classList.add('modal-open');
            modal.style.display = 'block';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);

            // Optional: auto focus input kalau ada
            const input = modal.querySelector('input[type="text"]');
            if (input) input.focus();
        }
    }

    function closeModal(id = "modalEditProfilSiswa") {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // ESC key buat nutup modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    closeModal(modal.id);
                });
            }
        });

        // Klik di luar modal content buat nutup
        document.addEventListener('click', function(e) {
            document.querySelectorAll('.modal.show').forEach(modal => {
                if (e.target === modal) {
                    closeModal(modal.id);
                }
            });
        });
    });
</script>
