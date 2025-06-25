@extends('guru.layouts.app')
@php
    $nama = explode(' ', Auth::user()->guru->nama);
    $namaPendek = implode(' ', array_slice($nama, 0, 1));
@endphp
@section('content')
    <main>

        <h2 class="section-title-feature">Fitur Guru</h2>
        <div class="status-presensi">
            <div class="check-item checked" id="checkInBerandaGuru">
                <span class="material-icons-sharp">check_circle</span>
                <span>Check In --:--</span>
            </div>
            <div class="check-item unchecked" id="checkOutBerandaGuru">
                <span class="material-icons-sharp">cancel</span>
                <span>Check Out --:--</span>
            </div>
        </div>

        <!-- Fitur Guru Section -->
        <div class="feature-card">
            <div class="icon-group">
                <a href="{{ route('guru.presensi') }}"
                    class="icon-item {{ request()->routeIs('guru.presensi') ? 'active' : '' }}">
                    <span class="material-icons-sharp">work_history</span>
                    <p>Presensi</p>
                </a>
                <a href="{{ route('guru.izin') }}" class="icon-item {{ request()->routeIs('guru.izin') ? 'active' : '' }}">
                    <span class="material-icons-sharp">forward_to_inbox</span>
                    <p>Izin</p>
                </a>
                <a href="{{ route('guru.jadwal') }}"
                    class="icon-item {{ request()->routeIs('guru.jadwal') ? 'active' : '' }}">
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
        <!-- End of Fitur Guru Section -->

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
                            'Terima kasih atas dedikasi Anda, semangat menginspirasi hari ini!',
                            'Setiap hari adalah peluang baru untuk membimbing generasi penerus.',
                            'Jangan lupa rehat sejenak, kesehatan juga penting untuk mengajar.',
                            'Mengajar adalah pekerjaan mulia, tetap semangat dan terus berkarya!',
                            'Senyum Anda hari ini bisa jadi semangat bagi siswa-siswi.',
                            'Jika ada tantangan, yakinlah Anda mampu melewatinya.',
                            'Jangan ragu berbagi ilmu, satu kata Anda bisa mengubah masa depan.',
                            'Kadang lelah, tapi hasilnya akan terasa di masa depan.',
                            'Terus belajar dan berinovasi, dunia pendidikan menanti karya Anda.',
                            'Setiap usaha Anda sangat berarti untuk sekolah dan siswa.',
                            'Jangan lupa sarapan dan minum air putih sebelum mengajar.',
                            'Mengajar bukan hanya soal materi, tapi juga membentuk karakter.',
                            'Jika hari ini terasa berat, istirahat sejenak dan lanjutkan dengan senyum.',
                            'Anda adalah inspirasi bagi banyak orang, tetaplah menjadi teladan.',
                            'Semangat mengajar hari ini, hasilnya akan terasa di masa depan.',
                            'Setiap kelas adalah cerita baru, nikmati setiap momennya.',
                            'Terima kasih sudah menjadi bagian penting dalam pendidikan.',
                            'Semoga hari Anda menyenangkan dan penuh keberkahan.',
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
                    <small class="text-muted">Guru</small>
                </div>
                <div class="profile-photo">
                    <img src="{{ asset('assets/img/smeapng.png') }}">
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

            @include('guru.modal.reminder')
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
    document.addEventListener{{ asset('assets/img/smeapng.png') }} / Reminder check - in buat guru
    fetch('/guru/presensi-reminder')
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
                        window.location.href {{ asset('assets/img/smeapng.png') }};
                        / arahkan ke halaman presensi guru
                    }
                });
            }
        })
        .catch(error => {
            console.error(
                'Gagal cek reminder presensi{{ asset('assets/img/smeapng.png') }} / Fungsi bantu dark mode
                function isDarkMode() {
                    return document.body.classList.contains('dark-mode-variables');
                }

                function getBg() {
                    return getComputedStyle(document.body).getPropertyValue('--color-background');
                }

                function getColor() {
                    return getComputedStyle(document.body).getPropertyValue('--color-dark');
                }
            )
        });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/guru/presensi/hari-ini')
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

        document.getElementById('checkInBerandaGuru').innerHTML =
            `<span class="material-icons-sharp">check_circle</span> <span>Check In ${jamIn}</span>`;

        document.getElementById('checkOutBerandaGuru').innerHTML =
            `<span class="material-icons-sharp">cancel</span> <span>Check Out ${jamOut}</span>`;
    }
</script>
