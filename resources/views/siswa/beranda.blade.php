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

        <!-- Carousel/Banner Section -->
        {{-- <div class="carousel-container">
        <div class="carousel-wrapper">
            <!-- Banner 1: Customer Service -->
            <div class="carousel-slide active">
                <div class="banner customer-service">
                    <div class="banner-content">
                        <h2>Kamu Punya Pertanyaan? Atau Mengalami Kendala?</h2>
                        <p>Yuk Hubungi CS Kami!</p>
                        <div class="contact-btn">
                            <i class="fas fa-phone-alt"></i> 0851-1700-0070
                        </div>
                        <div class="operational-hours">
                            <span class="hours-label">Jam Operasional:</span>
                            <span class="hours-time">Senin-Sabtu</span>
                            <span class="hours-detail">07.00 - 19.00 WIB</span>
                        </div>
                    </div>
                    <div class="banner-image">
                        <img src="{{ asset('images/cs-image.png') }}" alt="Customer Service">
                    </div>
                </div>
            </div>

            <!-- Banner 2: Promo Telkomsel -->
            <div class="carousel-slide">
                <div class="banner promo-banner">
                    <img src="{{ asset('images/promo-telkomsel.png') }}" alt="Promo Telkomsel" class="full-banner">
                </div>
            </div>

            <!-- Banner 3: Register Account -->
            <div class="carousel-slide">
                <div class="banner register-banner">
                    <img src="{{ asset('images/register-banner.png') }}" alt="Cara Daftar Akun" class="full-banner">
                </div>
            </div>
        </div>

        <!-- Carousel Navigation -->
        <div class="carousel-indicators">
            <span class="indicator active"></span>
            <span class="indicator"></span>
            <span class="indicator"></span>
            <span class="indicator"></span>
            <span class="indicator"></span>
            <span class="indicator"></span>
        </div>
    </div> --}}

        <!-- Main Features Section -->
        <div class="features-section">
            <h2 class="section-title">Fitur Utama</h2>

            <div class="features-grid">
                <!-- Feature 1: Presensi -->
                <div class="feature-item">
                    <a href="{{ route('siswa.presensi') }}">
                        <div class="feature-icon red">
                            <i class="fas fa-alarm-clock"></i>
                        </div>
                    </a>
                    <div class="feature-name">Presensi</div>
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
                    <div class="feature-icon red">
                        <i class="fas fa-calendar"></i>
                    </div>
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
                    <div class="feature-icon red">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="feature-name">Tugasku</div>
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

    <style>
        .beranda-container {
            padding: 20px;
            background-color: #fff5f5;
        }

        /* Clock In/Out Styles */
        .clock-section {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .clock-btn {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .clock-btn.active {
            background-color: #e9f5ff;
            border-color: #cce5ff;
            color: #0d6efd;
        }

        /* Carousel/Banner Styles */
        .carousel-container {
            margin-bottom: 30px;
            position: relative;
        }

        .carousel-wrapper {
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            height: 200px;
        }

        .carousel-slide {
            display: none;
            height: 100%;
        }

        .carousel-slide.active {
            display: block;
        }

        .banner {
            height: 100%;
            border-radius: 15px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
        }

        .customer-service {
            background-color: #ffebe3;
            padding: 20px;
        }

        .banner-content {
            flex: 1;
            padding-right: 20px;
        }

        .banner-content h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .banner-content p {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .contact-btn {
            display: inline-flex;
            align-items: center;
            background-color: #25d366;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            gap: 5px;
            margin-bottom: 10px;
        }

        .operational-hours {
            display: flex;
            flex-direction: column;
            font-size: 12px;
            background-color: #fff;
            padding: 8px;
            border-radius: 8px;
            width: fit-content;
        }

        .hours-label {
            font-weight: bold;
            color: #666;
        }

        .hours-time {
            font-weight: bold;
        }

        .hours-detail {
            color: #666;
        }

        .banner-image {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40%;
        }

        .banner-image img {
            max-height: 100%;
            max-width: 100%;
        }

        .full-banner {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Carousel Indicators */
        .carousel-indicators {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 10px;
        }

        .indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #ddd;
            cursor: pointer;
        }

        .indicator.active {
            background-color: #ff6b6b;
        }

        /* Features Section */
        .features-section {
            padding: 10px 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .feature-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .feature-icon.red {
            background-color: #ff6b6b;
        }

        .feature-icon.blue {
            background-color: #4dabf7;
        }

        .feature-icon.orange {
            background-color: #ffa94d;
        }

        .feature-name {
            font-size: 14px;
            font-weight: medium;
            color: #333;
            text-align: center;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .features-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 576px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .banner {
                flex-direction: column;
            }

            .banner-image {
                width: 100%;
            }
        }
    </style>

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
