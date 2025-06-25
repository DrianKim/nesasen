@extends('siswa.layouts.app')
@php
    $nama = explode(' ', Auth::user()->siswa->nama);
    $namaPendek = implode(' ', array_slice($nama, 0, 1));
@endphp
@section('content')
    <main>
        <h2 class="section-title-feature">Fitur Siswa</h2>
        <div class="status-presensi">
            <div class="check-item checked" id="checkInBerandaSiswa">
                <span class="material-icons-sharp">check_circle</span>
                <span>Check In --:--</span>
            </div>
            <div class="check-item unchecked" id="checkOutBerandaSiswa">
                <span class="material-icons-sharp">cancel</span>
                <span>Check Out --:--</span>
            </div>
        </div>

        <!-- Fitur Siswa Section -->
        <div class="feature-card">
            <div class="icon-group">
                <a href="{{ route('siswa.presensi') }}"
                    class="icon-item {{ request()->routeIs('siswa.presensi') ? 'active' : '' }}">
                    <span class="material-icons-sharp">work_history</span>
                    <p>Presensi</p>
                </a>
                <a href="{{ route('siswa.izin') }}"
                    class="icon-item {{ request()->routeIs('siswa.izin') ? 'active' : '' }}">
                    <span class="material-icons-sharp">forward_to_inbox</span>
                    <p>Izin</p>
                </a>
                <a href="{{ route('siswa.jadwal') }}"
                    class="icon-item {{ request()->routeIs('siswa.jadwal') ? 'active' : '' }}">
                    <span class="material-icons-sharp">today</span>
                    <p>Jadwal</p>
                </a>
                {{-- <a href="#" class="icon-item">
                    <span class="material-icons-sharp">cast_for_education</span>
                    <p>KelasKu</p>
                </a>
                <a href="#" class="icon-item">
                    <span class="material-icons-sharp">history</span>
                    <p>History</p>
                </a>
                <a href="#" class="icon-item">
                    <span class="material-icons-sharp">grading</span>
                    <p>Nilai</p>
                </a> --}}
                <!-- Tambah fitur lain tinggal duplikat -->
            </div>
        </div>
        <!-- End of Fitur Siswa Section -->

        <!-- Pengumuman -->
        <div class="pengumuman">
            <h2>Pengumuman</h2>
            @forelse($pengumumen as $p)
                <div class="announcement">
                    <h4>{{ $p->judul }}</h4>
                    <small>Dibuat pada
                        <i>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</i>
                    </small>
                    <p>{{ $p->isi }}</p>
                    <small>Ditujukan untuk: <b>{{ ucfirst($p->ditujukan_untuk) }}</b></small><br />
                    <small>Habis:
                        {{ \Carbon\Carbon::parse($p->kadaluarsa_sampai)->translatedFormat('d F Y') }}
                    </small>
                </div>
            @empty
                <div class="announcement empty-state">
                    <img src="{{ asset('assets/img/no-data.png') }}" alt="Tidak ada pengumuman" class="empty-img">
                    <h4 class="empty-title">Belum Ada Pengumuman untuk saat ini</h4>
                    <p class="empty-subtitle">
                        {{ collect([
                            'Tetap semangat, masa depan cerah menantimu!',
                            'Jangan menyerah, setiap hari adalah kesempatan baru!',
                            'Belajar hari ini, sukses esok hari.',
                            'Langkah kecil hari ini, hasil besar nanti.',
                            'Kamu hebat, teruslah berusaha!',
                            'Jangan lupa sarapan, biar otak nggak ngelag!',
                            'Belajar boleh, rebahan juga perlu (tapi jangan kebanyakan).',
                            'Ujian itu kayak wifi, kadang nyambung kadang lost.',
                            'Kalau tugas numpuk, ingat: satu-satu aja, jangan panik!',
                            'Ngantuk di kelas itu wajar, asal jangan ngorok.',
                            'Jangan takut salah, yang penting bukan salah jurusan.',
                            'Kesiswaan? Tenang, mereka juga manusia kok.',
                            'Jangan lupa cukur sebelum dicukurin.',
                            'Buku tebal bukan buat bantalan tidur, tapi buat dibaca.',
                            'Kalau nggak paham, tanya! Jangan cuma ngangguk-ngangguk.',
                            'Jangan lupa minum air putih, biar nggak error.',
                            'Kalau nilai jelek, jangan salahkan kalkulator.',
                            'Semangat sekolah, biar bisa liburan dengan tenang.',
                            'Ingat, tugas itu bukan hantu. Tapi kalau numpuk, bisa serem juga.',
                        ])->random() }}
                    </p>
                </div>
            @endforelse
        </div>
        <!-- End of Pengumuman -->
    </main>

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

        <div class="reminders">
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

<script>
    function openReminderModal() {
        document.getElementById("modalReminder").style.display = "flex";
    }

    function closeReminderModal() {
        document.getElementById("modalReminder").style.display = "none";
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reminder check-in buat siswa
        fetch('/siswa/presensi-reminder')
            .then(response => response.json())
            .then(res => {
                if (!res.jam_masuk) {
                    Swal.fire({
                        title: '',
                        html: 'Kamu belum Check In hari ini<br>Yuk Check In sekarang!',
                        icon: 'warning',
                        confirmButtonText: 'Check In',
                        confirmButtonColor: '#e7586e',
                        showCancelButton: true,
                        cancelButtonText: 'Nanti aja',
                        cancelButtonColor: '#3085d6',
                        background: isDarkMode() ? getBg() : '#fff',
                        color: isDarkMode() ? getColor() : '#000',
                        customClass: {
                            popup: isDarkMode() ? 'swal-dark' : ''
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                '/siswa/presensi'; // arahkan ke halaman presensi siswa
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Gagal cek reminder presensi:', error);
            });

        // Fungsi bantu dark mode
        function isDarkMode() {
            return document.body.classList.contains('dark-mode-variables');
        }

        function getBg() {
            return getComputedStyle(document.body).getPropertyValue('--color-background');
        }

        function getColor() {
            return getComputedStyle(document.body).getPropertyValue('--color-dark');
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/siswa/presensi/hari-ini')
            .then(response => response.json())
            .then(data => {
                const jamMasuk = data.jam_masuk;
                const jamKeluar = data.jam_keluar;

                updateCheckInfoBeranda(jamMasuk, jamKeluar);
            })
            .catch(error => {
                console.error('Gagal ambil data presensi', error);
                updateCheckInfoBeranda(null, null);
            });
    });

    function updateCheckInfoBeranda(jamMasuk = null, jamKeluar = null) {
        const jamIn = jamMasuk ? jamMasuk.slice(0, 5) : '--:--';
        const jamOut = jamKeluar ? jamKeluar.slice(0, 5) : '--:--';

        document.getElementById('checkInBerandaSiswa').innerHTML =
            `<span class="material-icons-sharp">check_circle</span> <span>Check In ${jamIn}</span>`;

        document.getElementById('checkOutBerandaSiswa').innerHTML =
            `<span class="material-icons-sharp">cancel</span> <span>Check Out ${jamOut}</span>`;
    }
</script>
