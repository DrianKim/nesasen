<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Nesasen - Learning Management System for SMKN 1 Subang">
    <meta name="author" content="Development Team">
    <meta name="theme-color" content="#4e73df">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nesasen | {{ $title }}</title>

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    {{-- <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/coba.css') }}" />

    <!-- Custom styles for this template-->
    {{-- <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet"> --}}

    {{-- SweetAlert --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

    {{-- ///// --}}

    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- PWA elements -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Nesasen">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-152x152.png') }}">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Css Custom -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style-app.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style-beranda.css') }}"> --}}
</head>

<body>
    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo">
                    <img src="{{ asset('img/ls-logo.png') }}" />
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp"> close </span>
                </div>
            </div>

            <div class="sidebar">
                <a href="#" class="active beranda">
                    <span class="material-icons-sharp"> dashboard </span>
                    <h3>Beranda</h3>
                </a>
                <a href="{{ route('admin_kelas.index') }}">
                    <span class="material-icons-sharp"> class </span>
                    <h3>Data Kelas</h3>
                </a>
                <a href="{{ route('admin_siswa.index') }}">
                    <span class="material-icons-sharp"> school </span>
                    <h3>Data Siswa</h3>
                </a>
                <a href="{{ route('admin_guru.index') }}">
                    <span class="material-icons-sharp"> school </span>
                    <h3>Data Guru</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp"> assignment </span>
                    <h3>Akademik</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp"> assignment_turned_in </span>
                    <h3>Presensi</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp"> drafts </span>
                    <h3>Perizinan</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp"> grading </span>
                    <h3>Penilaian</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp"> calendar_month </span>
                    <h3>Tahun Ajaran</h3>
                </a>
                {{-- <a href="#">
                    <span class="material-icons-sharp"> notifications </span>
                    <h3>Notifikasi</h3>
                    <span class="message-count">7</span>
                </a> --}}
                <a href="{{ route('logout') }}" class="btn-logout" id="logout-btn">
                    <span class="material-icons-sharp"> logout </span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Right Section -->
        <div class="right-section">
            <div class="nav">
                <button id="menu-btn">
                    <span class="material-icons-sharp"> menu </span>
                </button>
                <div class="dark-mode">
                    <span class="material-icons-sharp active"> light_mode </span>
                    <span class="material-icons-sharp"> dark_mode </span>
                </div>

                <div class="profile">
                    <div class="info">
                        <p>Halo, <b>{{ Auth::user()->guru->nama ?? 'Guru' }}</b></p>
                        <small class="text-muted">Guru</small>
                    </div>
                    <div class="profile-photo">
                        <img src="{{ asset('images/profile-1.jpg') }}" />
                    </div>
                </div>
            </div>

            <div class="news">
                <iframe src="https://www.instagram.com/p/DJWiNpzSK2i/embed" width="100%" height="335"
                    frameborder="0" scrolling="no" allowtransparency="true">
                </iframe>
            </div>


            <div class="reminders">
                <div class="header">
                    <h2>Reminders</h2>
                    {{-- <span class="material-icons-sharp"> notifications_none </span> --}}
                </div>

                <div class="notification">
                    <div class="icon">
                        <span class="material-icons-sharp"> schedule </span>
                    </div>
                    <div class="content">
                        <div class="info">
                            <h3>Mengajar di kelas XI RPL</h3>
                            <small class="text_muted"> 08:00 AM - 09:30 PM </small>
                        </div>
                        <span class="material-icons-sharp"> more_vert </span>
                    </div>
                </div>

                <div class="notification deactive">
                    <div class="icon">
                        <span class="material-icons-sharp"> groups </span>
                    </div>
                    <div class="content">
                        <div class="info">
                            <h3>Workshop</h3>
                            <small class="text_muted"> 10:00 AM - 12:00 PM </small>
                        </div>
                        <span class="material-icons-sharp"> more_vert </span>
                    </div>
                </div>

                <div class="notification add-reminder">
                    <div>
                        <span class="material-icons-sharp"> add </span>
                        <h3>Tambah Reminder</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} Made with ❤️ by P & R.</p>
    </footer>

    <script>
        // Saat halaman diload, cek localStorage
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode-variables');

            const lightIcon = document.querySelector('.dark-mode span:nth-child(1)');
            const darkIcon = document.querySelector('.dark-mode span:nth-child(2)');

            lightIcon?.classList.remove('active');
            darkIcon?.classList.add('active');
        }
    </script>

    <script>
        const sideMenu = document.querySelector('aside');
        const menuBtn = document.getElementById('menu-btn');
        const closeBtn = document.getElementById('close-btn');
        const darkMode = document.querySelector('.dark-mode');

        menuBtn?.addEventListener('click', () => sideMenu.style.display = 'block');
        closeBtn?.addEventListener('click', () => sideMenu.style.display = 'none');

        // Saat toggle diklik
        darkMode?.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode-variables');

            const isDark = document.body.classList.contains('dark-mode-variables');
            localStorage.setItem('darkMode', isDark ? 'true' : 'false');

            darkMode.querySelector('span:nth-child(1)')?.classList.toggle('active');
            darkMode.querySelector('span:nth-child(2)')?.classList.toggle('active');
        });

        document.getElementById('logout-btn').addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah aksi default
            Swal.fire({
                title: 'Apakah Anda yakin ingin logout?',
                text: "Anda akan keluar dari sesi saat ini.",
                icon: 'warning',
                showCancelButton: true,
                iconColor: '#e7586e',
                confirmButtonColor: '#e7586e',
                cancelButtonColor: '#43c6c9',
                confirmButtonText: 'Ya, logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('logout') }}"; // Redirect ke route logout
                }
            });
        });
    </script>
</body>

</html>
