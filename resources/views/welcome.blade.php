<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Nesasen - Digital Presensi & Jadwal</title>
    <meta name="description" content="Aplikasi Presensi dan Jadwal Digital untuk Sekolah">
    <meta name="keywords" content="presensi, jadwal, sekolah, nesasen, laravel">

    <!-- Favicons -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('enno/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('enno/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('enno/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('enno/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('enno/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('enno/assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">
    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">
            <a href="#" class="logo d-flex align-items-center me-auto">
                <img src="{{ asset('img/ls-logo.png') }}" alt="Nesasen Logo" />
            </a>
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Beranda</a></li>
                    <li><a href="#about">Tentang</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
            <a class="btn-getstarted" href="{{ route('selectRole') }}">Login</a>
        </div>
    </header>

    <main class="main">
        <section id="hero" class="hero section">
            <div class="container">
                <div class="row gy-4">
                    <div class="order-2 col-lg-6 order-lg-1 d-flex flex-column justify-content-center"
                        data-aos="fade-up">
                        <h1>NESASEN</h1>
                        <p>Platform Presensi dan Informasi Jadwal Sekolah Berbasis Web</p>
                        <div class="d-flex">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn-get-started">Dashboard</a>
                            @else
                                <a href="{{ route('selectRole') }}" class="btn-get-started">Login</a>
                            @endauth
                        </div>
                    </div>
                    <div class="order-1 col-lg-6 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="100">
                        <img src="{{ asset('img\communitiy.png') }}" class="img-fluid animated" alt="">
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="about section">
            <div class="container section-title" data-aos="fade-up">
                <span>Tentang Kita<br></span>
                <h2>Tentang</h2>
                <p>NESASEN adalah aplikasi berbasis web yang dirancang untuk membantu proses absensi siswa dan
                    pengelolaan jadwal pembelajaran secara digital di lingkungan sekolah.</p>
            </div>

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
                    </div>
                    <div class="col-xl-12 content" data-aos="fade-up" data-aos-delay="200">
                        <h3>Fitur Utama NESASEN</h3>
                        <p class="fst-italic">
                            Aplikasi ini memiliki beberapa fitur utama yang mendukung sistem manajemen sekolah digital.
                        </p>
                        <ul>
                            <li><i class="bi bi-check2-all"></i> <span>Presensi berbasis lokasi menggunakan polygon area
                                    sekolah.</span></li>
                            <li><i class="bi bi-check2-all"></i> <span>Validasi kehadiran dengan upload selfie secara
                                    langsung.</span></li>
                            <li><i class="bi bi-check2-all"></i> <span>Tampilan jadwal harian, mingguan, dan bulanan
                                    untuk guru dan siswa.</span></li>
                        </ul>
                        <p>
                            NESASEN mempermudah guru, wali kelas, dan admin dalam mengelola data kehadiran siswa secara
                            real-time dan efisien.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="contact section">
            <div class="container section-title" data-aos="fade-up">
                <span>Kontak</span>
                <h2>Kontak</h2>
                <p>Jika Anda ingin mengetahui lebih lanjut tentang NESASEN atau ingin bekerja sama, silakan hubungi kami
                    melalui informasi di bawah ini.</p>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-4">
                    <div class="col-xl-12">
                        <div class="info-wrap">
                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                                <i class="flex-shrink-0 bi bi-geo-alt"></i>
                                <div>
                                    <h3>Alamat</h3>
                                    <p>Jl. Arief Rahman Hakim No.35, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa
                                        Barat 41211</p>
                                </div>
                            </div>

                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                                <i class="flex-shrink-0 bi bi-telephone"></i>
                                <div>
                                    <h3>Telepon</h3>
                                    <p>0260-411410</p>
                                </div>
                            </div>

                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                                <i class="flex-shrink-0 bi bi-envelope"></i>
                                <div>
                                    <h3>Email</h3>
                                    <p>nesasen.project@gmail.com</p>
                                </div>
                            </div>

                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.7285140400804!2d107.75731297499314!3d-6.555916893437204!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e693c81014f0375%3A0x92c68964fc492d33!2sSMK%20Negeri%201%20Subang!5e0!3m2!1sid!2sid!4v1744180801592!5m2!1sid!2sid"
                                frameborder="0" style="border:0; width: 100%; height: 270px;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>

                    <div class="col-lg-7">
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="footer" class="footer">
        <div class="container mt-4 text-center copyright">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">2025</strong> <span>All Rights Reserved</span>
            </p>
        </div>
    </footer>

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <div id="preloader"></div>

    <script src="{{ asset('enno/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('enno/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('enno/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('enno/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('enno/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('enno/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('enno/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('enno/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('enno/assets/js/main.js') }}"></script>
</body>

</html>
