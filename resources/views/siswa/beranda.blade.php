@extends('layouts.app')

@section('content')
    <div class="beranda-container">
        <!-- Clock In/Out Section -->
        <div class="clock-section">
            <button class="clock-btn active">
                <i class="fas fa-clock"></i> Clock In
            </button>
            <button class="clock-btn">
                <i class="fas fa-clock"></i> Clock Out
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
    </script>
@endsection
