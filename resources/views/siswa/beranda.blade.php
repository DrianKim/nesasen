@extends('admin.layouts.app')

@section('content')
    <div class="beranda-container">
        <!-- Check In/Out Info -->
        <div class="clock-section">
            <button id="checkInBeranda" class="clock-btn active">
                <i class="fas fa-clock"></i> Check In --:--
            </button>
            <button id="checkOutBeranda" class="clock-btn">
                <i class="fas fa-clock"></i> Check Out --:--
            </button>
        </div>

        <!-- Main Features Section -->
        <div class="features-section">
            <h2 class="section-title">Fitur Utama</h2>

            <div class="features-grid">
                <!-- Feature 1: Presensi -->
                <div class="feature-item">
                    <a href="{{ route('siswa.presensi') }}">
                        <div class="feature-icon red">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="feature-name">Presensi</div>
                    </a>
                </div>

                <!-- Feature 2: Izin -->
                <div class="feature-item">
                    <a href="{{ route('siswa.izin') }}">
                        <div class="feature-icon blue">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </a>
                    <div class="feature-name">Izin</div>
                </div>

                <!-- Feature 3: Histori -->
                <div class="feature-item">
                    <div class="feature-icon orange">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="feature-name">Histori</div>
                </div>

                <!-- Feature 4: Jadwal -->
                <div class="feature-item">
                    <a href="{{ route('siswa.jadwal') }}">
                        <div class="feature-icon red">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </a>
                    <div class="feature-name">Jadwal</div>
                </div>

                <!-- Feature 5: Nilai -->
                <div class="feature-item">
                    <div class="feature-icon blue">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="feature-name">Nilai</div>
                </div>

                <!-- Feature 6: Tugasku -->
                <div class="feature-item">
                    <a href="{{ route('siswa.kelasKu.index') }}">
                        <div class="feature-icon red">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </a>
                    <div class="feature-name">KelasKu</div>
                </div>

                <!-- Feature 7: Catatan Sikap -->
                <div class="feature-item">
                    <div class="feature-icon blue">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="feature-name">Catatan Sikap</div>
                </div>

                <!-- Feature 8: E-Rapor -->
                <div class="feature-item">
                    <div class="feature-icon red">
                        <i class="fas fa-file-chart-line"></i>
                    </div>
                    <div class="feature-name">E-Rapor</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/siswa/presensi-reminder',
                method: 'GET',
                success: function(res) {
                    if (!res.jam_masuk) {
                        Swal.fire({
                            title: '',
                            html: 'Kamu belum Check In hari ini<br>Yuk Check In sekarang',
                            icon: 'warning',
                            // showCancelButton: true,
                            confirmButtonText: 'Check In',
                            confirmButtonColor: '#3085d6',
                            // cancelButtonText: 'Nanti aja',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/siswa/presensi';
                            }
                        });
                    }
                }
            });
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

            document.getElementById('checkInBeranda').innerHTML =
                `<i class="fas fa-check-circle"></i> Check In ${jamIn}`;

            document.getElementById('checkOutBeranda').innerHTML =
                `<i class="fas fa-times-circle"></i> Check Out ${jamOut}`;
        }
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Carousel functionality
            const slides = document.querySelectorAll('.carousel-slide');
            const indicators = document.querySelectorAll('.indicator');
            let currentSlide = 0;

            function showSlide(index) {
                slides.forEach(slide => slide.classList.remove('active'));
                indicators.forEach(indicator => indicator.classList.remove('active'));

                slides[index].classList.add('active');
                indicators[index].classList.add('active');
                currentSlide = index;
            }

            // Auto rotate slides
            setInterval(() => {
                let nextSlide = (currentSlide + 1) % slides.length;
                showSlide(nextSlide);
            }, 5000);

            // Click on indicators
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    showSlide(index);
                });
            });

            // Clock In/Out toggle
            const clockBtns = document.querySelectorAll('.clock-btn');

            clockBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    clockBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                });
            });
        });
    </script> --}}
@endsection
